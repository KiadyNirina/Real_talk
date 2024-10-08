import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Configuration de Laravel Echo
window.Pusher = Pusher;

export const echo = new Echo({
    broadcaster: 'pusher',
    key: '5a5c595863d7a664f170',
    cluster: 'eu',
    forceTLS: true
});