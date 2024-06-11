<div>
    @if ($showSplashScreen)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-75 z-50">
        <div class="absolute inset-0 flex items-center justify-center m-4">
            <div class="bg-gradient-to-b from-blue-400 to-sky-500 rounded-lg shadow-xl overflow-hidden">
                <!-- Advertisement Image -->
                <div class=" ">
                    <img src="/assets/images/splash.webp" alt="Advertisement" class="object-fill ">
                </div>
                <!-- Close Button -->
                <button wire:click="hideSplashScreen" class="absolute top-4 right-1 bg-primaryblue text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-primaryblue-dark focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
