import { writable } from 'svelte/store';

export const user = writable(null);

export const token = writable(null);
export const users = writable([]);
export const friends = writable([]);
export const messages = writable([]);