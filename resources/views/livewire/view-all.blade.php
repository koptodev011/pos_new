<div class="container  overflow-y-auto slide-from-bottom">

    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 400); setTimeout(() => show = true, 401)" x-transition:enter="slide-from-bottom-enter" x-transition:enter-start="slide-from-bottom-enter" x-transition:enter-end="slide-from-bottom-enter-active">
        <!-- Main Content -->
        <div class="container mx-auto overflow-y-auto">
            <div class="container mx-auto overflow-y-auto">
                <!-- Menu Page -->
                <div class="flex flex-1 mt-1 px-4 rounded-lg slide-from-bottom">
                    @livewire('search-bar')
                </div>
                <div class=" px-4 w-full ">
                    <div class="flex items-center m-2  justify-between  pt-3 ">
                        <div class="flex items-center">
                            <h2 class="text-md  font-bold text-white">Menu</h2>
                            <img src="/assets/images/dis.svg" alt="Hot Selling Icon" class="h-5 w-5 ml-2">
                        </div>
                    </div>

                    <div class="container overflow-y-auto">
                        <div class="mt-1 pb-20 whitespace-nowrap">
                            <!-- Vertical Scrollable Container -->
                            <div class="flex flex-col space-y-2 mb-2">
                                @if($menus->isEmpty())
                                <p class="text-white">No data available</p>
                                @else
                                <livewire:menu-card layout="horizontal" key="{{ now() }}" :menus="$menus" :currency="$currency" />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>