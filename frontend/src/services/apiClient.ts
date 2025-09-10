import axios from "axios";

const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    withCredentials: true,
    withXSRFToken: true,
    // headers: {
    //     'Accept': 'application/json',
    //     'Content-Type': 'application/json',
    // }
});


// Request Interceptor to ensure CSRF token is set
apiClient.interceptors.request.use(async(config: any) => {
    // Only set CSRF for non-GET requests to your API
    if(config.method !== 'get' && config.url?.startsWith('/api')) {
        try {
            // Get CSRF token from Laravel Sanctum
            await axios.get('/sanctum/csrf-cookie', {
                baseURL: import.meta.env.VITE_API_URL,
                withCredentials: true
            });

        } catch (error) {
            console.error('Failed to get CSRF token:', error);

            throw error;
        }
    }
    
    return config;
});


export default apiClient;