<a href="/customers/orders/search" wire:navigate x-data="{ isScrolled: false, placeholders: ['Search Biryani', 'Search Pulav', 'Search Ice-cream'], currentPlaceholder: 0 }" x-init="window.addEventListener('scroll', () => { isScrolled = window.pageYOffset > 0; });
             setInterval(() => { currentPlaceholder = (currentPlaceholder + 1) % placeholders.length }, 2000)" :class="{ 'h-0': isScrolled, 'h-[44px]': !isScrolled }" class="flex flex-row space-x-2 w-full p-2.5 rounded-lg bg-secondary">

    <div class="flex flex-row items-center flex-1 h-full space-x-2">
        
        <div class="flex-1 duration-1000 text-discription transition-height" :class="{'hidden': isScrolled}">
            <template x-for="(placeholder, index) in placeholders" :key="index">
                <span class="placeholder" x-show="currentPlaceholder === index" x-text="placeholder" x-bind:class="{ 'slide-from-bottom': currentPlaceholder === index }" class="text-gray-400"></span>
            </template>
        </div>
        <span class="text-discription">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </span>
    </div>
    <span class="h-full w-[1px] bg-discription"></span>
    <button class="text-discription">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z" />
        </svg>
    </button>
</a>