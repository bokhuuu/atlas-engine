# 🗺️ Atlas - Multi-Market Real Estate Intelligence Platform

Atlas is a multi-market real estate intelligence platform built for Colliers International. It scrapes listings across Caucasus markets (Yerevan, Baku, Tbilisi), cleans and deduplicates the data and delivers: a public market snapshot, an internal dashboard, an AI assistant, AI-generated summaries, a filterable Data Hub and on-demand Excel exports.

One platform, multiple markets. Switching between Yerevan and Baku changes which data you see - the platform, AI and exports all scope automatically.

**Production:** `https://45-32-154-101.nip.io`

---

## What's Live

| Screen                     | URL                           | Description                                                                                                                                                                                                                  |
| -------------------------- | ----------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Screen 0 - Public Snapshot | `/yerevan` `/baku` `/tbilisi` | Public market snapshot per market. No login. Median price/sqm, listing counts, animated count-up, live badge.                                                                                                                |
| Screen 1 - Dashboard       | `/yerevan/admin/dashboard`    | Scraper control panel scoped to market. Start scrapes, live status, listing volume trend chart, Excel exports.                                                                                                               |
| Screen 2 - Market Stats    | `/yerevan/admin/market-stats` | District-level price breakdown by category. Median + average price/sqm, heat-shaded ranking, price reductions, disappeared and stale counts, commercial yield, AI summary.                                                   |
| Screen 3 - Data Hub        | `/yerevan/admin/data-hub`     | Filterable listing explorer scoped to market. Filter by category, district, status, rooms, price, area, agency/owner, posted date, collected date. Live count, race-condition protection. Download filtered Excel instantly. |
| Screen 4 - AI Assistant    | `/yerevan/admin/assistant`    | Chat interface powered by Claude Haiku. Market-aware - answers are scoped to the selected market. 8 callable tools, persistent per-user history per market.                                                                  |

Old `/admin/*` URLs redirect automatically to `/yerevan/admin/*`.

---

## Tech Stack

| Layer      | Choice                                                                                 |
| ---------- | -------------------------------------------------------------------------------------- |
| Backend    | Laravel 12 (PHP 8.4), layered architecture (Controller → Service → Repository → Model) |
| Frontend   | Inertia.js + React + TypeScript, Tailwind, shadcn/Radix UI                             |
| Auth       | Fortify + Sanctum, Spatie roles/permissions                                            |
| AI         | Prism → OpenRouter, LarAgent, `anthropic/claude-haiku-4-5`                             |
| Database   | PostgreSQL 16 + pgvector                                                               |
| Scraping   | Laravel Dusk + ChromeDriver (list.am is JS-rendered)                                   |
| Queue      | Redis + Laravel Horizon                                                                |
| Storage    | Backblaze B2 (screenshots), rclone (DB backups)                                        |
| Monitoring | Sentry, UptimeRobot, Telegram alerts                                                   |
| DevOps     | Docker, Caddy (HTTPS), Vultr VPS, GitHub Actions CI/CD                                 |

---

## Local Setup

```bash
docker compose up -d --build
docker compose exec app bash scripts/setup.sh
npm install && npm run build
```

Then visit `http://localhost:8080/yerevan`.

The setup script handles environment file creation, app key generation, migrations, database seeding and storage linking in one pass.

**Default credentials (change before any client-facing use):**

- Email: `zura.coding@gmail.com`
- Password: `AtlasAtlas2026!`

---

## Production Deploy

Every push to `main` auto-deploys via GitHub Actions CI/CD:

1. Tests run against PostgreSQL 16
2. Frontend assets are built
3. Server is updated automatically via `scripts/deploy.sh`

No manual steps needed for code or frontend changes.

**Manual deploy** (if needed): SSH as `deploy` user and run `bash scripts/deploy.sh` from `/var/www/atlas`.

**Do not deploy while a scrape is in progress** - deploy restarts Horizon and kills running jobs.

**Before merging to main:** always run `docker compose exec app php artisan route:cache` locally. Route caching catches duplicate name conflicts that tests miss.

---

## Markets

| Market  | Slug    | Flag | Data Source       |
| ------- | ------- | ---- | ----------------- |
| Yerevan | yerevan | 🇦🇲   | list.am           |
| Baku    | baku    | 🇦🇿   | bina.az           |
| Tbilisi | tbilisi | 🇬🇪   | myhome.ge + ss.ge |

