<div>
@if($successMessage)
    <div x-data="{ visible: true }" x-init="setTimeout(() => { visible = false; }, 3000); setTimeout(() => { $wire.call('clearSuccessMessage'); }, 3001);" x-show="visible" class="fixed top-0 right-0 z-50 p-2 mt-24 text-center text-white bg-green-500 rounded mr-20">
        {{ $successMessage }}
    </div>
    @endif

    <!-- Main modal -->
    <div class="overflow-y-auto overflow-x-hidden fixed -bottom-24 right-0 left-0 z-50 justify-center rounded-3xl  items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-auto">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
                <!-- Modal header -->
            <div class="flex flex-col items-center justify-center rounded-3xl h-auto py-4 bg-blackgrey">
               <div class="flex flex-row px-4 justify-between w-full">
                    <span></span>
                    <a href="/customers/orders/home" class="text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>
              
                @if ($form->profile_photo_path)
                    
                    <img src="{{ $form->profile_photo_path->temporaryUrl() }}" class="w-[100px] h-[100px] object-cover rounded-full mb-4" alt="Image">
                    
                @else
                    @if(auth()->user()->profile_photo_path)
                
                            <img src="{{ url('storage/'. auth()->user()->profile_photo_path) }}" class="w-[100px] h-[100px] rounded-full object-cover mb-4" alt="Image">
                    @endif
                @endif
                <label for="file-upload" class="font-semibold bg-transparent rounded-lg cursor-pointer text-md font-urbanist text-primaryblue">
                    Change Profile Photo
                    <input id="file-upload" name="file-upload" type="file" class="sr-only" wire:model="form.profile_photo_path" />
                </label>
            
                <!-- Modal body -->
                <div class="p-4 bg-blackgrey w-full md:p-5">
                    <form  wire:submit="updateProfile" class="space-y-4" action="#">
                        <span>
                            <input wire:model="form.name" type="text" name="name" id="name" class="bg-textfield border border-bordercol outline-none text-discription text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Name" value="{{ucfirst(auth()->user()->name)}}" />
                            @error('form.name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </span>

                        <span>
                            <input wire:model="form.email" type="email" name="email" id="email" class="bg-textfield border border-bordercol text-discription text-sm rounded-lg outline-none block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Email" value="{{auth()->user()->email}}"/>
                            @error('form.email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </span>
                        <span>
                            <input wire:model="form.phone" type="tel" name="phone" id="phone" placeholder="Phone (Optional)" class=" bg-textfield border border-bordercol text-discription text-sm rounded-lg outline-none block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="{{auth()->user()->phone}}" />
                            @error('form.phone') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </span>
                        <button type="submit" class="bg-primaryblue w-full text-white py-2 px-4 text-md font-semibold rounded-md">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>