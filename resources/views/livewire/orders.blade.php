<div class="antialiased font-urbanist">
    <span class=" fixed top-[62px] text-center w-full bg-primary left-0 z-50 px-4  flex items-center justify-center font-semibold text-white">Orders</span>
    @if(auth()->user())
    <p class="flex px-5 mt-4 text-white">Hello {{ auth()->user()->name }}</p>
    @else
    <p class="flex px-5 mt-4 text-white">Hello Guest</p>

    @endif
    @if(auth()->user())
    @if($menus)
    <span class="flex items-center justify-between h-10 px-4 mx-4 mt-2 border rounded-lg border-bordercol bg-blackgrey">
        <p class="text-white">Your Table Pin:</p>
        <p class="font-bold text-white">@if($menus){{ $menus->code }} @endif</p>
    </span>
    <span class="w-ful px-4 mt-4 flex flex-col space-y-2 slide-from-bottom">
   
        @foreach($menus->orderItems as $menu)
        <livewire:menu-card :menu="$menu->orderable" :status="$menus->status->value" :showAddButton="false" :currency="$currency" />
        @endforeach
       
    </span>
   
    <!-- Order details section -->
    <span class="fle flex-col p-4 fixed z-50 left-0 right-0 mx-auto w-full shadow-xl bottom-[70px] max-w-md items-center rounded-t-3xl bg-blackgrey">
        <span class="flex flex-row justify-between mb-2">
            <span class="flex flex-col justify-between mb-2">
                <h3 class="mb-1 font-semibold text-white text-md">Amount to be Paid</h3>
                <span class="text-xs text-discription">Taxes and Fees are included.</span>
            </span>
            <span class="flex items-center">
                <span class="font-bold text-white">{{ $currency->symbol }} {{ $total }}</span>
                <img src="/assets/images/Arrow.png" alt="Your Image" class="w-5 h-5 ml-2">
            </span>
        </span>
        @if($menus->status->value == 'Ready')
        <button wire:click="navigateToPayment" class="px-4 py-3 w-full font-semibold text-white rounded-md bg-primaryblue text-md">Pay Now</button>
        @else
        <button class="px-4 py-3 w-full font-semibold text-white rounded-md bg-primaryblue text-md">Pay Now</button>

        @endif   
    </span>
    @else
    <div class="flex flex-col justify-center items-center h-auto mt-10">
            <img src="/assets/images/preview.png" alt="Clock Icon" class="w-50 h-40 object-cover">
            <span class="text-white">Your Order is Empty</span>
        </div>
    @endif

    @else
    <div class="flex flex-col justify-center items-center h-auto mt-10">
    <span class="text-white text-center justify-center items-center h-auto mt-36"> To view order <br> Please <a href="/customers/orders/login" class="text-primaryblue">Login</a></span>
    </div>
    @endif

    <!-- </div> -->
    @livewireScripts
</div>