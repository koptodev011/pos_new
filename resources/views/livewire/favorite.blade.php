<div class="">
    <span class=" flex flex-1 mt-1 px-4 w-full rounded-lg slide-from-bottom">
        @livewire('search-bar')
    </span>
    <h2 class="text-md ml-6 py-2 font-bold text-white">Favorites</h2>
    <span class="container px-4 w-full overflow-y-auto">
        <!-- Vertical Scrollable Container -->
        <span class="flex mx-4 flex-col sprace-y-2 mb-2">
            @auth
            @if(count($menus)>0)
            @foreach($menus as $favMenu)
            <livewire:menu-card :key="$favMenu->menu->id" :menu="$favMenu->menu" :currency="$currency" />
            @endforeach
            @else
            <span class="flex flex-col justify-center items-center h-auto mt-10">
                <img src="/assets/images/no_fev.png" alt="Clock Icon" class="w-50 h-40 object-cover">
                <span class="text-white">Please add menus in Favourites</span>
            </span>
            @endif
            @else
            <span class="text-white text-center justify-center items-center h-auto mt-36"> To add menus in Favourites <br> Please <a href="/customers/orders/login" class="text-primaryblue">Login</a></span>
            @endauth
        </span>
    </span>
</div>