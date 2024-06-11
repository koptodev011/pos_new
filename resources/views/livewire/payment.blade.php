<div class=" antialiased font-urbanist overflow-y-auto h-screen ">
    <div class=" fixed top-[60] bg-primary left-0 font-semibold z-20 w-full px-4 sm:py-4 md:py-2 flex items-center justify-between text-white">
        <button onclick="window.history.back()" class="flex items-center justify-left text-white mr-2 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <span class="justify-center mr-8 items-center text-white">Payment</span>
        <span></span>
    </div>
    @if($successMessage)
    <div x-data="{ visible: true }" x-init="setTimeout(() => { visible = false; }, 3000); setTimeout(() => { $wire.call('clearSuccessMessage'); }, 3001);" x-show="visible" class="fixed top-0 right-0 z-50 p-2 mt-24 text-center text-white bg-green-500 rounded mr-14">
        {{ $successMessage }}
    </div>
    @endif

    <div class="flex px-4 mt-8  justify-between">
        <!-- Single Payment Button -->
        <button wire:click="$set('selectedTab', 'singlePayment')" class="w-1/2 inline-flex flex-col items-center justify-center px-5 rounded-md group
               {{ $selectedTab === 'singlePayment' ? 'bg-primaryblue text-black' : 'bg-transparent text-primaryblue' }} border-none focus:outline-none">
            <span class="text-md py-2">Pay Now </span>
        </button>
        <!-- Split Payment Button -->
        <button wire:click="$set('selectedTab', 'splitPayment')" class="w-1/2 inline-flex flex-col items-center justify-center px-5 rounded-md group animated-button slide-from-bottom
               {{ $selectedTab === 'splitPayment' ? 'bg-primaryblue text-black' : 'bg-transparent text-primaryblue' }}
               border-none focus:outline-none">
            <span class="text-md py-2">{{ __("Split Payment") }}</span>
        </button>
    </div>
    <span class="flex items-center justify-between mt-2 px-4 w-full">
        <p class="order-first text-discription text-sm">{{ __("Order Total") }}</p>
        <span class="flex items-center">
            <img src="/assets/images/info.svg" alt="Non-Veg" class="w-5 h-5 mr-2 ">
            <p class="mr-2 text-lg font-bold text-white">{{ $currency->symbol }} {{$orderData['summary']['total'] + $orderData['summary']['tip']['value']}}</p>
        </span>
    </span>
    @if($selectedTab === 'singlePayment' || 'splitPayment')
    <div class="  px-4 right-0 w-full z-50 mb-4 bottom-36 sm:bottom-10 shadow-xl ">
        <span class="  right-0 w-full z-50 mb-0 bottom-16 ng-red-600 sm:bottom-10 shadow-xl ">
            <span class="flex w-full items-center bg-blackgrey mb-4 mt-4  rounded-lg px-4 ">
                <!-- Order details section -->
                <span class="flex flex-col w-full">
                    <span class="flex flex-row justify-between ">
                        <div class=" w-full">
                            <!-- List items -->
                            <span class="flex items-center   py-4  w-full justify-between">
                                <span class="flex items-center space-x-4">
                                    <img src="/assets/images/Profile.png" alt="Image" class="w-5 h-5 rounded-lg">
                                    @if(auth()->user())
                                    <span class="text-md text-white">{{ucfirst(auth()->user()->name)}}</span>
                                    @else
                                    <span class="text-md text-white">Guest</span>

                                    @endauth
                                </span>
                                <!-- <span class="items-end flex">
                                    <input type="text" class="text-md focus:outline-none bg-transparent border-none w-auto text-end focus:border-none text-primaryblue px-1 py-1" value="$50">
                                    <img src="/assets/images/edit.svg" alt="edit" class="w-6 h-8 py-2">
                                </span> -->
                            </span>
                            <!-- Add and remove buttons -->
                            <!-- <div class="flex   py-4 justify-between">
                                    <a href="#" class="text-primaryblue font-bold text-sm py-2 ">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-4 inline fill-current text-primaryblue" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zM9 5a1 1 0 012 0v3h3a1 1 0 010 2h-3v3a1 1 0 01-2 0v-3H6a1 1 0 010-2h3V5z" clip-rule="evenodd" />
                                        </svg>
                                        Add a Guest
                                    </a>

                                    <a href="#" class="text-discription font-normal font-sans text-xs py-2  underline">
                                        Remove a guest
                                    </a>
                                </div> -->
                        </div>
                    </span>
                </span>
            </span>
        </span>
    </div>
    @endif

    <div class=" right-0 w-full px-4 z-50 mb-0 sm:bottom-10 shadow-xl ">
        <span class="flex w-full items-center bg-blackgrey rounded-lg p-2 ">
            <!-- Order details section -->
            <div class="flex flex-col w-full">
                <p class=" text-xs mb-4 text-discription">Tip for Waiter</p>
                <span class="flex flex-row  justify-between mb-2">
                    <span wire:click="selectOption(10)" class="inline-flex items-center px-3 py-1 me-2 text-sm font-medium {{ $selectedOption === 10 ? 'text-white' : 'text-primaryblue' }} {{ $selectedOption !== 10 ? 'bg-primary' : 'bg-primaryblue' }} rounded-md cursor-pointer">{{ $currency->symbol }} 10</span>
                    <span wire:click="selectOption(20)" class="inline-flex items-center px-3 py-1 me-2 text-sm font-medium {{ $selectedOption === 20 ? 'text-white' : 'text-primaryblue' }} {{ $selectedOption !== 20 ? 'bg-primary' : 'bg-primaryblue' }} rounded-md cursor-pointer">{{ $currency->symbol }} 20</span>
                    <span wire:click="selectOption(30)" class="inline-flex items-center px-3 py-1 me-2 text-sm font-medium {{ $selectedOption === 30 ? 'text-white' : 'text-primaryblue' }} {{ $selectedOption !== 30 ? 'bg-primary' : 'bg-primaryblue' }} rounded-md cursor-pointer">{{ $currency->symbol }} 30</span>
                    <span wire:click="selectOption(40)" class="inline-flex items-center px-3 py-1 me-2 text-sm font-medium {{ $selectedOption === 40 ? 'text-white' : 'text-primaryblue' }} {{ $selectedOption !== 40 ? 'bg-primary' : 'bg-primaryblue' }} rounded-md cursor-pointer">{{ $currency->symbol }} 40</span>
                    <span wire:click="toggleModal" class="inline-flex items-center px-3 py-1 me-2 text-sm font-medium  rounded-md cursor-pointer {{ $customTip > 0 ? 'text-white' : 'text-primaryblue' }} {{ $customTip == 0 ? 'bg-primary' : 'bg-primaryblue' }} rounded-md cursor-pointer">Custom</span>
                    <!-- Modal -->
                    @if($showModal)
                    <span class="fixed bottom-16 inset-0 z-30 overflow-auto flex items-center justify-center">
                        <span class="fixed inset-0 bg-black opacity-50 z-40"></span> <!-- Background overlay -->
                        <span class="bg-blackgrey rounded-lg p-8 relative z-50">
                            <!-- Modal content -->
                            <div class="flex justify-end absolute top-0 right-0 p-2">
                                <button wire:click="showModal = false" type="button" class="mt-2 text-md  focus:outline-none font-semibold rounded-lg z-50">
                                    <!-- Cancel image button (replace with your image) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 " fill="none" viewBox="0 0 24 24" stroke="white">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <h2 class="text-lg text-center text-white font-semibold mb-4">Enter Custom Amount</h2>
                            <input wire:model="customTip" type="number" class="w-full text-center border-none text-white bg-transparent  focus:outline-none rounded-xl px-3 py-2 mb-4" placeholder="{{ $currency->symbol }} 0">
                            <button wire:click="toggleModal" class="bg-primaryblue text-white py-2 px-4 w-full rounded-md">Submit</button>
                        </span>
                    </span>
                    @endif
                </span>
            </div>
        </span>
    </div>

    <span class="fle flex-col p-2 fixed z-20 left-0 right-0 mx-auto w-full shadow-xl bottom-[70px] max-w-md items-center rounded-t-3xl bg-blackgrey">
        <div class="flex w-full items-center flex-col p-2 rounded-t-3xl">
            <!-- Order details section -->
            <div x-data="{ isOpen: false }" class="mb-4 w-full">
                <span class=" flex justify-between items-center">
                    <span class="flex flex-row w-full justify-between">
                        <span class="flex flex-col justify-between ">
                            <p class="text-white text-md font-semibold">Order Details</p>
                            <p class="text-discription text-xs">Taxes and Fees are included.</p>
                        </span>
                        <div class="flex  items-center">
                            <span class="text-white mr-2 font-bold">{{ $currency->symbol }} {{ $orderData['summary']['total'] + $orderData['summary']['tip']['value']}}</span>
                            <button @click="isOpen = !isOpen" class="w-4 items-end justify-right focus:outline-none my-2 mr-2 text-white rounded-md ml-auto">
                                <svg x-show="isOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                                <svg x-show="!isOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                    </span>
                </span>


                <!-- Expandable content -->
                <div x-show="isOpen" class=" px-2 border border-white rounded-lg">
                    <!-- Content goes here -->
                    <span class="flex flex-1 justify-center items-center text-lg text-primaryblue font-normal text-center mb-4">Order Details</span>
                    <!-- Bill section -->
                    <div class="flex flex-col">
                        <!-- header -->

                        <span class="flex  justify-between items-center">
                            <p class="text-white text-start w-6/12 text-sm truncate-2-lines">Item</p>
                            <p class="text-white text-center w-2/12 text-sm">Qty.</p>
                            <p class="text-white text-center w-2/12 text-sm">Rate</p>
                            <p class="text-white text-end w-2/12 text-sm">Total</p>
                        </span>
                        <hr class="border-double px-4 my-2"> <!-- Horizontal line -->
                        <!-- Bill item -->
                        @foreach($orderData['orderData']->orderItems as $order)
                        <div class="flex  justify-between items-center">
                            <p class="text-white  w-6/12 text-sm truncate-2-lines">{{ $order->orderable->name }}</p>
                            <p class="text-white text-center w-2/12  text-sm">{{ $order->quantity }}</p>
                            <p class="text-white text-center w-2/12 text-sm">{{ $currency->symbol }} {{$order->price}}</p>
                            <p class="text-white  text-end  w-2/12 text-sm">{{ $currency->symbol }} {{ $order->quantity * $order->price }}  </p>
                        </div>
                        @endforeach
                        <hr class="border-dashed px-4 my-2"> <!-- Horizontal line -->
                        <!-- Total, discounts and Taxes -->
                        <span class="flex  justify-between items-center">
                            <p class="text-white text-sm">Sub Total</p>
                            <p class="text-white font-bold text-sm">{{ $currency->symbol }} {{ $orderData['summary']['sub_total']}}</p>
                        </span>
                        <span class="flex  justify-between items-center">
                            <p class="text-white text-sm">Discounts / Coupons Applied</p>
                            <p class="text-white text-sm">-{{ $currency->symbol }} {{ $orderData['summary']['discount']['value']}}</p>
                        </span>
                        <!-- <span class="flex  justify-between items-center">
                            <span class="flex flex-row">
                                <p class="text-white text-sm">Loyalty Points Used </p>
                                <svg class="w-3 h-3 ml-2 mt-1 text-green-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                </svg>
                            </span>
                            <p class="ms-1 text-sm  text-white mt-1 ">-{{ $currency->symbol }}0</p>
                        </span> -->
                        <span class="flex  justify-between items-center">
                            <p class="text-white text-sm">Taxes (5%)</p>
                            <p class="text-white text-sm">{{ $currency->symbol }} {{ $orderData['summary']['tax']['value']}}</p>
                        </span>
                        <span class="flex  justify-between items-center">
                            <p class="text-white text-sm">Tip For Waiter <span class="text-xs text-gray-400">(tax is not applicable)</span></p>
                            <p class="text-white text-sm">{{ $currency->symbol }} {{ $orderData['summary']['tip']['value']}}</p>
                        </span>
                        <hr class="border-spacing-1 px-4 my-2"> <!-- Horizontal line -->
                        <span class="flex mb-2 justify-between items-center">
                            <p class="text-white text-sm">Grand Total</p>
                            <p class="text-white font-bold text-sm">{{ $currency->symbol }} {{ $orderData['summary']['total'] + $orderData['summary']['tip']['value'] }}</p>
                        </span>
                    </div>
                </div>
            </div>
            @if($selectedTab === 'singlePayment')
            <span class="flex flex-row w-full justify-between mb-2">
                @if(!$couponApplied)
               
                <label for="useLoyaltyPoints" class=" text-discription text-md">Apply Coupon</label>
                <span class="flex items-center mr-1">
                    <input type="text" wire:model="couponCode" class="w-full border bg-primary text-white rounded-md w-24 text-center px-3 py-0.5 focus:outline-none">
                </span>
                 <button wire:click="applyCoupon" class="flex items-center justify-center bg-primaryblue text-white py-0.5 px-4 rounded-md focus:outline-none">
                    Apply
                </button>
                @else
                    @if($appliedCouponStatus)
                        <div class="flex items-center flex-row justify-between">
                            <span class="text-description text-white text-md mr-2">Coupon Applied successfully: {{ $appliedCoupon }}</span>
                            <span class="flex items-center mr-1">
                            <input type="text" wire:model="couponCode" class="w-full border bg-primary text-white rounded-md w-24 text-center px-3 py-0.5 focus:outline-none" value="{{ $this->couponCode }}" readonly>
                        </span>
                            <button wire:click="removeCoupon" class="flex items-center justify-center bg-red-500 text-white py-0.5 px-4 rounded-md focus:outline-none">Remove</button>
                        </div>
                    @else
                        <label for="useLoyaltyPoints" class=" text-discription text-md">Apply Coupon</label>
                        <span class="flex items-center mr-1">
                            <input type="text" wire:model="couponCode" class="w-full border bg-primary text-white rounded-md w-24 text-center px-3 py-0.5 focus:outline-none">
                        </span>
                        <button wire:click="applyCoupon" class="flex items-center justify-center bg-primaryblue text-white py-0.5 px-4 rounded-md focus:outline-none">
                            Apply
                        </button>
                    @endif
                @endif
            </span>
            <!-- <span class="flex items-center w-full justify-between  mb-2">
                <label for="useLoyaltyPoints" class=" text-discription text-md">Use your loyalty Points</label>
                <input type="text" class="text-md focus:outline-none bg-transparent border-none w-16 focus:border-none text-primaryblue px-2 py-1" value="$50">
            </span> -->
            @endif
            <button wire:click="payment" class="bg-primaryblue w-full text-white py-3 px-3 rounded-md">Pay Now</button>
        </div>
    </span>
   
</div>