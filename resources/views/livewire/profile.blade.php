<div class="rounded-t-3xl h-auto bg-blackgrey fixed bottom-14 left-0 right-2">
<div class="absolute top-2 right-4">
        <button wire:click="navigateToEditProfile" class="text-discription focus:outline-none text-sm underline font-normal">Edit </button>
    </div>

    <div class="flex flex-col items-center justify-center rounded-t-3xl h-auto py-4 bg-blackgrey">
      
        @auth
            @if(auth()->user()->profile_photo_path)
            <img src="{{ url('storage/'.auth()->user()->profile_photo_path) }}" class="w-[100px] h-[100px] object-cover rounded-full mb-4" alt="Image">
            @else
            <img src="/assets/images/Ellipse.svg" class="w-[100px] h-[100px] rounded-full object-cover mb-4" alt="Image">
            @endif
            <p class="text-white text-xl font-bold">{{ucfirst(auth()->user()->name)}}</p>
        @else
        <p class="text-white text-xl font-bold">Guest</p>
        @endauth

    </div>
    
    <div class="h-auto py-4">
        @livewire('profile-content')
    </div>

    <hr class="border-blackgrey mx-4 w-full"> <!-- Horizontal line -->

    <div class="flex items-center m-2  px-2 justify-between  py-1"> <!-- Adjusted height to py-4 -->
        <div class="flex items-center"> <!-- Added items-center to the flex container -->

            <img src="/assets/images/Language.svg" alt="" class=" h-8 w-8 mr-4">

            <h2 class="text-md mr-2 font-semibold text-white">Language</h2>

        </div>
        <div class="flex items-center">
            <span class="text-discription text-sm font-normal">English</span>
            <a href="#" class="inline-block rounded-md px-2 py-1">
                <img src="/assets/images/Arrow.png" alt="Your Image" class="w-5 h-5">
            </a>
        </div>
    </div>

    <hr class="border-blackgrey mx-4 w-full"> <!-- Horizontal line -->

    <!-- livewire/your-livewire-component.blade.php -->

    <div class="flex items-center m-2 px-2 justify-between py-1">
        <div class="flex items-center">
            <img src="/assets/images/log-out.svg" alt="Logout Icon" class="h-5 w-5 ml-2 mr-5">
            <button wire:click="navigateToLogout" @click="showProfile = false" class="flex items-center ">
                <span class="text-white text-md font-semibold">Log Out</span>
            </button>
        </div>

    </div>


    <hr class="border-blackgrey mx-4 w-full"> <!-- Horizontal line -->




</div>