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
                    container.innerHTML = event.message;
                    const link = document.createElement('a');
                    link.href = event.consultationUrl;
                    link.innerHTML = 'joindre';
                    container.appendChild(link);
                }
            });
    } else {
        console.log('Echo or User ID not found, cannot listen for private events.');
    }
});