Adding a new market: add entry to `config/scraper.php` markets array + create scraper profiles. No other code changes needed.

---

## Data Sources (Yerevan)

All Yerevan data is scraped from [list.am](https://list.am) across 7 property categories:

| Category                        | list.am ID | Deal Type |
| ------------------------------- | ---------- | --------- |
| Apartments for Sale             | 60         | Sale      |
| Long Term Apartment Rentals     | 56         | Rent      |
| Houses for Sale                 | 1386       | Sale      |
| Commercial Real Estate for Sale | 199        | Sale      |
| Commercial Real Estate for Rent | 59         | Rent      |
| Land for Sale                   | 55         | Sale      |
| New Development Apartments      | 268        | Sale      |

---

## Listing Lifecycle

Every listing is tracked through its full lifecycle:

| Field             | Description                                              |
| ----------------- | -------------------------------------------------------- |
| `scraped_at`      | When the listing was first collected                     |
| `last_seen_at`    | Last time confirmed live via HTTP check                  |
| `last_checked_at` | Last time check-listings ran against it                  |
| `disappeared_at`  | When first detected as gone (404)                        |
| `screenshot_url`  | B2 path of detail page screenshot (Thread 14)            |
| `days_on_market`  | Computed at export time from scraped_at → disappeared_at |
| `status`          | Active / Stale (90+ days) / Disappeared                  |

The `atlas:check-listings` command checks 5% of active listings per run. Currently disabled - requires residential proxies (list.am returns 403 to non-browser requests from server IPs).

---

## Scheduled Jobs

| Command                     | Schedule                                     | Description                             |
| --------------------------- | -------------------------------------------- | --------------------------------------- |
| `scraper:run [category]`    | Mon/Wed/Fri 05:00 Tbilisi                    | Scrape one category from list.am        |
| `atlas:check-listings`      | Commented out (requires residential proxies) | HTTP GET check for disappeared listings |
| `atlas:capture-screenshots` | Commented out (deferred to Thread 14)        | Screenshot new listings to B2           |

---

## API

All authenticated endpoints require a Sanctum Bearer token. Create one at `/yerevan/admin/tokens`.

A full Postman collection is available at [`postman/atlas.postman_collection.json`](postman/atlas.postman_collection.json). Import it, set `base_url` and `token` variables and every endpoint is ready to use.

**Market-aware endpoints** - pass `?market=yerevan` (or `baku`/`tbilisi`) to scope results:

```
GET  /api/v1/public/snapshot?market=yerevan     - public market snapshot (no auth)
GET  /api/v1/scrape/status-all?market=yerevan   - live status for market categories
POST /api/v1/scrape/start-all?market=yerevan    - start scraping market categories
GET  /api/v1/scrape/trend?source=...            - listing volume over time
GET  /api/v1/listings?source=...                - paginated listings with filters
GET  /api/v1/listings/stats?source=...          - district price breakdown
GET  /api/v1/listings/filter?market=yerevan     - Data Hub filter
GET  /api/v1/listings/summary?source=...        - AI market summary
GET  /api/v1/listings/yield?pair=...&market=yerevan  - rental yield per district
GET  /api/v1/exports/{source}                   - Excel export, one category
GET  /api/v1/exports?market=yerevan             - Excel exports, all market categories (zip)
GET  /api/v1/exports/filtered?source=...        - Excel export with filters applied
POST /api/v1/ai/chat                            - AI assistant chat (market passed in body)
GET  /api/v1/ai/history?market=yerevan          - fetch AI chat history for current user + market
GET  /api/v1/ai/usage                           - current user's token usage and cost
GET  /api/v1/ai/usage/system                    - system-wide token usage, admin only
```

---

## Roles

| Role          | Access                                                             |
| ------------- | ------------------------------------------------------------------ |
| `super_admin` | Everything                                                         |
| `admin`       | All product screens + Users + Settings + Activity Log + API Tokens |
| `editor`      | Intelligence screens only (Dashboard, Market Stats, AI Assistant)  |
| `viewer`      | 403 - no access                                                    |

---

## License

Private repository. Not for public distribution.
