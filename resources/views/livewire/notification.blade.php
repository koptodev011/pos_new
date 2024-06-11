<style>
    @keyframes slideFromBottom {
        0% {
            transform: translateY(100%);
            opacity: 0;
        }

        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .slide-from-bottom {
        animation: slideFromBottom 0.5s ease-out;
    }
</style>

<div class="max-w-md slide-from-bottom dark:divide-gray-700">
    <p class="text-center mb-4 text-lg text-white font-bold">Notification</p>
    <span x-data="{ notifications: @entangle('notifications') }">
        <template x-for="(notification, index) in notifications" :key="index">
            <div x-show="notification.visible" class="bg-blackgrey py-2 px-4 mx-4 mb-4 border border-bordercol rounded-lg shadow slide-from-bottom relative">
                <div class="flex items-center">
                    <img class="w-12 flex-shrink-0 h-12 pr-4 rounded-full" src="/assets/images/Prof.svg" alt="Profile picture">
                    <span class="flex flex-col flex-1 justify-between">
                        <h3 class="font-bold text-sm text-white " x-text="notification.message"></h3>
                        <p class="text-discription text-xs" x-text="notification.details"></p>
                        <p class="text-discription text-xs" x-text="notification.date"></p>
                    </span>
                    <span class="flex items-center justify-center p-2 w-4 h-4 text-sm bg-primaryblue mr-8 text-white rounded-full">
                        1
                    </span>
                    <button @click="notification.visible = false" class="text-red-500 hover:text-red-700 absolute top-0 right-0 mt-1 mr-1 ">
                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 11.414l3.535 3.536 1.415-1.415L11.414 10l3.536-3.535-1.415-1.415L10 8.586 6.465 5.05 5.05 6.465 8.586 10l-3.535 3.535 1.415 1.415L10 11.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </span>
</div>