// src/lib/axios.js
import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api', // URL de votre backend Laravel
  headers: {
    'Content-Type': 'application/json',
  },
});

apiClient.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});  

export default apiClient;
