import apiClient from './axios';

export const getUserInfo = async () => {
    try {
        const response = await apiClient.get('/users');
        return response.data.user;
    } catch (error) {
        console.error("Erreur lors de la récupération de l'utilisateur :", error);
        throw error;
    }
};

export const updateUser = async (formData) => {
    try {
        const response = await apiClient.put('/updateUser', formData);
        return response.data;
    } catch (error) {
        console.error("Erreur lors de la mise à jour des informations :", error);
        throw error;
    }
};

export const getAllUsers = async () => {
    try {
        const response = await apiClient.get('/users');
        return response.data.other_users;
    } catch (error) {
        console.error("Erreur lors de la récupération des utilisateurs :", error);
        throw error;
    }
};

export const getUserSelectedInfo = async (id) => {
    try {
        const response = await apiClient.get(`/user/${id}`);
        return response.data;
    } catch (error) {
        console.error("Erreur lors de la récupération des informations de l'utilisateur sélectionné :", error);
        throw error;
    }
};
