<div class="flex flex-row items-center justify-center w-full px-4 py-4">

    <!--Main login-->
    <div class="flex flex-col w-full max-w-md p-5 space-y-2 rounded-lg bg-blackgrey">
        <div class="flex flex-row justify-between items-center">
            <span></span>
            <p class="text-lg ml-4 font-bold text-center text-white">Login</p>
            <button onclick="window.history.back()" class="text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form wire:submit="login" class="space-y-4">
            <input type="email" wire:model="login_form.email" class="bg-textfield border border-bordercol text-gray-100 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Email" required />
            <input type="password" wire:model="login_form.password" inputmode="numeric" class="bg-textfield border border-bordercol text-gray-100 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="PIN" required />
            @error('login_form.email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            <button type="submit" class="w-full px-4 py-2 font-semibold text-white rounded-md bg-primaryblue text-md">Login</button>
        </form>
        <p wire:click="openForgotPinModal" class="text-sm text-white underline cursor-pointer self-end">Forgot PIN?</p>
    </div>

    <!--Forgot Password-->
    <div x-show="$wire.showForgotPwdDialog" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="flex flex-col w-full max-w-md p-5 space-y-2 rounded-lg bg-blackgrey">
            <div class="flex flex-row justify-between">
                <span></span>
                <p class="text-lg font-bold text-center text-white">Reset Your Pin</p>
                <button @click="$wire.showForgotPwdDialog = false" class=" text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form wire:submit="sendResetPin" class="mt-4 space-y-4">
                <div>
                    <input type="email" wire:model="forgot_pin_form.email" class="bg-textfield border border-bordercol text-gray-100 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Email" required />
                    @error('forgot_pin_form.email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" wire:loading.attr="disabled" wire:target="sendResetPin" class="w-full px-4 py-2 font-semibold text-white bg-opacity-100 rounded-md disabled:bg-opacity-50 bg-primaryblue text-md">Send OTP</button>
            </form>
        </div>
    </div>

        <!--Reset PIN-->

    <div x-show="$wire.showResetPwdDialog" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="flex flex-col w-full max-w-md p-5 space-y-2 rounded-lg bg-blackgrey">

        <div class="flex flex-row justify-between">
                <span></span>
                <p class="text-lg font-bold text-center text-white">Update Pin</p>
                <button @click="$wire.showResetPwdDialog = false" class=" text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form wire:submit="submitPin" class="mt-4 space-y-4">
                <div class="flex flex-col items-start w-full space-y-2">
                    <div class="flex flex-col w-full">
                        <input type="email" wire:model="reset_pin_form.email" class="bg-textfield border border-bordercol text-gray-100 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Email" required />
                        @error('reset_pin_form.email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col w-full">
                        <input type="number" wire:model="reset_pin_form.token" class="bg-textfield border border-bordercol text-gray-100 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="OTP" required />
                        @error('reset_pin_form.token')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col w-full">
                        <input inputmode="numeric" type="password" wire:model="reset_pin_form.password" class="bg-textfield border border-bordercol text-gray-100 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="New Pin" required />
                        @error('reset_pin_form.password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col w-full">
                        <input inputmode="numeric" type="password" wire:model="reset_pin_form.password_confirmation" class="bg-textfield border border-bordercol text-gray-100 text-sm rounded-lg outline-none focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Confirm New Pin" required />
                        @error('reset_pin_form.password_confirmation')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" wire:loading.attr="disabled" wire:target="submitPin" class="w-full px-4 py-2 font-semibold text-white bg-opacity-100 rounded-md disabled:bg-opacity-50 bg-primaryblue text-md">Submit</button>
            </form>
        </div>
    </div>


</div>