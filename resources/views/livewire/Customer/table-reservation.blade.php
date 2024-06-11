<div class="font-sans antialiased bg-primary">
  
    <span class="my-2">
        <h6 class="font-semibold text-center text-white">Table Reservation</h6>
    </span>
    <span class="flex justify-between px-2 mx-4 border border-bordercol bg-blackgrey rounded-md items-center mt-4">
        <p class="text-white text-sm font-semibold">Table#: 12</p>
        <p class="items-end justify-right text-sm my-1 border p-1 text-green-600 rounded-md">Booked</p>

        <span class="flex my-2 flex-col">
            <p class="text-white text-sm">Date: 26-04-2024</p>
            <p class="text-white text-sm">04:00 PM - 5:00 PM</p>
        </span>
    </span>
    <div class="fixed z-10 mb-6 shadow-xl right-5 bottom-16 sm:bottom-10 sm:right-auto sm:left-5 md:right-5 md:left-auto">
        <button wire:click="$toggle('showModal')" class="w-auto rounded-lg shadow-md h-10 transform-gpu hover:scale-105 flex p-2 items-center justify-center bg-backgroundblue text-sm font-bold text-white transition-colors duration-300" >Book New Table</button>
    </div>

    <!-- Modal content -->
    @if($showModal)
    <div wire:ignore.self class="fixed inset-0 z-50 overflow-auto flex items-center justify-center">
        <span class="fixed inset-0 bg-black opacity-25 z-40"></span>
        <span class="bg-blackgrey rounded-lg p-8 relative z-50">
            <h6 class="font-semibold text-center absolute top-4 left-0 right-0 w-full  text-white">Table Reservation</h6>
            <div class="flex justify-end absolute top-0  right-0 p-2">
                <button wire:click="$toggle('showModal')" type="button" class="mt-2 text-md focus:outline-none font-semibold rounded-lg z-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 " fill="none" viewBox="0 0 24 24" stroke="white">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <input type="text" wire:model="name" class=" text-white bg-textfield border mt-4 border-bordercol text-gray-900 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Name" required value="{{ auth()->user()->name }}" readonly/>
            <input type="email" wire:model="email" class=" text-white bg-textfield border mt-2 border-bordercol text-gray-900 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Email" required value="{{ auth()->user()->email }}" readonly/>
            <input type="tel" wire:model="phoneNumber" pattern="[0-9]*" inputmode="numeric" class=" text-white bg-textfield mt-2 border border-bordercol text-gray-900 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Phone Number" required value="{{ auth()->user()->phone }}" readonly/>
            <div class="mt-2">@livewire('select-date')</div>
           
            <div class="mt-2">@livewire('select-time')</div>
            <button wire:click="$toggle('showModal')" class="bg-primaryblue text-white py-2 px-4 mt-4 w-full text-md font-semibold rounded-md">Book Table</button>
        </span>
    </div>
    @endif
    
</div>