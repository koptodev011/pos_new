@if($type == 'mail')
<x-mail::message>
    # Hello

    You are receiving this email because you are requested E-bill.

    <span class="w-full flex flex-col space-y-2">
        <div x-data="{ isOpen: false }" class="bg-blackgrey border border-bordercol mt-8 mx-4 px-4 py-2 rounded-lg">
            <span class=" flex justify-between items-center    ">
                <span class="flex-col flex">

                    <p class="text-white text-md font-semibold">ORDER: {{ $orderData[0]['order']->order_no }} </p>
                    <span class=" w-full flex justify-between  space-x-2 flex-row">
                        <span class="flex items-center">
                            <img src="/assets/images/table.webp" alt="Table" class="w-3 h-3 ">
                            <p class="text-white font-semibold text-xs ml-1">: {{ $orderData[0]['order']->floor_table_id }}</p>
                        </span>
                        <span class="flex items-center">
                            <img src="/assets/images/man.webp" alt="Table" class="w-3 h-3 ">
                            <p class="text-white font-semibold text-xs ml-1">: {{ $orderData[0]['order']->diners }}</p>
                        </span>
                        <p class="text-white font-semibold text-xs">Qty: {{ $orderData[0]['order']->quantity }}</p>
                        <p class="text-white font-semibold text-xs">Item: {{ count($orderData[0]['order']->orderItems) }}</p>
                    </span>
                </span>
                <!-- Toggle button -->
                <div class="flex mt-3 flex-col">
                    <p class="ml-2 text-white text-xs">Date: {{date('d-m-Y', strtotime($orderData[0]['order']->created_at));}}</p>
                    <button @click="isOpen = !isOpen" class="flex items-end justify-right focus:outline-none my-2 text-white rounded-md ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" x-show="isOpen">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" x-show="!isOpen">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
            </span>
            <hr class="border-dashed px-4 my-1">
            <!-- Expandable content -->
            <div x-show="isOpen">
                <div class="flex flex-1 justify-between items-center">
                    
                    <h2 class="text-lg text-primaryblue font-normal text-center mb-4">Order Details</h2>
                    
                </div>
                <!-- Bill section -->
                <span class="flex flex-col">
                    <!-- header -->
                    <span class="flex mb-1 flex-col">
                        <span class="flex  justify-between items-center">
                            <p class="text-white text-start w-6/12 text-sm truncate-2-lines">Item</p>
                            <p class="text-white text-center w-2/12 text-sm">Qty.</p>
                            <p class="text-white text-center w-2/12 text-sm">Rate</p>
                            <p class="text-white text-end w-2/12 text-sm">Total</p>
                        </span>
                    </span>
                    <hr class="border-double px-4 my-2">
                    <!-- Bill item -->
                    @foreach($orderData[0]['order']->orderItems as $orderItem)
                    <span class="flex mb-3 flex-col">
                        <span class="flex  justify-between items-center">
                            <p class="text-white  w-6/12 text-sm truncate-2-lines">{{ $orderItem->orderable->name }}</p>
                            <p class="text-white text-center w-2/12  text-sm">{{ $orderItem->quantity }}</p>
                            <p class="text-white text-center w-2/12 text-sm"> {{ $orderItem->orderable->price }}</p>
                            <p class="text-white  text-end  w-2/12 text-sm"> {{ $orderItem->quantity * $orderItem->price }}</p>
                        </span>
                    </span>
                    @endforeach


                    <hr class="border-dashed px-4 my-2">
                    <!-- Total, discounts and Taxes -->
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Sub Total</p>
                        <p class="text-white font-bold text-sm"> {{ $orderData[0]['summary']['sub_total'] }}</p>
                    </span>
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Discounts / Coupons Applied</p>
                        <p class="text-white text-sm">- {{ $orderData[0]['summary']['discount']['value'] }}</p>
                    </span>
                    <!-- <span class="flex  justify-between items-center">
                        <span class="flex flex-row">
                            <p class="text-white text-sm">Loyalty Points Used </p>
                            <svg class="w-3 h-3 ml-2 mt-1 text-green-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                            </svg>
                        </span>
                        <p class="ms-1 text-sm  text-white mt-1 ">- 0</p>
                    </span> -->
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Taxes (5%)</p>
                        <p class="text-white text-sm"> {{ $orderData[0]['summary']['tax']['value'] }}</p>
                    </span>
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Tip For Waiter <span class="text-xs text-gray-400">(tax is not applicable)</span></p>
                        <p class="text-white text-sm"> 0</p>
                    </span>
                    <hr class="border-spacing-1 px-4 my-2"> <!-- Horizontal line -->
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Grand Total</p>
                        <p class="text-white text-xs">Paid Via Cash</p>
                        <p class="text-white font-bold text-sm"> {{ $orderData[0]['summary']['total'] }}</p>
                    </span>
                    <hr class="border-double px-4 my-2">
                    <h2 class="text-sm text-primaryblue font-normal text-center mb-2">Paid by</h2>
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Guest 1</p>
                        <p class="text-white text-xs">Paid Via Debit Card </p>
                        <p class="text-white text-sm"> 574</p>
                    </span>
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Guest 2</p>
                        <p class="text-white text-xs">Gpay</p>
                        <p class="text-white text-sm"> 574</p>
                    </span>
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Guest 3</p>
                        <p class="text-white text-xs">Cash</p>
                        <p class="text-white text-sm"> 582</p>
                    </span>
                    <hr class="border-double px-4 my-2">
                    <!-- <span class="flex flex-col justify-between items-center">
                        <p class="text-green-500 font-bold">Heyyyyy !!</p>
                        <span class="flex justify-between ">
                            <svg class="w-4 h-4  mt-1 text-green-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                            </svg>
                            <p class="text-green-500 text-center text-sm">You have earned 25 Loyalty Points on your order..! </p>
                            <svg class="w-4 h-4  mt-1 text-green-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                            </svg>
                        </span>
                    </span> -->
                </span>
            </div>
        </div>
    </span>

    Thanks,<br>
    {{ config('app.name') }}
   
