const defaultBaseUrl = import.meta.env.DEV ? '' : 'http://localhost';

export const env = {
  apiBaseUrl: import.meta.env.VITE_API_BASE_URL ?? defaultBaseUrl,
} as const;
