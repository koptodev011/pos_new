<div class="flex flex-row items-center justify-center px-4 py-4">

    <div class="flex flex-col w-full max-w-md p-5 space-y-4 rounded-lg bg-blackgrey">
        <div class="flex flex-row justify-end">
            <button onclick="window.history.back()" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <span class="flex flex-col  items-center w-full space-y-2">
            @if ($form->profile_photo_path)
            <img src="{{ $form->profile_photo_path->temporaryUrl() }}" class="w-8 h-8 rounded-full">
            @else
            <img src="/assets/images/prof.svg" class="w-8 h-8 rounded-full" alt="Image">
            @endif
            <label for="file-upload" class="font-semibold bg-transparent rounded-lg cursor-pointer text-md font-urbanist text-primaryblue">
                Add Photo
                <input id="file-upload" name="file-upload" type="file" class="sr-only" wire:model="form.profile_photo_path" />
            </label>
            <p class="text-xs text-center text-discription ">(Optional)</p>
        </span>
        
        <!-- Modal body -->
        <div class="w-full bg-blackgrey">
            <form wire:submit="register" class="space-y-4" action="#">
                <span class="flex flex-col space-y-1">
                    <input wire:model="form.name" type="text" name="name" id="name" class="bg-textfield border border-bordercol outline-none text-gray-100 text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Name" required />
                    @error('form.name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </span>

                <span class="flex flex-col space-y-1">
                    <input wire:model="form.email" type="email" name="email" id="email" class="bg-textfield border border-bordercol text-gray-100 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Email" required />
                    @error('form.email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </span>
                <span class="flex flex-col space-y-1">
                    <input wire:model="form.phone" type="tel" name="phone" id="phone" placeholder="Phone (Optional)" class=" bg-textfield border border-bordercol text-gray-100 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('form.phone') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </span>

                <span class="flex flex-col space-y-1">
                    <input wire:model="form.password" type="password" inputmode="numeric" class="bg-textfield border border-bordercol text-gray-100 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Set Your PIN" required />
                    @error('form.password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </span>

                <span class="flex flex-col space-y-1">
                    <input wire:model="form.password_confirmation" type="password" inputmode="numeric" class="bg-textfield border border-bordercol text-gray-100 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Confirm Pin" required />
                </span>

                <button type="submit" class="w-full px-4 py-2 font-semibold text-white rounded-md bg-primaryblue text-md">Sign Up</button>

            </form>
        </div>
    </div>
</div>