import apiClient from "./axios";
import { token } from "./store";
import { writable } from "svelte/store";

export const login = async (formData) => {
    try {
        const response = await apiClient.post('/login', formData);
        const token = response.data.token;

        // Sauvegarde du token dans le localStorage ou le sessionStorage
        localStorage.setItem('token', token);

        return response.data;
    } catch (err) {
        console.error(err);
        alert(err.message);
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
    localStorage.setItem('token', null);
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
        const response = await apiClient.get('/users'); // Route pour obtenir les infos de l'utilisateur connecté
        return response.data.other_users;
    } catch (error) {
        console.error("Erreur lors de la récupération des utilisateurs", error);
        throw error;
    }
};