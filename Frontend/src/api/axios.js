import axios from 'axios';

// Création de l'instance Axios avec des paramètres globaux
const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
  },
});

// Ajout d'un intercepteur pour inclure le token dans les requêtes
apiClient.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, (error) => {
    // Gestion des erreurs de requêtes
    return Promise.reject(error);
});

// Ajout d'un intercepteur pour traiter les erreurs des réponses
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        console.error("Erreur API : ", error.response || error.message);
        return Promise.reject(error);
    }
);

export default apiClient;
