<div class="p-6 space-y-6">

    <h1 class="text-center text-2xl font-bold text-gray-900 dark:text-gray-100">
        Reports & History
    </h1>

    <!-- Tabs -->
    <div x-data="{ tab: 'items' }">
        <div class="flex gap-2 mb-6">
            <button @click="tab = 'items'"
                :class="tab === 'items' ? 'bg-amber-600 text-white' : 'bg-gray-200 dark:bg-neutral-700'"
                class="px-4 py-2 rounded-lg font-medium">
                Item History
            </button>

            <button @click="tab = 'inventory'"
                :class="tab === 'inventory' ? 'bg-amber-600 text-white' : 'bg-gray-200 dark:bg-neutral-700'"
                class="px-4 py-2 rounded-lg font-medium">
                Inventory History
            </button>

            <button @click="tab = 'users'"
                :class="tab === 'users' ? 'bg-amber-600 text-white' : 'bg-gray-200 dark:bg-neutral-700'"
                class="px-4 py-2 rounded-lg font-medium">
                User History
            </button>

            <button @click="tab = 'payments'"
                :class="tab === 'payments' ? 'bg-amber-600 text-white' : 'bg-gray-200 dark:bg-neutral-700'"
                class="px-4 py-2 rounded-lg font-medium">
                Payment History
            </button>
        </div>

        <!-- Content  -->
        <div x-show="tab === 'items'" x-cloak>
            @livewire('items.item-history')
        </div>

        <div x-show="tab === 'inventory'" x-cloak>
            @livewire('items.inventory-history')
        </div>

        <div x-show="tab === 'users'" x-cloak>
            @livewire('management.user-history')
        </div>

        <div x-show="tab === 'payments'" x-cloak>
            @livewire('management.payment-method-history')
        </div>
    </div>

</div>