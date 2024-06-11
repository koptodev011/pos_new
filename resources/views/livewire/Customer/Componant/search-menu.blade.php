<div x-data="{ isScrolled: false,isSearching: {{ empty($searchResults)?'false':'true' }},isFocus: false, placeholders: ['Search Biryani', 'Search Pulav', 'Search Ice-cream'], currentPlaceholder: 0 }" x-init="window.addEventListener('scroll', () => { isScrolled = window.pageYOffset > 0; });
             setInterval(() => { currentPlaceholder = (currentPlaceholder + 1) % placeholders.length }, 2000)" :class="{ 'h-0': isScrolled, 'h-16': !isScrolled }" class="relative w-full transition-height duration-1000">

    <!-- Scrolling Placeholder Text -->
    <div class="placeholder-container text-discription" :class="{ 'hidden': isScrolled || isSearching || isFocus}">
        <template x-for="(placeholder, index) in placeholders" :key="index">
            <span class="placeholder" x-show="currentPlaceholder === index" x-text="placeholder" x-bind:class="{ 'slide-from-bottom': currentPlaceholder === index }" class="text-gray-400"></span>
        </template>
    </div>


    <form class="flex items-center max-w-lg h-full mx-auto">
        <!-- Input field -->
        <div class="relative w-full" @click.outside="isFocus = false;">

        <input @click="isFocus = true;" x-ref="voice-search" wire:keydown="searchMenu" wire:model.live="search" type="text" id="voiceSearch" x-show="!isScrolled" class="text-white bg-secondary text-sm rounded-lg block w-full ps-4 p-2.5 focus:border-transparent outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white sm:w-auto md:w-full lg:w-auto xl:w-full transition-placeholder duration-300" required />
            <div class="absolute inset-y-0 end-0 flex items-center pr-3 pointer-events-none">

                <!-- First image icon -->
                <div class="w-8 h-4  mr-3 border-r border-gray-600">
                    <img src="/assets/images/Search.png" class="w-4 h-4 " alt="First Image" />
                </div>
                <!-- Second image icon -->
                <button><img src="/assets/images/Mike.png" class="w-5 h-5 " alt="Second Image" /></button>
            </div>
        </div>
    </form>
    @if($searchResults)
    @foreach($searchResults as $menu)
    <div class="container overflow-y-auto">
        <div class="pb-2">
            <!-- Vertical Scrollable Container -->
            <div class="flex flex-col space-y-2">
            <livewire:menu-card :key="$menu->id" :menu="$menu" :currency="$currency" />
            </div>
        </div>
    </div>
    @endforeach
    @endif


</div>
