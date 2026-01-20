<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-col gap-6">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @livewire(\App\Livewire\ApplicationStats::class)
        </div>

        <!-- Latest Sales Table -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl p-4 shadow">
            <h2 class="text-xl font-semibold mb-4">{{ __('Latest & Trending Sales') }}</h2>

            <!-- Two sections side by side -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Latest Sales Table -->
                <div class="bg-white dark:bg-zinc-700 rounded-xl p-4 shadow overflow-x-auto">
                    <h3 class="text-lg font-medium mb-2">{{ __('Latest Sales') }}</h3>
                    @livewire(\App\Livewire\LatestSales::class)
                </div>

                <!-- Sales Trend Chart -->
                <div class="bg-white dark:bg-zinc-700 rounded-xl p-4 shadow">
                    <h3 class="text-lg font-medium mb-2">{{ __('Sales Trend') }}</h3>
                    @livewire(\App\Livewire\SalesTrendChart::class)
                </div>
            </div>
        </div>


        <div class="bg-white dark:bg-zinc-800 rounded-xl p-4 shadow">
            <h2 class="text-xl font-semibold mb-4">{{ __('Top Products & Customer Growth') }}</h2>
            
            <!-- Two charts side by side -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-700 rounded-xl p-4 shadow">
                    @livewire(\App\Livewire\TopProductsChart::class)
                </div>
                
                <div class="bg-white dark:bg-zinc-700 rounded-xl p-4 shadow">
                    @livewire(\App\Livewire\CustomerGrowthChart::class)
                </div>
            </div>
        </div>

        
    </div>
</x-layouts.app>