</x-mail::message>
@else

<span class="w-full flex flex-col space-y-2">
        <div x-data="{ isOpen: false }" class="bg-blackgrey border border-bordercol mt-8 mx-4 px-4 py-2 rounded-lg">
            <span class=" flex justify-between items-center    ">
                <span class="flex-col flex">

                    <p class="text-white text-md font-semibold">ORDER: {{ $orderData[0]['order']->order_no }} </p>
                    <span class=" w-full flex justify-between  space-x-2 flex-row">
                        <span class="flex items-center">
                            <img src="/assets/images/table.webp" alt="Table" class="w-3 h-3 ">
                            <p class="text-white font-semibold text-xs ml-1">: {{ $orderData[0]['order']->floor_table_id }}</p>
                        </span>
                        <span class="flex items-center">
                            <img src="/assets/images/man.webp" alt="Table" class="w-3 h-3 ">
                            <p class="text-white font-semibold text-xs ml-1">: {{ $orderData[0]['order']->diners }}</p>
                        </span>
                        <p class="text-white font-semibold text-xs">Qty: {{ $orderData[0]['order']->quantity }}</p>
                        <p class="text-white font-semibold text-xs">Item: {{ count($orderData[0]['order']->orderItems) }}</p>
                    </span>
                </span>
                <!-- Toggle button -->
                <div class="flex mt-3 flex-col">
                    <p class="ml-2 text-white text-xs">Date: {{date('d-m-Y', strtotime($orderData[0]['order']->created_at));}}</p>
                   
                </div>
            </span>
            <hr class="border-dashed px-4 my-1">
            <!-- Expandable content -->
            <div x-show="isOpen">
                <div class="flex flex-1 justify-between items-center">
                    
                    <h2 class="text-lg text-primaryblue font-normal text-center mb-4">Order Details</h2>
                    
                </div>
                <!-- Bill section -->
                <span class="flex flex-col">
                    <!-- header -->
                    <span class="flex mb-1 flex-col">
                        <span class="flex  justify-between items-center">
                            <p class="text-white text-start w-6/12 text-sm truncate-2-lines">Item</p>
                            <p class="text-white text-center w-2/12 text-sm">Qty.</p>
                            <p class="text-white text-center w-2/12 text-sm">Rate</p>
                            <p class="text-white text-end w-2/12 text-sm">Total</p>
                        </span>
                    </span>
                    <hr class="border-double px-4 my-2">
                    <!-- Bill item -->
                    @foreach($orderData[0]['order']->orderItems as $orderItem)
                    <span class="flex mb-3 flex-col">
                        <span class="flex  justify-between items-center">
                            <p class="text-white  w-6/12 text-sm truncate-2-lines">{{ $orderItem->orderable->name }}</p>
                            <p class="text-white text-center w-2/12  text-sm">{{ $orderItem->quantity }}</p>
                            <p class="text-white text-center w-2/12 text-sm"> {{ $orderItem->orderable->price }}</p>
                            <p class="text-white  text-end  w-2/12 text-sm"> {{ $orderItem->quantity * $orderItem->price }}</p>
                        </span>
                    </span>
                    @endforeach


                    <hr class="border-dashed px-4 my-2">
                    <!-- Total, discounts and Taxes -->
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Sub Total</p>
                        <p class="text-white font-bold text-sm"> {{ $orderData[0]['summary']['sub_total'] }}</p>
                    </span>
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Discounts / Coupons Applied</p>
                        <p class="text-white text-sm">- {{ $orderData[0]['summary']['discount']['value'] }}</p>
                    </span>
                    <span class="flex  justify-between items-center">
                        <span class="flex flex-row">
                            <p class="text-white text-sm">Loyalty Points Used </p>
                            <svg class="w-3 h-3 ml-2 mt-1 text-green-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                            </svg>
                        </span>
                        <p class="ms-1 text-sm  text-white mt-1 ">- 0</p>
                    </span>
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Taxes (5%)</p>
                        <p class="text-white text-sm"> {{ $orderData[0]['summary']['tax']['value'] }}</p>
                    </span>
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Tip For Waiter <span class="text-xs text-gray-400">(tax is not applicable)</span></p>
                        <p class="text-white text-sm"> 0</p>
                    </span>
                    <hr class="border-spacing-1 px-4 my-2"> <!-- Horizontal line -->
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Grand Total</p>
                        <p class="text-white text-xs">Paid Via Cash</p>
                        <p class="text-white font-bold text-sm"> {{ $orderData[0]['summary']['total'] }}</p>
                    </span>
                    <hr class="border-double px-4 my-2">
                    <h2 class="text-sm text-primaryblue font-normal text-center mb-2">Paid by</h2>
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Guest 1</p>
                        <p class="text-white text-xs">Paid Via Debit Card </p>
                        <p class="text-white text-sm"> 574</p>
                    </span>
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Guest 2</p>
                        <p class="text-white text-xs">Gpay</p>
                        <p class="text-white text-sm"> 574</p>
                    </span>
                    <span class="flex  justify-between items-center">
                        <p class="text-white text-sm">Guest 3</p>
                        <p class="text-white text-xs">Cash</p>
                        <p class="text-white text-sm"> 582</p>
                    </span>
                    <hr class="border-double px-4 my-2">
                    <span class="flex flex-col justify-between items-center">
                        <p class="text-green-500 font-bold">Heyyyyy !!</p>
                        <span class="flex justify-between ">
                            <svg class="w-4 h-4  mt-1 text-green-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                            </svg>
                            <p class="text-green-500 text-center text-sm">You have earned 25 Loyalty Points on your order..! </p>
                            <svg class="w-4 h-4  mt-1 text-green-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                            </svg>
                        </span>
                    </span>
                </span>
            </div>
        </div>
    </span>
 

@endif