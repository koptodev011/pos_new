<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-primary">

    <div x-data="{ showProfile: false }" class="flex flex-col w-full h-screen max-h-screen min-h-screen">
        <span class="flex flex-row items-center justify-between w-full px-4 py-4">
            <span></span>
            <img src="/assets/images/Logo1.webp" class="h-12 sm:h-5 md:h-8" alt="Logo">
            <a href="/customers/notifications" wire:navigate class="w-6 h-6 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-full h-full">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
            </a>
        </span>
        <!-- Content area with padding to avoid overlapping -->
        <div class="relative flex-1 w-full overflow-y-auto">
            {{ $slot }}
            <div x-show="showProfile" class="fixed inset-0 flex items-end justify-center w-full h-full px-4 py-6 sm:p-0 z-50">
                <!-- Modal Content -->
                <div class="fixed w-full z-50 h-auto transition-transform max-w-md  duration-500 ease-in-out transform bg-gray-400 rounded-t-3xl bottom-2 slide-from-bottom">
                    <!-- Modal Content -->
                    @auth 
                    <livewire:profile />
                    @else
                    <span class="flex flex-col items-center justify-center h-auto py-4 rounded-t-3xl bg-blackgrey">
                        <img src="/assets/images/prof.svg" class="mb-4 rounded-full w-22 h-22" alt="Image">
                        <span x-data="{ selected: 'button1' }" class="flex flex-col justify-between w-3/5 mt-2">
                            <a href="/customers/orders/login" wire:navigate class="w-full text-center px-2 py-3 font-semibold text-black rounded-lg bg-primaryblue text-md font-urbanist px-">Login</a>
                            <a href="/customers/orders/signup" wire:navigate class="w-full text-center  px-2 py-3 mt-2 font-semibold bg-transparent rounded-lg text-primaryblue text-md font-urbanist">Sign Up</a>
                        </span>
                    </span>
                    <!-- <div x-data="{ selected: 'button1' }" class="flex justify-center bg-primary">
                        <button x-on:click="selected = 'button1'" :class="{ 'bg-bordercol text-primaryblue': selected === 'button1', 'bg-transparent text-primaryblue': selected !== 'button1' }" class="w-3/5 py-3 my-8 font-semibold rounded-lg text-md font-urbanist">
                            Continue as Guest
                        </button>
                    </div> -->
                    <span class="flex items-center px-4 justify-between py-4 bg-primary">
                        <span class="flex items-center">
                            <img src="/assets/images/Language.svg" alt="" class="w-8 h-8 mr-4 ">
                            <p class="mr-2 font-semibold text-white text-md">Language</p>
                        </span>
                        <span class="flex items-center">
                            <p class="text-sm font-normal text-discription">English</p>
                            <a href="#" class="inline-block px-2 py-1 rounded-md">
                                <img src="/assets/images/Arrow.png" alt="Your Image" class="w-5 h-5">
                            </a>
                        </span>
                    </span>
                    @endauth
                    <!-- Dismiss Button -->
                    <div class="flex justify-center bg-primary">
                        <button @click="showProfile = false" type="button" class="z-50 mt-4 font-semibold rounded-lg text-md font-urbanist">
                            <img src="/assets/images/cancel.svg" class="mb-4 rounded-full w-22 h-22" alt="Close">
                        </button>
                    </div>
                </div>
                <!-- Modal Backdground -->
                <div class="fixed inset-0 z-40 bg-black opacity-25"></div>
            </div>
        </div>
        <!-- Fixed Navigationnbar -->
        <div class="flex flex-row items-center w-full px-4 py-4 space-x-1 justify-evenly bg-secondary">

            <a href="/customers/orders/home" wire:navigate class="flex flex-col items-center space-y-1 @if(Route::is('customers.orders.home') || Route::is('customers.orders.tables')) text-primaryblue @else text-gray-500 @endif">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path d="M13.832 3.71113H12.8555V1.36684C12.8555 0.929453 12.6439 0.515312 12.2895 0.259023C11.935 0.0027338 11.4754 -0.0685553 11.06 0.068476L1.48969 3.22496C0.928086 3.41023 0.550781 3.93203 0.550781 4.52336V15.4427V18.6328C0.550781 19.3867 1.1641 20 1.91797 20H13.832C14.5859 20 15.1992 19.3867 15.1992 18.6328V5.07832C15.1992 4.32449 14.5859 3.71113 13.832 3.71113ZM14.0273 18.6328C14.0273 18.7405 13.9397 18.8281 13.832 18.8281H1.91797C1.81027 18.8281 1.72266 18.7405 1.72266 18.6328V14.8568V5.07832C1.72266 4.97062 1.81027 4.88301 1.91797 4.88301H13.832C13.9397 4.88301 14.0273 4.97062 14.0273 5.07832V18.6328ZM11.4271 1.18137C11.5096 1.15418 11.5725 1.18676 11.6028 1.20859C11.633 1.23043 11.6836 1.28 11.6836 1.36684V3.71117H3.75695L11.4271 1.18137Z" fill="#8D99AE" stroke="#8D99AE" stroke-width="0.2" />
                    <path d="M3.96875 11.6594H4.67898H11.7812C12.1048 11.6594 12.3672 11.3971 12.3672 11.0735C12.3672 10.7499 12.1048 10.4875 11.7812 10.4875H11.6115C11.3589 8.87043 10.078 7.58953 8.46094 7.33695V7.04336C8.46094 6.71977 8.19859 6.45742 7.875 6.45742C7.55141 6.45742 7.28906 6.71977 7.28906 7.04336V7.33695C5.67195 7.58953 4.39105 8.87043 4.13848 10.4875H3.96875C3.64516 10.4875 3.38281 10.7499 3.38281 11.0735C3.38281 11.3971 3.64516 11.6594 3.96875 11.6594ZM7.875 8.4634C9.11281 8.4634 10.1521 9.32957 10.4188 10.4875H5.33121C5.59789 9.32957 6.63723 8.4634 7.875 8.4634Z" fill="#8D99AE" stroke-width="0.2" />
                    <path d="M4.19598 13.2424C3.87239 13.2424 3.61005 13.5047 3.61005 13.8283C3.61005 14.1519 3.87239 14.4143 4.19598 14.4143H7.00848C7.33208 14.4143 7.59442 14.1519 7.59442 13.8283C7.59442 13.5047 7.33208 13.2424 7.00848 13.2424H4.19598Z" fill="#8D99AE" stroke-width="0.2" />
                    <path d="M11.6348 15.5861H4.11523C3.79164 15.5861 3.5293 15.8485 3.5293 16.1721C3.5293 16.4957 3.79164 16.758 4.11523 16.758H11.6348C11.9584 16.758 12.2207 16.4957 12.2207 16.1721C12.2207 15.8485 11.9584 15.5861 11.6348 15.5861Z" fill="#8D99AE" stroke-width="0.2" />
                </svg>
                <span class="text-xs">Home</span>
            </a>

            <a href="/customers/orders/favorites" wire:navigate class="flex flex-col items-center space-y-1 @if(Route::is('customers.orders.favorites')) text-primaryblue @else text-gray-500 @endif">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-5 h-5">
                    <path d="M11.625 3.09084L11.1341 3.56357C11.1977 3.62956 11.274 3.68204 11.3583 3.71789C11.4427 3.75375 11.5334 3.77222 11.625 3.77222C11.7167 3.77222 11.8074 3.75375 11.8917 3.71789C11.9761 3.68204 12.0523 3.62956 12.1159 3.56357L11.625 3.09084ZM7.08048 13.0181C6.94063 12.9031 6.76083 12.8484 6.58063 12.8659C6.40042 12.8835 6.23458 12.9719 6.11957 13.1118C6.00456 13.2516 5.94981 13.4314 5.96737 13.6116C5.98493 13.7918 6.07336 13.9577 6.2132 14.0727L7.08048 13.0181ZM2.84502 10.2827C2.888 10.3612 2.94603 10.4305 3.0158 10.4867C3.08557 10.5428 3.16571 10.5846 3.25164 10.6098C3.33758 10.6349 3.42763 10.6429 3.51665 10.6333C3.60567 10.6236 3.69192 10.5965 3.77048 10.5536C3.84903 10.5106 3.91835 10.4526 3.97448 10.3828C4.03061 10.313 4.07244 10.2329 4.0976 10.1469C4.12276 10.061 4.13074 9.97096 4.12109 9.88194C4.11145 9.79292 4.08436 9.70667 4.04139 9.62811L2.84502 10.2827ZM3.21593 6.3972C3.21593 4.44266 4.32048 2.80266 5.82866 2.11266C7.29411 1.44266 9.2632 1.61993 11.1341 3.56357L12.1159 2.61902C9.89775 0.312659 7.31957 -0.0682499 5.26139 0.872659C3.24866 1.79357 1.85229 3.93175 1.85229 6.3972H3.21593ZM8.44048 15.8181C8.90684 16.1854 9.40684 16.5763 9.9132 16.8727C10.4196 17.1681 10.9977 17.409 11.625 17.409V16.0454C11.3432 16.0454 11.0123 15.9363 10.6014 15.6954C10.1896 15.4554 9.7632 15.1245 9.28502 14.7472L8.44048 15.8181ZM14.8096 15.8181C16.1059 14.7954 17.7641 13.6245 19.0641 12.1599C20.3887 10.669 21.3977 8.82084 21.3977 6.3972H20.0341C20.0341 8.39539 19.2159 9.93448 18.045 11.2545C16.8496 12.5999 15.3432 13.6608 13.965 14.7472L14.8096 15.8181ZM21.3977 6.3972C21.3977 3.93175 20.0023 1.79357 17.9887 0.872659C15.9305 -0.0682499 13.3541 0.312659 11.1341 2.61811L12.1159 3.56357C13.9868 1.62084 15.9559 1.44266 17.4214 2.11266C18.9296 2.80266 20.0341 4.44175 20.0341 6.3972H21.3977ZM13.965 14.7472C13.4868 15.1245 13.0605 15.4554 12.6487 15.6954C12.2378 15.9354 11.9068 16.0454 11.625 16.0454V17.409C12.2523 17.409 12.8305 17.1681 13.3368 16.8727C13.8441 16.5763 14.3432 16.1854 14.8096 15.8181L13.965 14.7472ZM9.28502 14.7472C8.56139 14.1772 7.82593 13.6318 7.08048 13.0181L6.2132 14.0727C6.96775 14.6936 7.76593 15.2863 8.44048 15.8181L9.28502 14.7472ZM4.04139 9.62902C3.49376 8.64032 3.20951 7.52742 3.21593 6.3972H1.85229C1.85229 7.8863 2.23411 9.16539 2.84502 10.2827L4.04139 9.62902Z" fill="#8D99AE" />
                </svg>
                <span class="text-xs">Favorite</span>
            </a>

            <button @click="showProfile = !showProfile" class="p-1 text-gray-100 rounded-full bg-gr ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                </svg>
            </button>

            <a href="/customers/orders/cart" wire:navigate class="flex flex-col items-center space-y-1 @if(Route::is('customers.orders.cart')) text-primaryblue @else text-gray-500 @endif">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                <span class="text-xs">Cart</span>
            </a>

            <a href="/customers/orders/view" wire:navigate class="flex flex-col items-center space-y-1 @if(Route::is('customers.orders.view')) text-primaryblue @else text-gray-500 @endif">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <span class="text-xs">Orders</span>
            </a>

        </div>

    </div>
    @livewireScripts
</body>

</html>