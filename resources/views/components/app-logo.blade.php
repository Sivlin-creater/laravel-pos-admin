@props([
'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Ceauty" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="h-10 w-10 fill-current text-red-600 dark:text-red-400" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Ceauty" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="h-10 w-10 fill-current text-red-600 dark:text-red-400" />
        </x-slot>
    </flux:brand>
@endif
