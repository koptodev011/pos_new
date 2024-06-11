<div class="mb-2  ">
    @if($quantity === 0)
    <div class="mb-1 flex justify-center">
        <button wire:click="increment" class=" py-1 w-full text-xs 
             text-primaryblue bg-primary border border-primaryblue
              font-bold rounded focus:outline-none focus:shadow-outline">Add</button>
    </div>
    @else
    <form class="max-w-xs mx-auto">
        <div class="relative flex items-center justify-center">
            <button wire:click="decrement" type="button" id="decrement-button" class="flex-shrink-0
                 bg-primary border-primaryblue  
                 inline-flex items-center justify-center border  rounded-md h-5 w-5
                  dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                <svg class="w-2.5 h-2.5 text-primaryblue dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                </svg>
            </button>
            <input type="text" id="counter-input" class="flex-shrink-0 text-white  border-0 bg-transparent text-sm font-normal focus:outline-none focus:ring-0 max-w-[2.5rem] text-center" placeholder="" wire:model="quantity" required />
            <button wire:click="increment" type="button" id="increment-button" class="flex-shrink-0 bg-primary
                border-primaryblue 
                  inline-flex items-center justify-center border  rounded-md h-5 w-5
                    focus:ring-2 focus:outline-none">
                <svg class="w-2.5 h-2.5 text-primaryblue " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                </svg>
            </button>
        </div>
    </form>
    @endif
</div>