# Деплой stream.eeee.baby

## На сервере

```bash
ssh user@stream.eeee.baby

# Найди путь к домену (обычно /home/username/web/stream.eeee.baby)
cd /home/username/web/stream.eeee.baby

# Клонируй
git clone git@github.com:cosmokit/streamer.git
cd streamer

# БД в Hestia CP: DB → Add Database
# Имя: streamer_db
# Юзер: streamer_user
# Пароль: сгенерируй

# Backend
cd backend
composer install --no-dev
cp .env.example .env
nano .env
# APP_URL=https://stream.eeee.baby
# DB_DATABASE=streamer_db
# DB_USERNAME=streamer_user
# DB_PASSWORD=твой_пароль

php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan optimize
chmod -R 775 storage bootstrap/cache

# Frontend
cd ../frontend
npm install
npm run build
cp -r dist/* ../backend/public/app/

# Симлинк
cd /home/username/web/stream.eeee.baby
rm -rf public_html
ln -s streamer/backend/public public_html

# Nginx (если нужен кастомный конфиг)
sudo nano /etc/nginx/conf.d/stream.eeee.baby.conf
```

Добавь в конфиг:
```nginx
location /app/ { try_files $uri $uri/ /app/index.html; }
location = / { try_files /app/index.html =404; }
```

```bash
sudo nginx -t && sudo systemctl reload nginx
```

## Автодеплой

```bash
nano ~/deploy.sh
```

```bash
#!/bin/bash
cd /home/username/web/stream.eeee.baby/streamer
git pull origin main
cd backend && composer install --no-dev && php artisan migrate --force && php artisan optimize
cd ../frontend && npm install && npm run build && cp -r dist/* ../backend/public/app/
chmod -R 775 ../backend/storage
```

```bash
chmod +x ~/deploy.sh
sudo npm install -g webhook
nano ~/webhook.json
```

```json
[{"id":"deploy","execute-command":"/home/username/deploy.sh"}]
```

```bash
screen -S webhook
webhook -hooks ~/webhook.json -port 9000 -verbose
# Ctrl+A, D
```

GitHub → Settings → Webhooks → Add:
- URL: `http://stream.eeee.baby:9000/hooks/deploy`
- push event

## Готово

- https://stream.eeee.baby
- https://stream.eeee.baby/admin
- admin@streamer.local / password
