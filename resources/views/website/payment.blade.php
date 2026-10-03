<x-app-layout :title="'ABA KHQR Payment — Order #' . $order->order_number">

    <div
        x-data="{
            orderNumber: '{{ $order->order_number }}',
            qrString: '{{ $payment->qr_string }}',
            deepLink: '{{ $payment->deep_link }}',
            expiresAt: new Date('{{ $payment->expires_at->toIso8601String() }}').getTime(),
            remainingTime: '',
            isExpired: false,
            pollTimer: null,
            isChecking: false,
            paymentStatus: '{{ $payment->status }}',
            isSimulating: false,

            init() {
                this.renderQrCode();
                this.startCountdown();
                this.startPolling();
            },

            renderQrCode() {
                this.$nextTick(() => {
                    const container = document.getElementById('qrcode-container');
                    if (container && this.qrString) {
                        container.innerHTML = '';
                        new QRCode(container, {
                            text: this.qrString,
                            width: 230,
                            height: 230,
                            colorDark: '#0b132b',
                            colorLight: '#ffffff',
                            correctLevel: QRCode.CorrectLevel.M
                        });
                    }
                });
            },

            startCountdown() {
                const update = () => {
                    const now = new Date().getTime();
                    const diff = this.expiresAt - now;

                    if (diff <= 0) {
                        this.isExpired = true;
                        this.remainingTime = '00:00';
                        if (this.pollTimer) clearInterval(this.pollTimer);
                        return;
                    }

                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    this.remainingTime = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
                };

                update();
                setInterval(update, 1000);
            },

            startPolling() {
                this.pollTimer = setInterval(async () => {
                    if (this.isExpired || this.paymentStatus === 'paid') {
                        return;
                    }

                    try {
                        const response = await fetch('/payment/' + this.orderNumber + '/status', {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await response.json();

                        if (data.paid) {
                            clearInterval(this.pollTimer);
                            this.paymentStatus = 'paid';
                            $store.toast.success('Payment Verified! Delivering Diamonds...');
                            setTimeout(() => {
                                window.location.href = data.redirect_url;
                            }, 1200);
                        }
                    } catch (e) {
                        console.error('Polling error', e);
                    }
                }, 3000);
            },

            async simulatePayment() {
                this.isSimulating = true;
                try {
                    const response = await fetch('/payment/' + this.orderNumber + '/simulate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();

                    if (data.success) {
                        $store.toast.success('Sandbox Payment Confirmed!');
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 1000);
                    } else {
                        $store.toast.error(data.message || 'Simulation failed');
                        this.isSimulating = false;
                    }
                } catch (e) {
                    $store.toast.error('Simulation error');
                    this.isSimulating = false;
                }
            }
        }"
        class="max-w-xl mx-auto py-4 sm:py-8"
    >
        <!-- Top Status Card -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 border border-white/50 dark:border-white/10 text-center shadow-xl mb-6">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-white/10 mb-6">
                <div class="text-left">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Order Reference</span>
                    <h3 class="font-mono text-sm font-black text-slate-900 dark:text-white">{{ $order->order_number }}</h3>
                </div>

                <div class="text-right">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Time Left</span>
                    <p class="font-mono text-base font-black text-rose-500" x-text="remainingTime"></p>
                </div>
            </div>

            <!-- Total Amount Header -->
            <div class="mb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Payable Amount</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mt-1">
                    {{ $order->formatted_total }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    {{ $order->game->name }} &bull; {{ $order->package_name }}
                </p>
            </div>

            <!-- KHQR QR Code Presentation Card -->
            <div class="relative mx-auto w-64 h-64 p-3 bg-white rounded-3xl shadow-xl border border-gray-200/80 flex flex-col items-center justify-center">
                <!-- KHQR Brand Top Ribbon -->
                <div class="flex items-center justify-between w-full px-2 mb-1">
                    <span class="font-extrabold text-[11px] tracking-tight text-red-600">KHQR</span>
                    <span class="font-extrabold text-[10px] text-blue-800">ABA PayWay</span>
                </div>

                <!-- Canvas QR container -->
                <div id="qrcode-container" class="w-full flex items-center justify-center"></div>

                <!-- Expired Overlay -->
                <div
                    x-show="isExpired"
                    x-transition
                    class="absolute inset-0 bg-white/95 rounded-3xl flex flex-col items-center justify-center p-4"
                    style="display: none;"
                >
                    <svg class="w-10 h-10 text-rose-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="font-bold text-sm text-slate-900">QR Code Expired</p>
                    <p class="text-xs text-slate-500 mt-1">Please initiate a new checkout.</p>
                    <a href="{{ route('topup.show', $order->game->slug) }}" class="mt-4 px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold">
                        Try Again
                    </a>
                </div>
            </div>

            <!-- Real-Time Waiting Pulse -->
            <div class="mt-6 flex items-center justify-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-ping"></span>
                <span>Waiting for your ABA Mobile scan...</span>
            </div>

            <!-- Deep link for Mobile Phones -->
            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-white/10 space-y-3">
                <a
                    :href="deepLink"
                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold shadow-lg shadow-blue-500/30 active:scale-[0.98] transition-all"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9v-2h2v2zm0-4H9V7h2v5zm4 4h-2v-2h2v2zm0-4h-2V7h2v5z"/></svg>
                    <span>Tap to Pay with ABA Mobile</span>
                </a>
                <p class="text-[11px] text-slate-400">Opens ABA Mobile app automatically on your phone</p>
            </div>

            <!-- Sandbox Testing Simulator Button -->
            <div class="mt-6 pt-5 border-t border-dashed border-gray-200 dark:border-white/10">
                <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-4 text-left">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-amber-600 dark:text-amber-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Sandbox / Development Simulator</span>
                        </span>
                        <span class="text-[10px] font-mono text-amber-500 bg-amber-500/20 px-2 py-0.5 rounded">TEST</span>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">
                        Testing without a real ABA account? Click below to simulate instant payment confirmation and automatic top-up delivery.
                    </p>
                    <button
                        type="button"
                        @click="simulatePayment()"
                        :disabled="isSimulating"
                        class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-white font-bold text-xs shadow-md transition-colors"
                    >
                        <span x-show="!isSimulating">Simulate Successful ABA KHQR Payment</span>
                        <span x-show="isSimulating">Verifying & Top-Up Processing...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- How to Pay Guide Card -->
        <div class="glass-card rounded-3xl p-6 border border-white/40 dark:border-white/10 text-xs text-slate-600 dark:text-slate-300 space-y-2">
            <h4 class="font-bold text-sm text-slate-900 dark:text-white mb-2">How to Pay with KHQR:</h4>
            <ol class="list-decimal list-inside space-y-1.5 text-slate-500 dark:text-slate-400">
                <li>Open <strong>ABA Mobile</strong> or any Bakong-compatible Cambodian banking app.</li>
                <li>Tap <strong>QR Scan</strong> at the bottom of the screen.</li>
                <li>Scan the QR code displayed above.</li>
                <li>Confirm the payment of <strong>{{ $order->formatted_total }}</strong>.</li>
                <li>Your diamonds will be delivered to Player UID <strong>{{ $order->player_id }}</strong> automatically!</li>
            </ol>
        </div>
    </div>

</x-app-layout>
