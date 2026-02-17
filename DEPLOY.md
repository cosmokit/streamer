# 🚀 Деплой на Hestia CP

## 1️⃣ На сервере (один раз)

```bash
# Подключись к серверу
ssh user@server.com

# Установи зависимости
sudo apt update
sudo apt install -y php8.4-{fpm,cli,mysql,mbstring,xml,curl,zip,gd,bcmath} composer nodejs npm git

# Создай домен в Hestia CP
# Web → Add Domain → твой-домен.com → Create

# Перейди в папку сайта
cd /home/username/web/твой-домен.com

# Клонируй репозиторий
git clone git@github.com:cosmokit/streamer.git
cd streamer

# Создай БД в Hestia CP
# DB → Add Database → streamer_db / streamer_user / пароль

# Настрой backend
cd backend
composer install --no-dev --optimize-autoloader
cp .env.example .env
nano .env  # Настрой DB_* и APP_URL

php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan optimize

chmod -R 775 storage bootstrap/cache
chown -R username:username storage bootstrap/cache

# Собери frontend
cd ../frontend
npm install
npm run build
cp -r dist/* ../backend/public/app/

# Создай симлинк
cd /home/username/web/твой-домен.com
rm -rf public_html
ln -s streamer/backend/public public_html
```

## 2️⃣ Nginx конфиг

`/etc/nginx/conf.d/твой-домен.com.conf`:

```nginx
server {
    listen 443 ssl http2;
    server_name твой-домен.com;
    
    root /home/username/web/твой-домен.com/streamer/backend/public;
    index index.php index.html;
    
    # Frontend
    location /app/ {
        try_files $uri $uri/ /app/index.html;
    }
    
    # API
    location /api/ {
        try_files $uri /index.php?$query_string;
    }
    
    # Admin & Auth
    location ~ ^/(admin|login|logout|register|password) {
        try_files $uri /index.php?$query_string;
    }
    
    # Root → React
    location = / {
        try_files /app/index.html =404;
    }
    
    # PHP
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

```bash
sudo nginx -t && sudo systemctl reload nginx
```

---

## 3️⃣ Автодеплой (настройка один раз)

### На сервере:

```bash
# Создай скрипт деплоя
nano /home/username/deploy.sh
```

```bash
#!/bin/bash
cd /home/username/web/твой-домен.com/streamer

# Pull изменений
git pull origin main

# Backend
cd backend
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize

# Frontend
cd ../frontend
npm install
npm run build
cp -r dist/* ../backend/public/app/

# Права
chmod -R 775 ../backend/storage ../backend/bootstrap/cache

echo "✅ Deployed at $(date)"
```

```bash
chmod +x /home/username/deploy.sh
```

### Webhook на сервере:

```bash
# Установи webhook listener
sudo npm install -g webhook

# Создай конфиг
nano /home/username/webhook.json
```

```json
[
  {
    "id": "deploy-streamer",
    "execute-command": "/home/username/deploy.sh",
    "command-working-directory": "/home/username",
    "response-message": "Deploying...",
    "trigger-rule": {
      "match": {
        "type": "payload-hash-sha256",
        "secret": "ТвойСекретныйКлюч123",
        "parameter": {
          "source": "header",
          "name": "X-Hub-Signature-256"
        }
      }
    }
  }
]
```

```bash
# Запусти webhook (в screen или systemd)
screen -S webhook
webhook -hooks /home/username/webhook.json -port 9000 -verbose
# Ctrl+A, D (detach)
```

### На GitHub:

1. Открой репозиторий → **Settings** → **Webhooks** → **Add webhook**
2. **Payload URL**: `http://твой-домен.com:9000/hooks/deploy-streamer`
3. **Content type**: `application/json`
4. **Secret**: `ТвойСекретныйКлюч123`
5. **Events**: Just the `push` event
6. **Active**: ✓
7. **Add webhook**

---

## 4️⃣ Workflow

### Работа локально:

```bash
# Делаешь изменения
git add .
git commit -m "Update feature"
git push origin main

# GitHub webhook автоматически деплоит на сервер! 🎉
```

### Проверка:

- **Сайт**: https://твой-домен.com
- **Админка**: https://твой-домен.com/admin
- **Логи деплоя**: `tail -f /var/log/webhook.log`

---

## 5️⃣ Логины

- **Admin**: admin@streamer.local / password
- **User**: user1@streamer.local / password

**⚠️ СМЕНИ ПАРОЛИ В ПРОДАКШЕНЕ!**

---

## Troubleshooting

```bash
# Логи Laravel
tail -f /home/username/web/твой-домен.com/streamer/backend/storage/logs/laravel.log

# Логи Nginx
tail -f /var/log/nginx/error.log

# Логи PHP
tail -f /var/log/php8.4-fpm.log

# Права
sudo chown -R username:www-data /home/username/web/твой-домен.com/streamer/backend/storage
sudo chmod -R 775 /home/username/web/твой-домен.com/streamer/backend/storage
```
