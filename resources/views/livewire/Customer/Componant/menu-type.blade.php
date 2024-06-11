<div class="overflow-x-auto whitespace-nowrap">
    @foreach($menuTypes as $menuType)
    <button type="button" wire:click="toggleSelected('{{ $menuType->value }}')" class="inline-flex shadow-md items-center px-4 py-2 font-sans me-2 mb-2 text-sm font-medium rounded-full
        @if($this->isSelected($menuType->value)) bg-primaryblue text-black @else bg-blackgrey text-white @endif">
            <img src="{{ $menuType->image() }}" class="w-4 h-4 mr-1 " alt="Image">
        {{ $menuType->value }}
    </button>
    @endforeach
</div>