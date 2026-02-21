import { http } from '@/shared/api/http';

export type TranslationItem = { key: string; value: string };

export async function fetchI18nOverrides(locale: string): Promise<Record<string, string>> {
  const res = await http.get<{ items: TranslationItem[] }>(`/api/i18n/translations`, {
    params: { locale },
  });

  const out: Record<string, string> = {};
  for (const item of res.data.items) {
    out[item.key] = item.value;
  }

  return out;
}
