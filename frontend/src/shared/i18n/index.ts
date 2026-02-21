import { createI18n } from 'vue-i18n';

import en from './locales/en.json';
import ru from './locales/ru.json';
import hy from './locales/hy.json';
import {fetchI18nOverrides} from "@/shared/i18n/overrides.ts";

type MessageSchema = typeof en;

export type AppLocale = 'en' | 'ru' | 'hy';
export const DEFAULT_LOCALE: AppLocale = 'en';

const _messagesStrict: Record<AppLocale, MessageSchema> = {
  en,
  ru: ru as MessageSchema,
  hy: hy as MessageSchema,
};

export const i18n = createI18n({
  legacy: false,
  locale: DEFAULT_LOCALE,
  fallbackLocale: 'en',
  messages: _messagesStrict as unknown as Record<string, MessageSchema>,
});

export async function applyLocale(locale: AppLocale): Promise<void> {
  document.documentElement.lang = locale;

  const overrides = await fetchI18nOverrides(locale);

  const current = (i18n.global.getLocaleMessage(locale) ?? {}) as Record<string, any>;
  i18n.global.setLocaleMessage(locale, { ...current, ...overrides });

  i18n.global.locale.value = locale;
}
