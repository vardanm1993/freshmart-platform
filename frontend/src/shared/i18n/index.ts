import { createI18n } from 'vue-i18n';

import en from './locales/en.json';
import ru from './locales/ru.json';
import hy from './locales/hy.json';

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
