import './bootstrap';

// This code will run after bootstrap.js has initialized Echo
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    const userId = document.querySelector('meta[name="user-id"]')?.content;

    // Ensure Echo is ready and we have a user ID
    if (window.Echo && userId) {
        console.log(`Listening for private events on user.${userId}`);
        window.Echo.private(`user.${userId}`)
            .listen('.DemarrerConsultationEvent', event => {
                if (container) {
                    const notificationId = 'notif-' + Date.now();
                    const notification = document.createElement('div');
                    notification.id = notificationId;
                    notification.className = 'transform transition-all duration-300 ease-in-out';
                    
                    notification.innerHTML = `
                        <div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                            <div class="p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <!-- Heroicon name: outline/video-camera -->
                                        <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 w-0 flex-1 pt-0.5">
                                        <p class="text-sm font-medium text-gray-900">
                                            Invitation à une consultation
                                        </p>
                                        <p class="mt-1 text-sm text-gray-600">
                                            ${event.message}
                                        </p>
                                        <div class="mt-3 flex space-x-4">
                                            <a href="${event.consultationUrl}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Rejoindre
                                            </a>
                                            <button onclick="document.getElementById('${notificationId}').remove()" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Ignorer
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex">
                                        <button onclick="document.getElementById('${notificationId}').remove()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <span class="sr-only">Close</span>
                                            <!-- Heroicon name: solid/x -->
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Clear previous notifications and add the new one
                    container.innerHTML = '';
                    container.appendChild(notification);

                    // Optional: auto-dismiss after some time
                    setTimeout(() => {
                        const el = document.getElementById(notificationId);
                        if(el) el.remove();
                    }, 30000); // 30 seconds
                }
            });
    } else {
        console.log('Echo or User ID not found, cannot listen for private events.');
    }
});