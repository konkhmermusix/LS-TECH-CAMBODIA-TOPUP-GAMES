@props([
    'name',
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'confirmText' => 'Confirm',
    'confirmVariant' => 'danger',
    'action' => '#',
    'method' => 'POST',
])

<x-modal :name="$name" :title="$title" maxWidth="md">
    <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">{{ $message }}</p>

    <form action="{{ $action }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}" class="flex justify-end gap-3">
        @if($method !== 'GET')
            @csrf
            @if(!in_array(strtoupper($method), ['GET', 'POST']))
                @method($method)
            @endif
        @endif

        <x-button variant="ghost" type="button" x-on:click="show = false">
            Cancel
        </x-button>

        <x-button :variant="$confirmVariant" type="submit">
            {{ $confirmText }}
        </x-button>
    </form>
</x-modal>
