#!/bin/bash
set -e

echo "🔄 Обновление frontend из gamer-glow-lab..."

cd "$(dirname "$0")/.."

# Подтягиваем изменения
cd frontend
git init > /dev/null 2>&1
git remote add glow git@github.com:cosmokit/gamer-glow-lab.git 2>/dev/null || true
git fetch glow
git reset --hard glow/main
rm -rf .git

echo "✅ Frontend обновлен!"
echo ""
echo "⚠️  Проверь кастомизации:"
echo "  - vite.config.ts (base: '/app/')"
echo "  - src/App.tsx (ProtectedRoute)"
echo "  - src/pages/LoginPage.tsx (localStorage)"
echo "  - src/components/DashboardLayout.tsx (logout)"
echo ""
echo "Собери и закоммить:"
echo "  ./scripts/build-frontend.sh"
echo "  git add frontend/ backend/public/app/"
echo "  git commit -m 'Update: Frontend from gamer-glow-lab'"
echo "  git push origin main"
