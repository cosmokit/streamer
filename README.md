# Streamer - Платформа управления стримами

Полнофункциональная платформа для управления стримами на Twitch с монетизацией, прокси и аналитикой.

## Технологии

- **Backend**: Laravel 11 (PHP 8.3)
- **Frontend**: React + Vite + TypeScript + TailwindCSS + shadcn/ui
- **Database**: MariaDB 11
- **Локальная разработка**: Docker + docker-compose
- **Продакшен**: Hestia CP (без Docker)

## Быстрый старт (локально)

### Требования

- Docker и docker-compose
- Git

### Установка и запуск

1. Клонируйте репозиторий:
```bash
git clone <your-repo>
cd streamer
```

2. Запустите скрипт установки:
```bash
./scripts/dev-up.sh
```

Скрипт автоматически:
- Поднимет базу данных MariaDB
- Соберёт и запустит Laravel backend
- Установит все зависимости
- Запустит миграции и сидеры
- Соберёт frontend и скопирует в `backend/public/app`

3. Откройте браузер:
```
http://localhost:8080
```

### Демо пользователи

```
Admin:  admin@streamer.local  / admin12345
User1:  user1@streamer.local  / user12345  (с данными: 3 дня стримов)
User2:  user2@streamer.local  / user12345
```

## Структура проекта

```
streamer/
├── backend/                 # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/Api/  # API контроллеры
│   │   └── Models/               # Модели БД
│   ├── database/
│   │   ├── migrations/           # Миграции БД
│   │   └── seeders/              # Сидеры с демо данными
│   ├── routes/
│   │   ├── api.php              # API routes
│   │   └── web.php              # Web routes
│   └── public/
│       └── app/                 # Frontend build (статика)
│
├── frontend/                # React SPA
│   ├── src/
│   │   ├── pages/dashboard/     # Страницы дашборда
│   │   ├── components/          # React компоненты
│   │   └── lib/
│   │       └── api.ts           # API клиент
│   └── dist/                    # Build output
│
├── docker/                  # Docker конфигурация
│   ├── backend/Dockerfile
│   └── nginx/
│
├── scripts/                 # Скрипты для dev
│   ├── dev-up.sh           # Запуск локального окружения
│   └── build-frontend.sh   # Сборка frontend
│
└── docs/
    └── deploy-hestia.md    # Инструкция по деплою
```

## API Endpoints

### Аутентификация

- `GET /api/me` - Текущий пользователь

### Прокси

- `GET /api/proxies` - Список прокси
- `POST /api/proxies/upload` - Загрузка списка прокси (.txt)
- `POST /api/proxies/activate` - Активация прокси

### Стримы

- `POST /api/stream-runs/start` - Запуск стрима

### Уведомления

- `GET /api/notifications` - Список уведомлений

### Социальные сети

- `GET /api/social-links` - Получить ссылки
- `POST /api/social-links` - Сохранить ссылку X

### Видео

- `GET /api/videos` - Список видео
- `GET /api/videos/summary` - Статистика видео

### Шаблоны

- `GET /api/templates` - Список шаблонов

### Помощь

- `GET /api/help` - Статьи помощи

### Прогресс

- `GET /api/progress` - Общий прогресс
- `GET /api/progress/steps` - Шаги прогресса
- `POST /api/progress/steps/{stepKey}/complete` - Отметить шаг выполненным

## Особенности UI

### Разделы дашборда

1. **Мой прогресс** - Отслеживание выполнения шагов обучения
2. **Управление трафиком** - Запуск стримов, прогресс дней (1-4)
3. **Мои прокси** - Загрузка и активация прокси
4. **Шаблоны** - Каталог готовых шаблонов для стримов
5. **Записи** - База видео-записей с аналитикой
6. **Помощь** - FAQ и поддержка

### Логика монетизации

Раздел "Монетизация" разблокируется после:
- 4 успешных запусков стрима (День 4)
- Наличия активных прокси

### Форматы прокси

Поддерживаемые форматы в .txt файле:

```
host:port
user:pass@host:port
host:port:user:pass
```

## Разработка

### Пересборка frontend

```bash
./scripts/build-frontend.sh
```

### Остановка

```bash
docker compose down
```

### Очистка

```bash
docker compose down -v  # Удалить volumes (включая БД)
```

### Логи

```bash
docker compose logs -f backend
docker compose logs -f nginx
```

## Деплой на продакшен

См. подробную инструкцию: [docs/deploy-hestia.md](docs/deploy-hestia.md)

### Краткая версия

1. Настройте vhost в Hestia CP (document root = `public_html/public`)
2. Загрузите backend на сервер
3. Установите зависимости: `composer install --no-dev --optimize-autoloader`
4. Настройте `.env` (база данных, URL)
5. Запустите миграции: `php artisan migrate --force`
6. Соберите frontend локально и загрузите в `public/app/`
7. Настройте права: `chmod -R 755 storage bootstrap/cache`
8. Кэшируйте конфиги: `php artisan config:cache && php artisan route:cache`

## База данных

### Таблицы

- `users` - Пользователи (с полями is_admin, is_banned)
- `proxies` - Прокси пользователей
- `stream_runs` - История запусков стримов
- `notifications` - Уведомления
- `social_links` - Ссылки на соцсети
- `videos` - Видео записи
- `templates` - Шаблоны для стримов
- `help_articles` - Статьи помощи
- `progress_steps` - Шаги прогресса пользователей

## Принципы разработки

- **KISS** (Keep It Simple, Stupid) - простота превыше всего
- **DRY** (Don't Repeat Yourself) - без дублирования кода
- Читаемый код без "магии"
- Минимум зависимостей
- Прямые пути без преждевременной абстракции

## Лицензия

Частный проект.
