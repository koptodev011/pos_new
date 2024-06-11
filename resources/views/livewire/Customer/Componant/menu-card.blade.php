<div class="flex items-start rounded-2xl bg-[#31363E] @if($vertical) flex-col max-w-[150px] @else flex-row w-full h-[130px] max-h-[130px] @endif">
    @if($successMessage)
    <div x-data="{ visible: true }" x-init="setTimeout(() => { visible = false; }, 3000); setTimeout(() => { $wire.call('clearSuccessMessage'); }, 3001);" x-show="visible" class="fixed top-0 right-0 z-50 p-2 mt-24 text-center text-white bg-green-500 rounded mr-14">
        {{ $successMessage }}
    </div>
    @endif
    <div class="relative @if($vertical) w-[150px] h-[80px] rounded-t @else w-[175px] h-full rounded-l @endif">
        <img src="@if($menu->image != null) {{$menu->image}} @else /assets/images/image1.jpg @endif" class="object-cover w-full h-full overflow-hidden @if($vertical) rounded-t @else rounded-l-2xl @endif" />
        @auth
            @if($this->isFavorite($menu->id))
            <button wire:click="addFavorite({{$menu->id}})" type="button" class="absolute w-5 h-5 text-[#FB5607] top-1 right-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                    <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                </svg>
            </button>
            @else
            <button wire:click="addFavorite({{$menu->id}})" type="button" class="absolute w-5 h-5 text-white top-1 right-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-full h-full">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
            </button>
            @endif
        @else
        <a href="/customers/orders/login" class="absolute w-5 h-5 text-white top-1 right-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
            </svg>
            </a>
        @endauth
    </div>


    @if($vertical)
    <div class="flex flex-col items-start w-full px-2 py-2 space-y-1 text-white">
        <span class="text-[14px] ">{{ $menu->name }}</span>


        <span class="text-[11px] text-[#8D99AE] relative" x-data="{ expanded: false }" x-init="$watch('expanded', value => { if (value) { setTimeout(() => { $refs.description.classList.remove('line-clamp-1') }, 0); } })" @click.away="expanded = false" class="font-sans text-xs font-normal leading-tight cursor-pointer text-wrap" :class="{ 'line-clamp-2': !expanded }" x-ref="description" @click="expanded = !expanded">

            {!! $menu->description !!}

            <button class="text-primaryblue z-50  bg-[#31363E]  font-sans text-xs cursor-pointer absolute pl-2 bottom-0 right-0 " x-show="!expanded && $refs.description.textContent.length > 40">
                Read More
            </button>

            <button class="text-primaryblue font-sans bg-[#31363E]  text-xs cursor-pointer absolute bottom-0 pl-2 right-0 " x-show="expanded">
                Show Less
            </button>
        </span>
        <div class="flex flex-row items-center justify-between w-full">
            <span class="text-[12px]">{{ $currency->symbol }}{{ $menu->applied_price }}</span>

        </div>
        <div class="w-full text-center">
           
            @if($this->fetchMenuQuantity($menu->id) > 0)
            <div class="flex flex-row items-center space-x-1">
                <button type="button" wire:click="onCartSub({{$menu->id}})" class="px-4 text-[12px] py-1 border rounded border-primaryblue bg-primary text-primaryblue">
                    -
                </button>
                <span class="px-4 text-[12px] py-1 border rounded border-primaryblue bg-primary text-primaryblue">
                    {{ $this->fetchMenuQuantity($menu->id) }}
                </span>
                <button type="button" wire:click="onCartAdd({{$menu->id}})" class="px-4 text-[12px] py-1 border rounded border-primaryblue bg-primary text-primaryblue">
                    +
                </button>
            </div>

            @else
            <button type="button" wire:click="onCartAdd({{$menu->id}})" class="px-4 w-full text-[12px] py-1 border rounded border-primaryblue bg-primary text-primaryblue">
                Add
            </button>
            @endif
        </div>
    </div>
    @else
   
    <div class="flex flex-col items-start w-full py-2 pl-4 space-y-1 text-white">
        <span class="text-[14px] font-bold">{{ $menu->name }}</span>
        <span class="text-[13px] text-[#8D99AE] line-clamp-2 mr-2">{!! $menu->description !!}</span>
        <div class="flex items-start justify-between w-full pr-4">
            <span class="text-[15px]">{{ $currency->symbol }}{{ $menu->applied_price }}</span>
          
            @if($status == 'Preparing')
            <span class="flex items-center space-x-2 text-[#FDB91F]">
                <img class="h-[16px] w-[16px] mr-1" src="/assets/images/clock.png" alt="">
                {{ $status }}
            </span>
            @elseif($status == 'Ready')
            <span class="flex items-center space-x-2 text-[#25D375]">
                <img class="h-[16px] w-[16px] mr-1" src="/assets/images/check-circle.png" alt="">
                {{ $status }}
            </span>
            @endif
            @if($this->showAddButton)
            @if($this->fetchMenuQuantity($menu->id) > 0)
            <div class="flex flex-row items-center space-x-1">
                <button type="button" wire:click="onCartSub({{$menu->id}})" class="px-4 text-[12px] py-1 border rounded border-primaryblue bg-primary text-primaryblue">
                    -
                </button>
                <span class="px-4 text-[12px] py-1 border rounded border-primaryblue bg-primary text-primaryblue">
                    {{ $this->fetchMenuQuantity($menu->id) }}
                </span>
                <button type="button" wire:click="onCartAdd({{$menu->id}})" class="px-4 text-[12px] py-1 border rounded border-primaryblue bg-primary text-primaryblue">
                    +
                </button>
            </div>
            @else
            <button type="button" wire:click="onCartAdd({{$menu->id}})" class="px-4 w-20 text-[12px] py-1 border rounded border-primaryblue bg-primary text-primaryblue ml-auto">
                Add
            </button>
            @endif
            @endif
        </div>

    </div>
    @endif


</div>