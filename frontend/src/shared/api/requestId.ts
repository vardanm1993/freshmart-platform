export function createRequestId(): string {
  // lightweight unique id (good enough for client-side correlation)
  return `${Date.now().toString(36)}-${Math.random().toString(36).slice(2, 10)}`;
}
