import apiClient from './axios';

export const checkFriend = async (id) => {
    try {
        const response = await apiClient.get(`/friends/status/${id}`);
        return response.data;
    } catch (error) {
        console.error("Erreur lors de la vérification du statut d'amitié :", error);
        throw error;
    }
};

export const checkUsersWithFriendStatus = async () => {
    try {
        const response = await apiClient.get('/friends/status');
        return response.data.users;
    } catch (error) {
        console.error("Erreur lors de la vérification des utilisateurs avec statut d'amitié :", error);
        throw error;
    }
};

export const getUserFriends = async () => {
    try {
        const response = await apiClient.get('/friends');
        return response.data;
    } catch (error) {
        console.error("Erreur lors de la récupération des amis :", error);
        throw error;
    }
};

export const getUserFriendOnline = async () => {
    try {
        const response = await apiClient.get('/usersOnline'); 
        return response.data;
    }catch (error) {
        console.error("Erreur lors de la récupération des informations des utilisateurs online", error);
        throw error;
    }
}

export const sendInvitation = async (formData) => {
    try {
        const response = await apiClient.post('/invitations', formData);
        return response.data;
    } catch (error) {
        console.error("Erreur lors de l'envoi de l'invitation :", error);
        throw error;
    }
};

export const acceptInvitation = async (invitation) => {
    try {
        const response = await apiClient.post(`/invitations/${invitation}/accept`, {});
        return response.data;
    } catch (error) {
        console.error("Erreur lors de l'acceptation de l'invitation :", error);
        throw error;
    }
};

export const rejectInvitation = async (invitation) => {
    try {
        const response = await apiClient.post(`/invitations/${invitation}/reject`, {});
        return response.data;
    } catch (error) {
        console.error("Erreur lors du refus de l'invitation :", error);
        throw error;
    }
};

export const deleteFriend = async (id) => {
    try {
        const response = await apiClient.delete(`/friend/remove/${id}`);

        return response.data;
    } catch (error) {
        console.error('Erreur lors de la suppression de l ami: ', error);
    }
}