# Workflow

## Обновление frontend из gamer-glow-lab

```bash
./scripts/update-frontend.sh
# Проверь кастомизации (vite.config.ts, App.tsx, LoginPage.tsx, DashboardLayout.tsx)
./scripts/build-frontend.sh
git add frontend/ backend/public/app/
git commit -m "Update: Frontend from gamer-glow-lab"
git push origin main
```

## На сервере

```bash
cd /home/uan/web/stream.eeee.baby/streamer
git pull origin main
cd backend && composer install --no-dev && php artisan optimize
cd ../frontend && npm install && npm run build && cp -r dist/* ../backend/public/app/
```
