
<div class="font-sans antialiased bg-primary">
    <div x-data="{ showModal: false, isOrderPlaced: false }">
        <span class=" fixed top-[70px] text-center w-full bg-primary left-0 z-50 px-4 py-2 flex items-center justify-center font-semibold text-white">Cart</span>
        @if(count($summary['cart']['cartItems'])!=0)

        <span class="flex items-center justify-between mt-8 mr-2 font-bold text-white text-md px-4 ">{{ count($summary['cart']['cartItems']) }} Items</span>
        <span class="pb-20 mt-2 px-4 flex flex-col space-y-4 slide-from-bottom mb-48">
            @foreach($summary['cart']['cartItems'] as $menu)
            <livewire:menu-card wire:key="{{ $menu->cartable->id }}" :menu="$menu->cartable" :currency="$currency" />
            @endforeach
        </span>
        <!-- Order Details Section -->
        <!-- <div x-data="{ isScrolled: false, prevScrollPos: 0, isAtBottom: false }" :class="{ 'translate-y-0': !isScrolled && !isAtBottom, 'translate-y-full': isScrolled || isAtBottom }" class="fixed grid w-full grid-cols-5 gap-1 py-10 mb-1 transition-transform duration-500 bottom-20 bg-secondary justify-items-center"> -->

        <div class="fixed right-0 w-full mb-0 shadow-xl bottom-[70px] flex items-center rounded-t-3xl bg-blackgrey">

            <!-- Order details section -->
            <span class="flex flex-col w-full  p-4 slide-from-bottom">
                <h3 class="mb-2 font-semibold text-white text-md">Order Details</h3>
                <!-- Subtotal -->
                <span class="flex justify-between w-full mb-2">
                    <p class="text-sm text-gray-400">Sub Total:</p>
                    <p class="text-sm text-white">{{ $summary['currency']['sign'] }} {{ $summary['summary']['sub_total'] }}</p>
                </span>
                <!-- Tax -->
                <span class="flex justify-between w-full mb-4">
                    <span class="text-sm text-gray-400">{{ $summary['summary']['tax']['text'] }} (%):</span>
                    <span class="text-sm text-white">{{ $summary['currency']['sign']}} {{ $summary['summary']['tax']['value'] }}</span>
                </span>
                <hr class="mb-2 border-gray-400 border-dashed">
                <!-- Total -->
                <span class="flex justify-between mb-2">
                    <p class="text-sm text-gray-400">Total:</p>
                    <p class="font-bold text-white">{{ $summary['currency']['sign'] }} {{ $summary['summary']['total'] }}</p>
                </span>
                <!-- Place order button x-on:click="showModal = true" wire:click="navigateToOrders"-->
                @auth
                <div class="w-full">
                    <button wire:click="placeOrder" x-on:click="placeOrder" x-show="!isOrderPlaced" class="px-4 py-3 w-full text-white rounded-md bg-primaryblue focus:outline-none">Place Order</button>
                   
                </div>
                @else
                    <a href="/customers/orders/login" class="px-4 py-3 w-full text-white text-center rounded-md bg-primaryblue focus:outline-none">Place Order</a>

                    @endauth
                <p x-show="isOrderPlaced" class="mt-2 text-sm text-white">Order placed successfully!</p>
            </span>
            <!-- </div> -->
        </div>
        <!-- Table Pin Modal -->
        <!-- <span x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
            <span class="p-8 border rounded-lg maz-w-md  bg-blackgrey border-bordercol">
                <div class="flex flex-row justify-between">
                    <span></span>
                    <p class=" text-lg font-normal text-center text-primaryblue">Enter Your Table PINTo Place Order</p>
                    <button @click="showModal = false" class=" text-white  focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form wire:submit.prevent="placeOrder">
                    <input type="text" wire:model="tablePin" class="w-full px-4 mt-4 py-2 mb-4 text-white border rounded-md bg-primary border-bordercol" placeholder="Table PIN" required>
                </form>
                <div class="flex justify-center"> 
                    <button type="submit" x-on:click="isOrderPlaced = true" wire:click="placeOrder" class="px-8 py-2 text-white rounded-md bg-primaryblue">Submit</button>
                </div>
            </span>
        </span> -->
        <!-- Order Success Notification -->
        <span x-show="isOrderPlaced" class="fixed inset-0 z-50 flex items-center justify-center">
            <span class="flex flex-col items-center justify-center w-48 h-48 text-white bg-green-500 trasition duration-5000">
                <img src="/assets/images/image2.jpg" alt="Order success" class="w-16 h-16 mb-2">
                <span class="">Order Placed Successfully!</span>
            </span>
        </span>
        @else
        <div class="flex flex-col justify-center items-center h-auto mt-10">
            <img src="/assets/images/empty_cart.png" alt="Clock Icon" class="w-50 h-40 object-cover">
            <span class="text-white">Your Cart is Empty</span>
        </div>
        @endif
    </div>
</div>