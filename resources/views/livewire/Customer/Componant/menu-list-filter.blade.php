<div class="flex flex-col w-full px-4 space-y-2">
    @if($menus->isEmpty())
    <p class="text-white text-center">{{ __("No data available") }}</p>
    @else
    @foreach($menus as $menu)
    <livewire:menu-card key="{{$menu->id}}" :vertical="false" :menu="$menu" :currency="$currency" />
    @endforeach
    @endif
</div>