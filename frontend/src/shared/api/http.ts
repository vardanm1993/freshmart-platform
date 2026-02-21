import axios from 'axios';
import { env } from '@/shared/config/env';
import { createRequestId } from '@/shared/api/requestId';

export type ApiError = {
  status: number;
  message: string;
  details?: unknown;
  requestId?: string;
};

function normalizeError(error: unknown): ApiError {
  if (!axios.isAxiosError(error)) {
    return { status: 0, message: 'Unexpected error' };
  }

  const status = error.response?.status ?? 0;
  const requestId = (error.config?.headers as any)?.['X-Request-Id'];
  const data: any = error.response?.data;

  return {
    status,
    message: data?.message ?? error.message ?? 'Request failed',
    details: data ?? null,
    requestId,
  };
}

export const http = axios.create({
  baseURL: env.apiBaseUrl,
  timeout: 15_000,
  headers: {
    Accept: 'application/json',
  },
});

http.interceptors.request.use((config) => {
  const requestId = createRequestId();

  config.headers = config.headers ?? {};
  (config.headers as any)['X-Request-Id'] = requestId;

  (config.headers as any)['Accept-Language'] = document.documentElement.lang || 'en';

  return config;
});

http.interceptors.response.use(
  (response) => response,
  (error) => Promise.reject(normalizeError(error)),
);
