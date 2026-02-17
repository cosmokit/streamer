# 🚀 Деплой PROFITSTREAM на Hestia CP

## 📋 Требования

- Hestia CP установлен
- PHP 8.4+ (с расширениями: mysql, mbstring, xml, bcmath, gd, zip)
- Composer 2.x
- MariaDB/MySQL 5.7+
- Git
- Node.js 20+ (для сборки frontend локально)

---

## 🎯 Пошаговая инструкция

### 1️⃣ Создание домена в Hestia CP

1. Войдите в **Hestia CP**
2. Перейдите в **WEB** → **Add Web Domain**
3. Укажите домен: `stream.eeee.baby`
4. Включите **SSL (Let's Encrypt)**
5. Создайте домен

### 2️⃣ Подключение к серверу по SSH

```bash
ssh your-user@stream.eeee.baby
# или
ssh your-user@your-server-ip
```

### 3️⃣ Подготовка структуры

```bash
# Перейдите в директорию домена
cd ~/web/stream.eeee.baby

# Удалите стандартную public_html (если нужно)
rm -rf public_html

# Клонируйте репозиторий или загрузите файлы
# Вариант 1: Git
git clone https://github.com/your-repo/streamer.git backend
mv backend public_html

# Вариант 2: Загрузка через rsync (с вашего компьютера)
rsync -avz --exclude 'node_modules' --exclude '.git' \
      backend/ your-user@stream.eeee.baby:~/web/stream.eeee.baby/public_html/
```

### 4️⃣ Установка зависимостей PHP

```bash
cd ~/web/stream.eeee.baby/public_html

# Установка composer зависимостей
composer install --no-dev --optimize-autoloader --no-interaction

# Проверка версии PHP
php -v  # Должна быть 8.4+
```

### 5️⃣ Создание базы данных в Hestia

1. В Hestia CP: **DB** → **Add Database**
2. Создайте БД:
   - **Имя:** `streamer_db`
   - **Пользователь:** `streamer_user`
   - **Пароль:** (сгенерируйте сложный)
3. Запомните данные для `.env`

### 6️⃣ Настройка .env файла

```bash
cd ~/web/stream.eeee.baby/public_html

# Создайте .env из примера
cp .env.example .env

# Отредактируйте .env
nano .env
```

**Важные параметры `.env`:**

```env
APP_NAME="PROFITSTREAM"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://stream.eeee.baby

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=streamer_db
DB_USERNAME=streamer_user
DB_PASSWORD=ваш_пароль_из_hestia

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 7️⃣ Генерация ключа и миграции

```bash
cd ~/web/stream.eeee.baby/public_html

# Генерация APP_KEY
php artisan key:generate

# Запуск миграций
php artisan migrate --force

# Заполнение демо-данными
php artisan db:seed --force
```

**Что создаст seeder:**
- Админ: `admin@streamer.local` / `password`
- User1: `user1@streamer.local` / `password` (с 70 видео, 3 стрима)
- User2: `user2@streamer.local` / `password` (пустой)
- 19 шаблонов Gaming
- 12 статей помощи

### 8️⃣ Оптимизация для продакшена

```bash
# Кеширование конфигурации
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Оптимизация autoload
composer dump-autoload --optimize
```

### 9️⃣ Права доступа

```bash
cd ~/web/stream.eeee.baby/public_html

# Установка прав на storage и cache
chmod -R 775 storage bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache

# Проверка прав
ls -la storage
ls -la bootstrap/cache
```

### 🔟 Сборка и загрузка Frontend

**На вашем локальном компьютере:**

```bash
cd /home/kit/Work/streamer/frontend

# Установка зависимостей (если еще не установлены)
npm install

# Сборка для продакшена
npm run build

# Результат будет в frontend/dist/
```

**Загрузка на сервер:**

```bash
# Вариант 1: rsync (с вашего компьютера)
rsync -avz frontend/dist/ \
      your-user@stream.eeee.baby:~/web/stream.eeee.baby/public_html/public/app/

# Вариант 2: scp (с вашего компьютера)
scp -r frontend/dist/* \
    your-user@stream.eeee.baby:~/web/stream.eeee.baby/public_html/public/app/

# Вариант 3: FTP/SFTP через FileZilla
# Загрузите содержимое frontend/dist/ в public_html/public/app/
```

**На сервере проверьте:**

```bash
ls -la ~/web/stream.eeee.baby/public_html/public/app/
# Должны быть: index.html, assets/, logo.png и т.д.
```

### 1️⃣1️⃣ Настройка Nginx в Hestia

Hestia автоматически создаст конфиг nginx. Проверьте, что **Document Root** указывает на `public_html/public`.

**Если нужно добавить правила, отредактируйте:**

```bash
# Путь к конфигу (может отличаться)
sudo nano /home/$USER/conf/web/stream.eeee.baby.nginx.conf_letsencrypt
```

**Добавьте правило для SPA (если его нет):**

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

# Для статики frontend
location /app/ {
    try_files $uri $uri/ /app/index.html;
}
```

**Перезапустите Nginx:**

```bash
sudo systemctl restart nginx
```

### 1️⃣2️⃣ Проверка работы сайта

Откройте браузер:

1. **Frontend:** `https://stream.eeee.baby/`
   - Должна открыться страница логина

2. **API:** `https://stream.eeee.baby/api/templates`
   - Должен вернуть JSON с 19 шаблонами

3. **Admin:** `https://stream.eeee.baby/admin`
   - Войдите: `admin@streamer.local` / `password`

---

## 🔐 Доступы после установки

### Пользовательская часть

| Email | Пароль | Роль | Данные |
|-------|--------|------|--------|
| `user1@streamer.local` | `password` | User | 70 видео, 3 стрима |
| `user2@streamer.local` | `password` | User | Пустой аккаунт |

### Админ-панель

**URL:** `https://stream.eeee.baby/admin`

| Email | Пароль | Роль |
|-------|--------|------|
| `admin@streamer.local` | `password` | Admin |

**Функции админки:**
- 📊 Дашборд со статистикой
- 👥 Управление пользователями (редактирование, блокировка, удаление)
- 🎨 Управление шаблонами (CRUD)
- ❓ Управление статьями помощи (CRUD)

---

## 🔄 Обновление приложения

При обновлении кода:

```bash
cd ~/web/stream.eeee.baby/public_html

# Обновление backend
git pull origin main  # если используете git
composer install --no-dev --optimize-autoloader

# Миграции
php artisan migrate --force

# Очистка кешей
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Новые кеши
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Права
chmod -R 775 storage bootstrap/cache
```

**Обновление frontend:**

```bash
# На локальном компьютере
cd frontend
npm run build

# Загрузите dist/ на сервер в public/app/
rsync -avz frontend/dist/ \
      your-user@stream.eeee.baby:~/web/stream.eeee.baby/public_html/public/app/
```

---

## 🐛 Troubleshooting

### 500 Internal Server Error

**Причины:**
- Неправильные права на `storage/` и `bootstrap/cache/`
- Неверная конфигурация `.env`
- Отсутствует `APP_KEY`

**Решение:**
```bash
# Проверьте логи Laravel
tail -f storage/logs/laravel.log

# Проверьте логи Nginx
sudo tail -f /var/log/nginx/error.log

# Установите права
chmod -R 775 storage bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache

# Сгенерируйте ключ (если отсутствует)
php artisan key:generate
```

### API не работает

**Проверка:**
```bash
# Тест API
curl https://stream.eeee.baby/api/templates

# Проверьте маршруты
php artisan route:list | grep api
```

**Возможные причины:**
- Неверная конфигурация nginx
- CORS настройки

### Frontend не загружается

**Проверка:**
```bash
# Убедитесь что файлы скопированы
ls -la ~/web/stream.eeee.baby/public_html/public/app/

# Должны быть: index.html, assets/, и т.д.
```

**Проверьте пути в HTML:**
```bash
# Откройте index.html
cat public/app/index.html | grep 'src='

# Должно быть: src="/app/assets/..."
```

### Админка не открывается

**Проверка:**
```bash
# Проверьте маршруты
php artisan route:list | grep admin

# Проверьте middleware
php artisan route:list --name=admin
```

**Убедитесь что:**
1. Залогинены как админ (`is_admin = 1`)
2. Middleware `admin` зарегистрирован в `bootstrap/app.php`

### База данных не подключается

**Проверка:**
```bash
# Проверьте подключение
php artisan tinker
>>> DB::connection()->getPdo();

# Проверьте настройки в Hestia
# DB -> Посмотрите имя БД, пользователя, хост
```

### Ошибка "Class not found"

**Решение:**
```bash
# Очистите autoload
composer dump-autoload --optimize

# Очистите кеши
php artisan config:clear
php artisan cache:clear
```

---

## 📊 Проверочный чеклист

После деплоя проверьте:

- [ ] Сайт открывается по HTTPS (SSL работает)
- [ ] Frontend загружается (страница логина)
- [ ] API работает (`/api/templates` возвращает JSON)
- [ ] Админка открывается (`/admin`)
- [ ] Можно войти как user1
- [ ] Можно войти в админку
- [ ] База данных заполнена (19 шаблонов, 70 видео)
- [ ] Мобильная версия работает
- [ ] Нет ошибок 500 в логах
- [ ] Нет ошибок 404 для статики

---

## 🔒 Безопасность (после деплоя)

**Обязательно:**

1. **Смените пароли демо-пользователей:**
```bash
php artisan tinker
>>> $admin = User::where('email', 'admin@streamer.local')->first();
>>> $admin->password = Hash::make('новый_сложный_пароль');
>>> $admin->save();
```

2. **Отключите debug mode:**
```env
APP_DEBUG=false
```

3. **Настройте firewall:**
```bash
# Только SSH, HTTP, HTTPS
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

4. **Регулярные бэкапы:**
- В Hestia: **BACKUP** → настройте автоматические бэкапы

---

## 📞 Поддержка

Если возникли проблемы:
1. Проверьте логи: `storage/logs/laravel.log`
2. Проверьте nginx логи: `/var/log/nginx/error.log`
3. Проверьте права: `ls -la storage`

**Успешного деплоя! 🚀**
