# SIBIMA

**Sistem Informasi Booking Bimbingan Mahasiswa** — a Laravel 12 web app for booking thesis-supervision (bimbingan skripsi) slots. Replaces ad-hoc WhatsApp scheduling with a structured workflow: kaprodi assigns mahasiswa to dosen, dosen publishes available slots, mahasiswa books, dosen approves, both record what happened.

Built as the UAS project for Web Framework Design and Application, **Kelompok 10** (Kevin · Fabio · Anisa Rafa · Christian · Joseph).

---

## Quick start (Docker)

The only thing you need installed is **Docker Desktop**. Then:

```powershell
# 1. Clone + cd into the Project folder
cd Project

# 2. Copy the env template
copy .env.example .env

# 3. Generate APP_KEY directly into .env (no PHP needed)
$bytes = New-Object byte[] 32
[System.Security.Cryptography.RandomNumberGenerator]::Create().GetBytes($bytes)
$key = "base64:" + [Convert]::ToBase64String($bytes)
(Get-Content .env) -replace "^APP_KEY=.*", "APP_KEY=$key" | Set-Content .env

# 4. Build + start everything
docker compose up -d --build

# 5. Open in browser
start http://localhost:8080
```

First build takes 3–5 minutes (pulls PHP, MySQL, Nginx, installs Composer + npm deps). Subsequent starts are seconds.

The `entrypoint.sh` script inside the `app` container automatically waits for MySQL, runs migrations, and seeds the test accounts.

---

## Test accounts

All use password `password`:

| Email | Role |
|---|---|
| `kaprodi@petra.ac.id` | Kaprodi (assigns mahasiswa → dosen, sets quota, sees all bookings) |
| `dosen@petra.ac.id` | Dosen (publishes slots, approves bookings, writes catatan) |
| `mahasiswa@petra.ac.id` | Mahasiswa (books slots from their assigned pembimbing) |

The seeded mahasiswa is pre-assigned to the seeded dosen, with 3 sample slots ready to book.

---

## Local development (without Docker)

If you have PHP 8.4 + Composer + Node installed locally:

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Then open `http://127.0.0.1:8000`. Uses SQLite (`database/database.sqlite`) for local dev.

---

## Common Docker commands

```bash
# View logs
docker compose logs app --tail 50
docker compose logs web --tail 30

# Restart everything
docker compose restart

# Stop everything (keeps data)
docker compose down

# Wipe everything including the database
docker compose down -v

# Rebuild after code changes
docker compose down -v && docker compose up -d --build

# Run an artisan command inside the running container
docker compose exec app php artisan migrate:status
docker compose exec app php artisan tinker
```

---

## Tech stack

- **Backend**: Laravel 12, PHP 8.4
- **Auth**: Laravel Breeze (web), Laravel Sanctum (API tokens)
- **Frontend**: Blade, Tailwind CSS, Alpine.js, built with Vite
- **Database**: MySQL 8 (Docker) or SQLite (local dev)
- **Web server**: Nginx (in Docker)
- **Deployment**: Docker Compose — three containers (`app` PHP-FPM, `web` Nginx, `db` MySQL)

---

## API

Six REST endpoints under `/api`, authenticated via Sanctum bearer tokens:

| Method | Endpoint | Purpose |
|---|---|---|
| POST | `/api/login` | Exchange email+password for a token |
| GET | `/api/dosen` | List dosen (filtered to user's pembimbing for mahasiswa) |
| GET | `/api/slots?dosen_id=X` | List available slots |
| POST | `/api/bookings` | Create a booking |
| GET | `/api/bookings/me` | Get current user's bookings |
| PATCH | `/api/bookings/{id}/approve` | Dosen approves a booking |

Example:
```bash
TOKEN=$(curl -s -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"email":"mahasiswa@petra.ac.id","password":"password"}' | jq -r '.token')

curl http://localhost:8080/api/dosen -H "Authorization: Bearer $TOKEN" -H "Accept: application/json"
```

---

## Documentation

Detailed walkthroughs live in `../docs/`:

- **APPLICATION.md** — full technical tour of the codebase (for someone with no Laravel background)
- **DATABASE.md** — ER diagram + schema reference
- **KEVIN.md / FABIO.md / ANISA.md / CHRISTIAN.md / JOSEPH.md** — per-team-member file ownership and demo notes

---

## Project structure (high level)

```
Project/
├── app/
│   ├── Http/Controllers/      ← Controllers (web + Api/)
│   ├── Http/Middleware/       ← CheckRole.php
│   └── Models/                ← User, Slot, Booking, CatatanBimbingan
├── database/
│   ├── migrations/            ← Table schemas
│   └── seeders/               ← Test accounts + sample slots
├── docker/
│   ├── nginx.conf
│   └── entrypoint.sh
├── public/                    ← Web-served files (logo, background, Vite build output)
├── resources/views/           ← Blade templates
│   ├── auth/                  ← Login, register
│   ├── slots/                 ← Dosen slot CRUD
│   ├── bookings/              ← Booking flow (mahasiswa + dosen views)
│   ├── catatan/               ← Catatan bimbingan form
│   ├── dosen/                 ← Mahasiswa browses pembimbing
│   ├── kaprodi/               ← Kaprodi assignment + global view
│   └── layouts/               ← Master layout + navigation
├── routes/
│   ├── web.php
│   ├── api.php
│   └── auth.php
├── Dockerfile
└── docker-compose.yml
```

---

## License

Built for educational purposes as part of Petra Christian University's Web Framework Design course, June 2026.
