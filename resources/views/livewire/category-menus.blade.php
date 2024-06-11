<div class="container  overflow-y-auto slide-from-bottom">
    <div x-data="{ show: true }">

        <div class="container mx-auto overflow-y-auto">

            <div class=" px-4 w-full ">
                <div class="flex items-center m-2  justify-between  pt-3 ">
                    <div class="flex items-center">
                        <h2 class="text-md font-bold text-white">Recommended For You</h2>
                    </div>
                </div>

                <div class="container overflow-y-auto">
                    <div class="mt-1 pb-20 whitespace-nowrap">

                        <div class="flex flex-col space-y-2 mb-2">
                            <livewire:menu-card layout="horizontal"/>
                            <livewire:menu-card layout="horizontal"/>
                            <livewire:menu-card layout="horizontal"/>
                            <livewire:menu-card layout="horizontal"/>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
</div>
