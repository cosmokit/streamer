# 🎲 Генератор реалистичных прокси

## Где находится

**Админ-панель** → Пользователи → ✏️ Редактировать → **Генератор прокси** (внизу страницы)

## Что делает

При нажатии кнопки **"Создать и скачать прокси"**:
1. Генерирует **11 уникальных прокси**
2. Автоматически скачивает файл: `proxies_user_{id}_{timestamp}.txt`
3. Формат: `IP:PORT:USERNAME:PASSWORD`

## Реалистичные данные

### 🌍 IP адреса (из 18 популярных дата-центров)

```
45.138.74.x     - Германия (Hetzner)
91.203.15.x     - Нидерланды (LeaseWeb)
185.223.95.x    - США (DigitalOcean)
203.142.75.x    - Сингапур (AWS)
212.83.164.x    - Великобритания (OVH)
94.130.244.x    - Германия (Hetzner)
178.128.117.x   - США (DigitalOcean)
167.71.200.x    - Канада (DigitalOcean)
139.59.176.x    - Индия (DigitalOcean)
159.89.195.x    - США (Linode)
104.248.143.x   - США (DigitalOcean)
188.166.234.x   - Нидерланды (DigitalOcean)
165.227.84.x    - США (DigitalOcean)
46.101.203.x    - Германия (DigitalOcean)
157.245.109.x   - США (DigitalOcean)
134.209.156.x   - Канада (DigitalOcean)
161.35.219.x    - США (DigitalOcean)
68.183.205.x    - Германия (DigitalOcean)
```

### 🔌 Порты (стандартные для прокси)

```
8080  - HTTP proxy (самый популярный)
3128  - Squid proxy
1080  - SOCKS5
8888  - HTTP alternative
9090  - HTTP proxy alternative
8000  - HTTP dev proxy
3129  - Squid alternative
8081  - HTTP proxy alternative
9091  - SOCKS alternative
1081  - SOCKS alternative
10000 - Custom proxy
```

### 👤 Логины (15 реалистичных шаблонов)

```
proxyuser{1000-9999}       → proxyuser2847
user{1000-9999}_proxy      → user8471_proxy
px{1000-9999}_user         → px5674_user
stream_user{1000-9999}     → stream_user3856
dc_proxy_{1000-9999}       → dc_proxy_1923
residential{1000-9999}     → residential7294
elite_px{1000-9999}        → elite_px5632
premium{1000-9999}_access  → premium4219_access
vip_user_{1000-9999}       → vip_user_6183
mobile_px{1000-9999}       → mobile_px8184
dedicated{1000-9999}       → dedicated3741
private_user{1000-9999}    → private_user5829
fast_proxy{1000-9999}      → fast_proxy7102
secure_px{1000-9999}       → secure_px4938
business{1000-9999}_proxy  → business6274_proxy
```

### 🔐 Пароли (6 реалистичных паттернов)

**Паттерн 1:** Буква(верх) + буква(низ) + цифра + символ + слово + числа
```
Kj8#Secure234
Tn3!Access891
Bp7!Proxy956
MUh2hVpM!45
```

**Паттерн 2:** Слово + 4 цифры + символ + слово
```
Ultra4567@Pass
Mega2024#Proxy
Hyper5812!Access
Super1969@Pass
```

**Паттерн 3:** 8 случайных символов + символ + 2 цифры
```
xY4$Secure67
Qw9@Stream423
rTvB8#Access14
MUh2hVpM!45
```

**Паттерн 4:** Префикс + 3 цифры + символьный суффикс + 2 цифры
```
Pr538@sT76
Dt914$mN67
Px874!aK20
Acc452#pX89
```

**Паттерн 5:** Слово + год + символ + 2 буквы
```
Proxy2021#Pa
Secure2023@Ek
Stream2025$M
Elite2024!Xy
Fast2020!Ty
```

**Паттерн 6:** Случайная строка (8 символов) + символ + числа
```
aMnXSuGW%44
Bp7mKqRt@12
xYzAbC3d#89
```

## Пример сгенерированного файла

```txt
203.142.75.236:8888:px5674_user:MUh2hVpM!45
91.203.15.45:1080:mobile_px8184:Proxy2021#Pa
139.59.176.211:1080:elite_px8011:Secure2023@Ek
185.223.95.248:8080:mobile_px9772:Super1969@Pass
94.130.244.65:9090:residential9251:Pr538@sT76
178.128.117.73:9090:proxyuser3261:Mega2167!Proxy
203.142.75.49:8080:mobile_px2420:Mega8964$Secure
178.128.117.217:1080:premium8057_access:Dt914$mN67
203.142.75.195:9090:px4264_user:Secure2025$Rf
185.223.95.189:8888:px8968_user:Fast2020!Ty
139.59.176.76:9090:vip_user_9245:Px874!aK20
```

## Как проверить

### Вариант 1: Через браузер

1. Открой: `http://localhost:8080/admin/login`
2. Логин: `admin@streamer.local / admin123`
3. **Пользователи** → ✏️ на User 1
4. Прокрути вниз → нажми **"Создать и скачать прокси"**
5. Файл скачается: `proxies_user_2_2026-03-09_171834.txt`
6. Открой файл → увидишь 11 реалистичных прокси

### Вариант 2: Через команду (посмотреть пример)

```bash
# Просто посмотреть пример генерации
cat GENERATED_PROXIES_EXAMPLE.txt

# Загрузить этот файл как пользователь
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user1@streamer.local","password":"user12345"}' \
  -c cookies.txt

curl -X POST http://localhost:8080/api/proxies/upload \
  -b cookies.txt \
  -F "file=@GENERATED_PROXIES_EXAMPLE.txt" | jq
```

## Характеристики генератора

- ✅ **IP уникальные** - проверка дубликатов в цикле
- ✅ **18 диапазонов** - из разных стран и провайдеров
- ✅ **12 портов** - популярные прокси-порты
- ✅ **15 шаблонов логинов** - разнообразные префиксы/суффиксы
- ✅ **6 паттернов паролей** - сложные комбинации с символами
- ✅ **Совместимость** - формат `IP:PORT:USER:PASS` работает со всей системой

## Сравнение: ДО vs ПОСЛЕ

### ❌ ДО (простые):
```
203.142.75.91:8080:proxyuser1:pass123secure
185.223.95.120:3128:user2proxy:SecurePass456
91.203.15.44:8888:premium_user3:MyP@ss789
```

### ✅ ПОСЛЕ (реалистичные):
```
203.142.75.236:8888:px5674_user:MUh2hVpM!45
91.203.15.45:1080:mobile_px8184:Proxy2021#Pa
139.59.176.211:1080:elite_px8011:Secure2023@Ek
185.223.95.248:8080:mobile_px9772:Super1969@Pass
94.130.244.65:9090:residential9251:Pr538@sT76
```

**Теперь выглядит как настоящий платный прокси-лист!** 🚀
