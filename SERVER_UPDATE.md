# Инструкция по обновлению на сервере (Hestia CP)

## 1. Подключение к серверу
```bash
ssh user@your-server.com
cd /home/username/web/yourdomain.com/public_html
```

## 2. Получение обновлений
```bash
git pull origin main
```

## 3. Установка зависимостей

### Backend (Laravel)
```bash
cd backend
composer install --no-dev --optimize-autoloader
```

### Frontend (React)
```bash
cd ../frontend
npm install
npm run build
```

## 4. Копирование frontend в public
```bash
cd ..
rm -rf backend/public/app/*
cp -r frontend/dist/* backend/public/app/
```

## 5. Применение миграций
```bash
cd backend
php artisan migrate --force
```

## 6. Очистка кешей
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## 7. Установка прав
```bash
cd ..
chmod -R 755 backend/storage backend/bootstrap/cache
chown -R www-data:www-data backend/storage backend/bootstrap/cache
```

## 8. Настройка .env (если первый раз)
```bash
cd backend
cp .env.example .env
nano .env
```

**Обязательные параметры:**
```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password

SESSION_DRIVER=database
SESSION_DOMAIN=yourdomain.com
```

## 9. Первый запуск (только при начальной установке)
```bash
php artisan key:generate
php artisan migrate:fresh --seed --force
```

## 10. Настройка Nezhna API (через админку)
1. Войдите в админ-панель: `https://yourdomain.com/admin`
2. Перейдите в "Настройки"
3. Вставьте ваш Nezhna API ключ
4. Сохраните

---

## Быстрое обновление (только код)
```bash
cd /home/username/web/yourdomain.com/public_html
git pull origin main
cd frontend && npm install && npm run build && cd ..
rm -rf backend/public/app/* && cp -r frontend/dist/* backend/public/app/
cd backend && composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear && php artisan route:clear && php artisan view:clear
```

---

## Проверка работоспособности
- Откройте сайт в браузере
- Проверьте логин пользователя
- Проверьте админ-панель
- Проверьте загрузку прокси
- Проверьте запуск стрима (если настроен Nezhna API)

## Важно!
- Всегда делайте backup базы данных перед обновлением
- Проверяйте права доступа к папкам `storage` и `bootstrap/cache`
- После обновления очищайте кеш браузера (Ctrl+Shift+R)
