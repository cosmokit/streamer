# Деплой stream.eeee.baby

## 1. SSH на сервер

```bash
ssh user@stream.eeee.baby
cd /home/username/web/stream.eeee.baby
```

## 2. Клонируй

```bash
git clone git@github.com:cosmokit/streamer.git
cd streamer
```

## 3. БД в Hestia CP

DB → Add Database:
- Имя: `streamer_db`
- Юзер: `streamer_user`
- Пароль: генерируй

## 4. Backend

```bash
cd backend
composer install --no-dev
cp .env.example .env
nano .env
```

В `.env`:
```
APP_URL=https://stream.eeee.baby
DB_DATABASE=streamer_db
DB_USERNAME=streamer_user
DB_PASSWORD=твой_пароль
```

```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan optimize
chmod -R 775 storage bootstrap/cache
```

## 5. Frontend

```bash
cd ../frontend
npm install
npm run build
cp -r dist/* ../backend/public/app/
```

## 6. Симлинк

```bash
cd /home/username/web/stream.eeee.baby
rm -rf public_html
ln -s streamer/backend/public public_html
```

## 7. Nginx (если нужно)

```bash
sudo nano /etc/nginx/conf.d/stream.eeee.baby.conf
```

Добавь:
```nginx
location /app/ { try_files $uri $uri/ /app/index.html; }
location = / { try_files /app/index.html =404; }
```

```bash
sudo nginx -t && sudo systemctl reload nginx
```

## Готово

- https://stream.eeee.baby
- https://stream.eeee.baby/admin
- admin@streamer.local / password

---

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
webhook -hooks ~/webhook.json -port 9000
# Ctrl+A, D
```

**GitHub:** Settings → Webhooks → Add
- URL: `http://stream.eeee.baby:9000/hooks/deploy`

**Локально:**
```bash
git push origin main
# → автоматически деплоится
```
