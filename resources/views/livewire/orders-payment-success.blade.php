<div>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles

    </head>

    <body class="font-urbanist antialiased  bg-primary">
        <!-- Fixed Header -->

        <div class="fixed top-0 left-0 bg-primary z-50 w-full px-4 text-white p-1 sm:h-20 md:h-12  flex items-center justify-center">
            <div class="flex items-center  justify-center w-full">
                <!-- Your logo -->
                <H6 class="my-2">Orders</H6>
            </div>

        </div>


        <div class="flex mx-4 mt-8 px-4 rounded-lg border border-bordercol bg-blackgrey h-16 items-center">
            <img src="/assets/images/check-mark.svg" alt="Clock Icon" class="ml-1 object-cover w-6 h-6">
            <p class="ml-2  text-lg  font-bold text-greensucc"> Payment Successful! </p>

        </div>


        <div class=" px-2 mt-4 w-full ">
            <div class="container overflow-y-auto">
                <div class=" pb-20 whitespace-nowrap">
                    <!-- Vertical Scrollable Container -->
                    <div class="flex flex-col space-y-2 mb-2">
                        <livewire:menu-card layout="ratings" />
                        <livewire:menu-card layout="ratings" />
                        <livewire:menu-card layout="ratings" />
                        <livewire:menu-card layout="ratings" />
                        <livewire:menu-card layout="ratings" />
                        <livewire:menu-card layout="ratings" />
                    </div>
                </div>
            </div>
        </div>





        <div x-data="{ isScrolled: false, prevScrollPos: 0, isAtBottom: false }" x-init="
        window.addEventListener('scroll', () => {
            const currentScrollPos = window.pageYOffset;
            const maxScrollPos = document.documentElement.scrollHeight - window.innerHeight;
            isScrolled = currentScrollPos > prevScrollPos && currentScrollPos > 0;
            prevScrollPos = currentScrollPos;
            isAtBottom = currentScrollPos >= maxScrollPos;
        })
    " :class="{ 'translate-y-0': !isScrolled && !isAtBottom, 'translate-y-full': isScrolled || isAtBottom }" class="grid grid-cols-5 gap-1 bg-secondary justify-items-center py-10 mb-1 fixed bottom-0 w-full transition-transform duration-500">


            <div class="fixed right-0 w-full z-50  bottom-20 ng-red-600 sm:bottom-10 shadow-xl ">

                <div class="flex w-full items-center bg-blackgrey rounded-lg ">
                    <!-- Order details section -->
                    <div class="flex flex-col w-full p-4">

                        <div class="flex flex-col w-full justify-between mb-2">
                            <h3 class="text-white text-lg font-semibold mb-1">How was your experience?</h3>

                            <textarea id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-textfield rounded-lg border border-bordercol " placeholder="Write your thoughts here..."></textarea>

                        </div>


                    
                        <button class="bg-primaryblue text-white py-2 px-4 text-md font-semibold rounded-md">Submit</button>
                    </div>
                </div>


            </div>
        </div>


        @livewireScripts
    </body>

    </html>

</div>