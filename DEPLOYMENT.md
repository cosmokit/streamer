# 🚀 Инструкция по деплою на сервер

## Краткая версия (для опытных):

```bash
# На сервере
cd ~/web/stream.eeee.baby/public_html
git pull origin main
cd backend
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Полная версия (пошагово):

### 1. Подключись к серверу

```bash
ssh uan@vmi2835920.contaboserver.net
cd ~/web/stream.eeee.baby/public_html
```

### 2. Создай бэкап (на всякий случай)

```bash
# Бэкап базы данных
mysqldump -u DB_USER -p DB_NAME > backup_$(date +%Y%m%d_%H%M%S).sql

# Бэкап файлов
cd ..
tar -czf backup_$(date +%Y%m%d_%H%M%S).tar.gz public_html/
cd public_html
```

### 3. Получи изменения из Git

```bash
# Проверь текущий статус
git status

# Если есть локальные изменения (не должно быть):
git stash

# Получи изменения
git pull origin main
```

### 4. Обнови зависимости Laravel

```bash
cd backend
composer install --no-dev --optimize-autoloader
```

### 5. Выполни миграции базы данных

```bash
# Обязательно с флагом --force на продакшене
php artisan migrate --force
```

### 6. Обнови .env (если нужно)

Проверь что в `backend/.env` установлен API ключ Nezhna:

```bash
cat backend/.env | grep NEZHNA
```

Если нет, добавь:

```bash
echo "NEZHNA_API_KEY=557a6f8fbf72f23d658efc58badbb5e8f91ede79b285e644d37c29ff44ad9316" >> backend/.env
```

### 7. Очисти и обнови кеши Laravel

```bash
# Очисти старые кеши
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Создай новые кеши (для производительности)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8. Проверь права доступа

```bash
# Убедись что Laravel может писать в storage и cache
chmod -R 775 storage bootstrap/cache
chown -R uan:www-data storage bootstrap/cache
```

### 9. Проверь фронтенд

Фронтенд уже собран и находится в `backend/public/app/`. Проверь:

```bash
ls -la backend/public/app/
```

Должны быть файлы:
- index.html
- assets/index-*.js
- assets/index-*.css
- assets/logo-*.png

### 10. Перезапусти PHP-FPM (если нужно)

```bash
# В Hestia CP это обычно происходит автоматически
# Но можно перезапустить вручную через панель
```

---

## 🔍 Проверка работоспособности:

### 1. Проверь фронтенд

Открой в браузере:
- https://stream.eeee.baby/

Должна открыться страница входа/регистрации.

### 2. Проверь API

```bash
curl https://stream.eeee.baby/api/me
```

Должен вернуть 401 (это правильно, т.к. не авторизован).

### 3. Проверь админку

Открой:
- https://stream.eeee.baby/admin/login

Войди:
- Email: admin@example.com
- Password: admin123

### 4. Проверь логи (если есть ошибки)

```bash
tail -f backend/storage/logs/laravel.log
```

---

## 🐛 Если что-то не работает:

### Ошибка 500:

```bash
# Проверь права
chmod -R 775 storage bootstrap/cache

# Проверь логи
tail -50 backend/storage/logs/laravel.log
```

### Ошибка 404 на фронтенде:

```bash
# Проверь что файлы на месте
ls -la backend/public/app/

# Проверь что .htaccess правильный
cat backend/public/.htaccess
```

### Ошибка БД:

```bash
# Проверь подключение
php artisan db:show

# Проверь миграции
php artisan migrate:status
```

### CSS/JS не загружаются:

```bash
# Проверь что Nginx/Apache правильно настроен
# В Hestia CP обычно всё настроено автоматически

# Очисти кеш браузера (Ctrl+Shift+R)
```

---

## 📝 Что изменилось в этом деплое:

### 1. Backend:
- ✅ Интеграция с Nezhna API
- ✅ Управление стримами (запуск/остановка)
- ✅ Проверка 65 прокси перед 4-м стримом
- ✅ Генерация пользователей (вместо создания)
- ✅ Генерация прокси с количеством и статусом
- ✅ Импersonation (вход под пользователем)
- ✅ Фильтр по дням стримов
- ✅ Исправлены все баги с прогрессом и прокси

### 2. Frontend:
- ✅ Убрано поле Email из профиля
- ✅ Переименовано Name → Username
- ✅ Кнопка "Запустить стрим" / "Завершить стрим"
- ✅ Отображение "День X из 4"
- ✅ Попап проверки 65 прокси
- ✅ Блок "Подписка LITE" + кнопка PRO
- ✅ Кнопка "Вернуться в админку" при impersonation

### 3. База данных:
- ✅ Новые миграции для управления стримами
- ✅ Связь пользователей с активными стримами

---

## 🎯 Checklist после деплоя:

- [ ] Git pull выполнен
- [ ] Composer install выполнен
- [ ] Миграции запущены
- [ ] Кеши обновлены
- [ ] NEZHNA_API_KEY добавлен в .env
- [ ] Фронтенд открывается
- [ ] Админка открывается
- [ ] Можно войти и зарегистрироваться
- [ ] Все новые функции работают

---

## 📞 Контакты для помощи:

Если возникли проблемы - напиши в Telegram: @chillkiller_v

**УСПЕШНОГО ДЕПЛОЯ!** 🚀
