<div>
    
    <div class="flex items-center justify-between w-full px-2 py-2">
        <div class="flex items-center space-x-2">
            <h2 class="font-bold text-white text-md">Ready To Serve</h2> 
        </div>
    </div>
   <div class="flex flex-1 px-3 ">
        @foreach($orderData as $order)
        
            <div class="flex rounded-2xl flex-1 bg-[#31363E] flex-col col-md-4 max-w-[150px] items-center mr-4">   
                <div class="items-start w-[150px] rounded-t px-2 py-2 text-white">
                    Order No: {{ $order->order_no }}
                </div>
                <div class="flex flex-col items-start w-full px-2 py-2 space-y-1 text-white">
                    <span class="text-[15px]">Table No: {{ $order->floor_table_id }}</span>
                    @foreach($order->orderItems as $orderItem)
                        <div class="flex flex-row items-center justify-between w-full">
                            <span class="text-[12px]"> {{ $orderItem->orderable->name }}</span>
                            <span class="text-[12px]"> {{ $orderItem->quantity }}</span>
                        </div>
                    @endforeach
                    <div class="w-full text-center">
                        <select wire:change="changeStatus($event.target.value, {{ $order->id }})" class="px-4 w-full text-[12px] py-1 border rounded border-primaryblue bg-primary text-primaryblue">
                            @foreach(App\Enums\OrderStatus::hostvalues() as $key => $value)
                                <option value="{{ $value }}" {{ $order->status->value === $value ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
           
        @endforeach
    </div>

</div>
