import apiClient from './axios';

export const login = async (formData) => {
    try {
        const response = await apiClient.post('/login', formData);
        const token = response.data.token;
        localStorage.setItem('token', token);
        return response.data;
    } catch (err) {
        console.error("Erreur lors de la connexion :", err);
        throw err;
    }
};

export const registerUser = async (formData) => {
    try {
        const response = await apiClient.post('/register', formData);
        return response.data;
    } catch (err) {
        console.error("Erreur lors de l'inscription :", err);
        throw err;
    }
};

export const logout = async () => {
    try {
        const response = await apiClient.post('/logout');
        localStorage.removeItem('token');
        return response;
    } catch (err) {
        console.error("Erreur lors de la déconnexion :", err);
        throw err;
    }
};
