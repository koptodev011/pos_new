<div>
    <div class=" mx-2 overflow-x-auto whitespace-nowrap flex">
        @foreach($selectedStates as $name => $isSelected)
        <span wire:click="toggleSelected('{{ $name }}')" id="badge-dismiss-{{ $name }}" class="flex border border-bordercol flex-1 flex-col bg-blackgrey shadow-md items-center justify-center px-4 py-2 
    font-sans mx-1 mb-2 text-sm font-medium rounded-md
        {{ $isSelected ? 'border-primaryblue text-black' : 'bg-blackgrey text-white' }}  max-w-1/3">
            <img src="{{ $imageUrls[$name] }}" class="w-6 h-6    align-middle" alt="Image">
            <span class="text-wrap mt-2 mb-1 text-xs align-middle text-center line-clamp-2">{{ $name }}</span>
        </span>
        @endforeach
    </div>

</div>