import apiClient from './axios';

export const sendMessage = async (formData) => {
    try {
        const response = await apiClient.post('/send-message', formData);
        return response.data;
    } catch (error) {
        console.error("Erreur lors de l'envoi du message :", error);
        throw error;
    }
};

export const getMessage = async (id) => {
    try {
        const response = await apiClient.get(`/messages/${id}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        });
        return response.data;
    } catch (error) {
        console.error("Erreur lors de la récupération des messages :", error);
        throw error;
    }
};
