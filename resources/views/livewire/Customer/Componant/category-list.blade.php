<div class="flex  space-x-4 ">
    @foreach($categories as $category)
    <button type="button" class="w-18 min-w-18" wire:click="onChanged({{ $category->id }})">

        <div class="w-18 h-18 p-3 border-gray-200 shadow-md min-w-18 min-h-18 rounded-2xl @if($this->isSelected($category->id)) bg-primaryblue bg-opacity-50 text-black @else bg-gr2 text-white @endif">
            <img class="object-fill w-full h-full saturate-150 shadow-inner rounded-2xl" src="{{ url('storage/' . $category->image) }}" alt="image description">
        </div>

        <figcaption class="mt-2 w-18 text-xs text-center font-semibold text-white line-clamp-1 dark:text-white">{{ $category->name }}</figcaption>

    </button>
    @endforeach



    
</div>

<script>
  window.onload = function() {
    var figcaptions = document.querySelectorAll('.auto-scroll');
    figcaptions.forEach(function(figcaption) {
      var textLength = figcaption.textContent.trim().length;
      if (textLength > 14) {
        var scrollWidth = figcaption.scrollWidth;
        var clientWidth = figcaption.clientWidth;
        if (scrollWidth > clientWidth) {
          var duration = scrollWidth * 20; // Adjust speed here
          figcaption.style.animation = 'scroll ' + duration + 'ms linear infinite';
        }
      }
    });
  };
</script>
