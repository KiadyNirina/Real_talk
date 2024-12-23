import apiClient from "./axios";
import { token } from "./store";
import { writable } from "svelte/store";

export const login = async (formData) => {
    try {
        const response = await apiClient.post('/login', formData);
        const token = response.data.token;

        localStorage.setItem('token', token);

        return response.data;
    } catch (err) {
        console.error(err);
    }
}

export const registerUser = async (formData) => {
    try {
        const response = await apiClient.post('/register', formData);
        return response.data;
    } catch (err) {
        console.error('Erreur lors de l’inscription', err);
    }
};

export const logout = async () => {
    try { 
        const response = await apiClient.post('/logout');

        localStorage.removeItem('token');

        return response;
    } catch (err) {
        console.error(err);
    }
}

export const getUserInfo = async () => {
    try {
        const response = await apiClient.get('/users'); // Route pour obtenir les infos de l'utilisateur connecté
        return response.data.user;
    } catch (error) {
        console.error("Erreur lors de la récupération de l'utilisateur", error);
        throw error;
    }
};

export const getAllUser = async () => {
    try {
        const response = await apiClient.get('/users'); // Route pour obtenir les infos des utilisateurs
        return response.data.other_users;
    } catch (error) {
        console.error("Erreur lors de la récupération des utilisateurs", error);
        throw error;
    }
};

export const getUserFriendOnline = async () => {
    try {
        const response = await apiClient.get(`/usersOnline`); // Route pour obtenir les infos des utilisateurs online
        return response.data;
    }catch (error) {
        console.error("Erreur lors de la récupération des informations des utilisateurs online", error);
        throw error;
    }
}

export const getUserSelectedInfo = async (id) => {
    try {
        const response = await apiClient.get(`/user/${id}`); // Route pour obtenir les infos des utilisateurs
        return response.data;
    }catch (error) {
        console.error("Erreur lors de la récupération des informations de l'utilisateur séléctionné", error);
        throw error;
    }
}

export const sendInvitation = async (formData) => {
    try {
        const response = await apiClient.post('/invitations', formData);
        return response.data;
    } catch (error) {
        console.error("Erreur lors de l envoie de l invitation:", error);
        throw error;
    }
}

export const acceptInvitation = async (invitation) => {
    try {
        const response = await apiClient.post(`/invitations/${invitation}/accept`, {});
        return response.data;
    } catch (err) {
        console.error("Erreur lors de l'acceptation de l'invitation:", err);
        throw err;
    }
}

export const rejectInvitation = async (invitation) => {
    try {
        const response = await apiClient.post(`/invitations/${invitation}/reject`, {});
        return response.data;
    } catch (err) {
        console.error("Erreur lors du refus de l'invitation:", err);
        throw err;
    }
}

export const checkFriend = async (id) => {
    try {
        const response = await apiClient.get(`/friends/status/${id}`);

        return response.data;
    } catch (error) {
        console.error('Erreur lors de la vérification du statut d\'amitié', error);
    }
}

export const checkUsersWithFriendStatus = async () => {
    try {
        const response = await apiClient.get(`/friends/status`);

        return response.data.users;
    } catch (error) {
        console.error('Erreur lors de la vérification du statut d\'amitié', error);
    }
}

export const getUserFriends = async () => {
    try {
        const response = await apiClient.get(`/friends`);

        return response.data;
    } catch (error) {
        console.error('Erreur lors de la récupération d amis: ', error);
    }
}

export const sendMessage = async (formData) => {
    try {
        const response = await apiClient.post('/messages', formData);
        return response.data;
    } catch (error) {
        console.error("Erreur lors de l envoie du message:", error);
        throw error;
    }
}

export const getMessage = async (id) => {
    try {
        const response = await apiClient.get(`/getMessage/${id}`);
        return response.data;
    } catch (error) {
        console.error('Erreur lors du récupération des message:', error);
        throw error;
    }
}

export const updateUser = async (formData) => {
    try {
        const response = await apiClient.put('/updateUser', formData);
        return response.data;
    } catch (error) {
        console.error("Erreur lors du mise à jour de l'information:", error);
        throw error;
    }
}