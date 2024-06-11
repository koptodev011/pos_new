<!-- Main Content -->

<div x-data="{ 
    callWaiter: function() { 
        if ('vibrate' in navigator) { 
            navigator.vibrate(500); 
        } else { 
            console.log('Vibration API not supported'); 
        } 
    } 
}" class="relative w-full slide-from-bottom">

    <div class="flex flex-col items-start w-full pb-4 space-y-4">


        <div class="w-full px-4">
            @livewire('search-bar')
        </div>

        <div class="w-full">
            @livewire('carousel')
        </div>

        <!-- Recommendation section -->
        <div class="flex items-center justify-between w-full px-4">
            <div class="flex items-center space-x-2">
                <h2 class="font-bold text-white text-md">Recommended For You</h2>

                <img src="/assets/images/Heart.svg" alt="Image" class="w-4 h-4">
            </div>
            <a href="#" class="inline-flex items-center justify-center space-x-1 text-xs font-semibold text-primaryblue">
                <span>View All</span>
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                    </svg>
                </span>
            </a>


        </div>

        <div class="w-full pl-4 overflow-x-auto flex space-x-4">
            @foreach($menu_tags['Recommended'] as $menu)
            <livewire:menu-card key="{{$menu->id}}_recommended" :status="$menu->status" :vertical="true" :menu="$menu" :currency="$currency" />
            @endforeach
        </div>
        <!-- Recommendation section  end -->

        <!-- Hottest Offers section -->
        <div class="flex items-center justify-between w-full px-4">
            <div class="flex items-center space-x-2">
                <h2 class="font-bold text-white text-md">Hottest Offers</h2>
                <img src="/assets/images/Discount.png" alt="Image" class="w-4 h-4">
            </div>
            <a href="#" class="inline-flex items-center justify-center space-x-1 text-xs font-semibold text-primaryblue">
                <span>View All</span>
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                    </svg>
                </span>
            </a>
        </div>

        <div class="w-full pl-4 overflow-x-auto flex space-x-4">
            @foreach($menu_tags['Hottest Offers'] as $menu)
            <livewire:menu-card key="{{$menu->id}}_offers" :status="$menu->status" :vertical="true" :menu="$menu" :currency="$currency" />
            @endforeach
        </div>
        <!-- Hottest Offers section End -->


        <!-- Hot selling -->
        <div class="flex flex-col w-full px-4 py-4 space-y-2 bg-gradient-to-b from-gr to-gr2">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center space-x-2">
                    <h2 class="font-bold text-white text-md">Hot Selling</h2>
                    <img src="/assets/images/hot_selling.svg" alt="Image" class="w-4 h-4">
                </div>
                <a href="#" class="inline-flex items-center justify-center space-x-1 text-xs font-semibold text-primaryblue">
                    <span>View All</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </a>
            </div>

            <div class="w-full overflow-x-auto flex space-x-4">
                @foreach($menu_tags['Hot Selling'] as $menu)
                <livewire:menu-card key="{{$menu->id}}_selling" :status="$menu->status" :vertical="true" :menu="$menu" :currency="$currency" />
                @endforeach
            </div>

        </div>
        <!-- Hot selling End -->

        <!-- Meal Type  -->
        <div class="flex items-center justify-between w-full px-4">
            <livewire:customer.components.comp-menu-type />
            <a href="#" class="inline-flex items-center justify-center space-x-1 text-xs font-semibold text-primaryblue">
                <img src="/assets/images/Filter.png" alt="Image" class="w-4 h-4">
                <span>Filter</span>
            </a>
        </div>
        <!-- Meal Type End -->

        <!-- Category -->
        <div class="w-full ">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center space-x-2">
                    <h2 class="font-bold text-white pl-4 text-md">Categories</h2>
                </div>
            </div>
            <div class="w-full pt-2 overflow-x-auto pl-4">
                <livewire:customer.components.comp-category-list />
            </div>
        </div>
        <!-- Category End -->

        <!-- Menu List -->
        <div class="flex items-center justify-between w-full px-4">
            <div class="flex items-center space-x-2">
                <h2 class="font-bold text-white text-md">Menu</h2>
                <img src="/assets/images/dish.svg" alt="Image" class="w-4 h-4">
            </div>
        </div>

        <livewire:customer.components.comp-menu-list-filter :currency="$currency" />
        <!-- Menu List End -->
        <div class="pb-10"></div>

    </div>

    <!-- Fixed Call Waiter Button -->




    <div class="fixed z-10 mb-6 shadow-xl right-5 bottom-16 sm:bottom-10 sm:right-auto sm:left-5 md:right-5 md:left-auto">
        <div x-data="{ isClicked: false }">
            <button x-bind:class="{ 'bg-green-600': isClicked, 'bg-backgroundblue': !isClicked }" @click="isClicked = !isClicked" @click.prevent="callWaiter" class="w-32 transition-colors duration-300 rounded-full shadow-md h-14 hover:shadow-xl transform-gpu hover:scale-105" style="transition: background-color 0.3s ease;">
                <div class="flex items-center h-full rounded-full bg-backgroundblue">
                    <!-- Add your image here with increased width and height -->
                    <div class="flex items-center justify-center rounded-full w-14 h-14 bg-primaryblue">
                        <img src="/assets/images/waiter.svg" class="w-6 h-6" alt="Waiter Icon">
                    </div>
                    <h6 class="pl-2 text-xs font-bold text-left text-white">Call <br> Waiter</h6>
                </div>
            </button>
        </div>
    </div>
</div>