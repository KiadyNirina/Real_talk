import Pusher from 'pusher-js';

const pusher = new Pusher('5a5c595863d7a664f170', {
    cluster: 'eu',
    forceTLS: true,
});

pusher.config.authorizer = (channel) => ({
    authorize: (socketId, callback) => {
        fetch('http://localhost:8000/broadcasting/auth', {
            method: 'POST',
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                socket_id: socketId,
                channel_name: channel.name,
            }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                callback(null, data);
            })
            .catch((error) => {
                console.error('Erreur lors de l\'autorisation Pusher:', error);
                callback(error);
            });
    },
});

export default pusher;