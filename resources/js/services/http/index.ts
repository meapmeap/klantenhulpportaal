import axios from 'axios';

const http = axios.create({
    baseURL: 'http://127.0.0.1:8000',
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        Accept: 'application/json',
    },
});

export const getRequest = (endpoint: string) => http.get(endpoint);
export const postRequest = <Item>(endpoint: string, data: Item) => http.post(endpoint, data);
export const putRequest = <Item>(endpoint: string, data: Item) => http.put(endpoint, data);
export const deleteRequest = (endpoint: string) => http.delete(endpoint);