import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "19bce95f00004fbdb5c3",
    cluster: "eu", // Remplacez par votre cluster Pusher
    forceTLS: true
});
