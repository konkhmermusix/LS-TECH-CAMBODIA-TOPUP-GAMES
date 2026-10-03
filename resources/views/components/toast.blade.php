<div
    x-data
    class="fixed bottom-6 right-6 z-50 flex flex-col gap-2.5 max-w-sm w-full pointer-events-none px-4 sm:px-0"
>
    <template x-for="item in $store.toast.items" :key="item.id">
        <div
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-3 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto flex items-center justify-between gap-3 p-4 rounded-2xl glass-panel border shadow-xl text-sm"
            :class="{
                'border-emerald-500/30 text-emerald-800 dark:text-emerald-200': item.type === 'success',
                'border-rose-500/30 text-rose-800 dark:text-rose-200': item.type === 'error',
                'border-amber-500/30 text-amber-800 dark:text-amber-200': item.type === 'warning',
                'border-blue-500/30 text-blue-800 dark:text-blue-200': item.type === 'info'
            }"
        >
            <div class="flex items-center gap-3">
                <template x-if="item.type === 'success'">
                    <span class="text-emerald-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </span>
                </template>
                <template x-if="item.type === 'error'">
                    <span class="text-rose-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                </template>
                <template x-if="item.type === 'warning'">
                    <span class="text-amber-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </span>
                </template>
                <template x-if="item.type === 'info'">
                    <span class="text-blue-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                </template>
                <span class="font-medium" x-text="item.message"></span>
            </div>
            <button
                type="button"
                @click="$store.toast.remove(item.id)"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors p-1"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </template>
</div>
