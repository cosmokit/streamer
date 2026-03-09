# Интеграция Nezhna

## Описание
Интеграция с сервисом Nezhna для автоматического запуска трафика на Twitch каналы пользователей при старте стрима.

## Компоненты

### 1. База данных

**Таблица `settings`:**
- `id` - Primary key
- `key` - Уникальный ключ настройки (varchar 255)
- `value` - Значение настройки (text, nullable)
- `created_at`, `updated_at` - Timestamps

**Модель `Setting`:**
```php
Setting::get('nezhna_api_key', 'default_value');
Setting::set('nezhna_api_key', 'new_value');
```

### 2. Админ панель

**Страница настроек:** `/admin/settings`
- Форма для ввода API ключа Nezhna
- Информация о сервисе
- Валидация: `nullable|string|max:500`

**Навигация:**
- Добавлена ссылка "Настройки" в sidebar админки

### 3. API Endpoints

**GET /api/stream-runs**
- Возвращает текущий день стрима и последний Twitch URL
- Требует авторизации

**POST /api/stream-runs/start**
- Запускает стрим и отправляет запрос в Nezhna API
- Требует: `twitch_url` (URL)
- Проверки:
  1. Наличие API ключа Nezhna
  2. Наличие Twitch канала у пользователя
  3. Лимит 4 дня
- Возвращает ошибки с типами:
  - `no_api_key` - отсутствует API ключ (503)
  - `no_twitch` - не указан Twitch канал (400)
  - `nezhna_error` - ошибка API Nezhna (503)
  - `connection_error` - ошибка подключения (503)

### 4. Frontend

**TrafficPage.tsx:**
- Загружает текущий день через `/api/stream-runs`
- Показывает уведомление "Обратитесь в поддержку" при ошибках сервиса
- Кнопка "Связаться с поддержкой" ведёт на https://t.me/profitstream_support
- Обработка всех типов ошибок (`no_api_key`, `no_twitch`, `nezhna_error`, `connection_error`)

**ProfilePage.tsx:**
- Отображает Telegram и Twitch пользователя (read-only)
- Примечание: "Для изменения обратитесь к администратору"

### 5. Логика работы

**При клике "Запустить Стрим":**
1. Проверяется наличие API ключа Nezhna в настройках
2. Проверяется наличие Twitch канала у пользователя
3. Отправляется POST запрос на `https://nezhna.com/api/v1/traffic/start`:
   ```json
   {
     "twitch_channel": "user_twitch_name",
     "twitch_url": "https://www.twitch.tv/user_twitch_name",
     "user_id": 2
   }
   ```
4. При успехе:
   - Создаётся запись StreamRun
   - Создаётся уведомление
   - Инкрементируется день стрима
5. При ошибке:
   - Логируется ошибка
   - Пользователю показывается сообщение "Обратитесь в поддержку"
   - Показывается кнопка для связи с поддержкой

## Демо данные

**API ключ:** `demo_api_key_12345` (установлен в базе)

**Пользователи:**
- `user1@streamer.local` - Telegram: `@user1_stream`, Twitch: `user1_twitch`
- `user2@streamer.local` - Telegram: `@user2_stream`, Twitch: `user2_twitch`

## Тестирование

### Админ панель
1. Войдите как `admin@streamer.local` / `admin12345`
2. Перейдите в "Настройки"
3. Вставьте API ключ Nezhna
4. Нажмите "Сохранить"

### Пользовательская часть
1. Войдите как `user1@streamer.local` / `user12345`
2. Перейдите в "Мой профиль" - проверьте наличие Telegram и Twitch
3. Перейдите в "Управление трафиком"
4. Введите Twitch URL
5. Нажмите "Запустить Стрим"
6. При ошибке должно показаться сообщение "Обратитесь в поддержку"

### Тестирование без API ключа
```bash
# Удалить API ключ
docker compose exec -T backend php artisan tinker --execute="
App\Models\Setting::where('key', 'nezhna_api_key')->delete();
echo 'API key removed';
"

# Попробовать запустить стрим - должна показаться ошибка с предложением обратиться в поддержку
```

## Файлы

**Backend:**
- `database/migrations/2026_03_09_170909_create_settings_table.php`
- `app/Models/Setting.php`
- `app/Http/Controllers/Admin/SettingController.php`
- `app/Http/Controllers/Api/StreamRunController.php` (обновлён)
- `resources/views/admin/settings/index.blade.php`
- `resources/views/admin/layout.blade.php` (добавлена ссылка "Настройки")
- `routes/web.php` (добавлены роуты settings)
- `routes/api.php` (добавлен GET /api/stream-runs)

**Frontend:**
- `frontend/src/pages/dashboard/TrafficPage.tsx` (полностью переделан)
- `frontend/src/pages/dashboard/ProfilePage.tsx` (уже показывает Telegram/Twitch)

## API Nezhna

**Endpoint:** `POST https://nezhna.com/api/v1/traffic/start`

**Headers:**
- `Authorization: Bearer {api_key}`
- `Accept: application/json`

**Body:**
```json
{
  "twitch_channel": "string",
  "twitch_url": "string",
  "user_id": number
}
```

**Примечание:** API endpoint может отличаться. Уточните у сервиса Nezhna правильный URL и формат запроса.
