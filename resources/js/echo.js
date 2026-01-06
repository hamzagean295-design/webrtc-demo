import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: "yofxzqccuvvez3y4fshq",
    wsHost: "lara-consult-production.up.railway.app",
    wsPort: 80, // Forcez 443 car Railway gère le SSL
    wssPort: 80,
    forceTLS: false,
    enabledTransports: ['ws'],
});
