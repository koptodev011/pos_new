<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased dark:bg-black dark:text-red/50" style="background-color: rgba(0, 0, 0, 0.4); background-image: url('assets/meal_table.jpg'); background-blend-mode: multiply; background-size: cover; background-position: center;">

<header class="bg-black">
<div class="absolute top-0 right-0 flex justify-between w-full px-8 mt-8">
    <div>
        <button label="Support" icon="o-phone" :link="route('login')" no-wire-navigate class="text-white bg-transparent animate-bounce  hover:text-black focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
           Support
        </button>
    </div>

    <div>
        <button label="Login" :link="route('login')" no-wire-navigate class="text-white bg-transparent hover:text-black focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300 ...">    
            Log in
        </button>
    </div>
</div>

</header>

<div class="flex items-center justify-center h-screen">

<!-- <div class="pb-10 ">
        <img src="assets/restaurant_menu.png" class="h-20 sm:h-32 md:h-40 lg:h-48 xl:h-56 mb-10 translate-x-10... rotate-45 ..." alt="Logo">
    </div> -->

    <div class="text-center">
        <img src="assets/Logo_new1.png" class="h-20 mb-8 sm:h-32 md:h-40 lg:h-48 xl:h-56" alt="Logo">
        <!-- <h1 class="text-4xl text-black sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl">Welcome to <br class="sm:hidden md:hidden lg:block xl:block"> Quickdine</h1> -->
    </div>

    
</div>

<footer class="absolute bottom-0 left-0 w-full py-4 text-center text-white">
    <p class="text-sm">© 2024 Quickdine. All rights reserved.</p>
</footer>

</body>
</html>
