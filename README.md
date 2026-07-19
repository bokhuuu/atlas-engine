# 🗺️ Atlas - Multi-Market Real Estate Intelligence Platform

Atlas is a multi-market real estate intelligence platform built for Colliers International. It scrapes listings across Caucasus markets (Yerevan, Baku, Tbilisi), cleans and deduplicates the data and delivers: a combined public market overview, per-market snapshots, an internal dashboard, an AI assistant with 9 tools, AI-generated summaries, a filterable Data Hub, on-demand Excel exports and property screenshots.

One platform, multiple markets. Switching between Yerevan and Baku changes which data you see - the platform, AI and exports all scope automatically. A Global view shows all markets combined.

**Production:** `https://45-32-154-101.nip.io`

---

## What's Live

| Screen                   | URL                           | Description                                                                                                                                                                                                                  |
| ------------------------ | ----------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Combined Overview        | `/`                           | Public combined view. All markets, total listings, per-market cards, animated counter. No login.                                                                                                                             |
| Per-Market Snapshot      | `/yerevan` `/baku` `/tbilisi` | Public market snapshot per market. No login. Median price/sqm, listing counts, animated count-up, live badge.                                                                                                                |
| Scraper Control          | `/yerevan/admin/dashboard`    | Scraper control panel scoped to market. Start scrapes, live status, listing volume chart, Excel exports.                                                                                                                     |
| Market Stats             | `/yerevan/admin/market-stats` | District-level price breakdown by category. Median + average price/sqm, heat-shaded ranking, price reductions, disappeared and stale counts, commercial yield, AI summary, monthly/quarterly trend charts.                   |
| Data Hub                 | `/yerevan/admin/data-hub`     | Filterable listing explorer scoped to market. Filter by category, district, status, rooms, price, area, agency/owner, posted date, collected date. Live count, race-condition protection. Download filtered Excel instantly. |
| AI Assistant             | `/yerevan/admin/assistant`    | Chat interface powered by Claude Haiku. Market-aware - answers scoped to selected market. 9 callable tools, persistent per-user history per market, context-aware suggested queries.                                         |
| Global Dashboard         | `/global/admin/dashboard`     | Cross-market overview - total listings, median prices, last scraped, active categories per market.                                                                                                                           |
| Global Market Comparison | `/global/admin/market-stats`  | Per-category price comparison table across all configured markets.                                                                                                                                                           |
| Global AI Assistant      | `/global/admin/assistant`     | Cross-market AI - all sources accessible, compareMarkets tool, context-aware suggested queries.                                                                                                                              |

**279 tests passing** across unit, feature and integration layers. PostgreSQL 16 backend.

Old `/admin/*` URLs redirect automatically to `/yerevan/admin/*`.

---

## Tech Stack

| Layer          | Choice                                                                                 |
| -------------- | -------------------------------------------------------------------------------------- |
| Backend        | Laravel 12 (PHP 8.4), layered architecture (Controller → Service → Repository → Model) |
| Frontend       | Inertia.js + React + TypeScript, Tailwind, shadcn/Radix UI                             |
| Auth           | Fortify + Sanctum, Spatie roles/permissions                                            |
| AI             | Prism → OpenRouter, LarAgent, `anthropic/claude-haiku-4-5`                             |
| Database       | PostgreSQL 16 + pgvector                                                               |
| Scraping       | Laravel Dusk + ChromeDriver (list.am is JS-rendered)                                   |
| Queue          | Redis + Laravel Horizon                                                                |
| Storage        | Backblaze B2 (screenshots + DB backups), rclone                                        |
| Screenshots    | Screenshotone API → Backblaze B2                                                       |
| Exchange Rates | open.er-api.com (free, no auth, daily)                                                 |
| Monitoring     | Sentry, UptimeRobot, Telegram alerts                                                   |
| DevOps         | Docker, Caddy (HTTPS), Vultr VPS, GitHub Actions CI/CD                                 |

---

## Local Setup

```bash
docker compose up -d --build
docker compose exec app bash scripts/setup.sh
npm install && npm run build
```

Then visit `http://localhost:8080/`.

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

| Market  | Slug    | Flag | Data Source       | Status  |
| ------- | ------- | ---- | ----------------- | ------- |
| Yerevan | yerevan | 🇦🇲   | list.am           | ✅ Live |
| Baku    | baku    | 🇦🇿   | bina.az           | ⬜      |
| Tbilisi | tbilisi | 🇬🇪   | myhome.ge + ss.ge | ⬜      |

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

All currencies accepted (AMD, EUR, USD). Prices converted to USD at pipeline time via `ExchangeRateService` (rates from open.er-api.com, updated daily). Original currency preserved in `currency` column.

---

## Listing Lifecycle

Every listing is tracked through its full lifecycle:

| Field                    | Description                                                |
| ------------------------ | ---------------------------------------------------------- |
| `listing_date`           | When the seller posted on the source site                  |
| `scraped_at`             | When the listing was first collected by Atlas              |
| `last_seen_at`           | Last time confirmed live via HTTP check                    |
| `last_checked_at`        | Last time check-listings ran against it                    |
| `disappeared_at`         | When first detected as gone (404)                          |
| `description`            | Raw listing description (native language)                  |
| `translated_description` | English translation via Claude API                         |
| `screenshot_url`         | B2 path of detail page screenshot                          |
| `days_on_market`         | Computed at export time from listing_date → disappeared_at |
| `status`                 | Active / Stale (90+ days) / Disappeared                    |

Stale detection uses `COALESCE(listing_date, scraped_at)` consistently across Data Hub, Market Stats and Excel exports.

The `atlas:check-listings` command checks 5% of active listings per run. Currently disabled - requires residential proxies (list.am returns 403 to non-browser requests from server IPs).

---

## Scheduled Jobs

| Command                      | Schedule                    | Description                                                 |
| ---------------------------- | --------------------------- | ----------------------------------------------------------- |
| `atlas:capture-screenshots`  | Daily 00:00                 | Screenshot new listings via Screenshotone API → B2 (66/day) |
| `atlas:fetch-exchange-rates` | Daily 04:00                 | Fetch AMD/AZN/GEL/EUR rates from open.er-api.com            |
| `scraper:run [category]`     | Mon/Wed/Fri 05:00 Tbilisi   | Scrape one category from list.am                            |
| `atlas:check-listings`       | Commented out               | Requires residential proxies                                |
| `scripts/backup-db.sh`       | Daily 03:00 UTC (host cron) | pg_dump → B2 via rclone                                     |

---

## API

All authenticated endpoints require a Sanctum Bearer token. Create one at `/yerevan/admin/tokens`.

A full Postman collection is available at [`postman/atlas.postman_collection.json`](postman/atlas.postman_collection.json). Import it, set `base_url`, `market` and `token` variables and every endpoint is ready to use.

**Market-aware endpoints** - Pass `?market=yerevan` (or `baku`/`tbilisi`) to scope results. Pass `market=global` in AI chat body for cross-market assistant context:

```
GET  /api/v1/public/snapshot?market=yerevan          - public market snapshot (no auth)
GET  /api/v1/trends/volume?market=yerevan&groupBy=month   - monthly/quarterly listing volume
GET  /api/v1/trends/price?market=yerevan&groupBy=month    - monthly/quarterly median price/sqm
GET  /api/v1/scrape/status-all?market=yerevan        - live status for market categories
POST /api/v1/scrape/start-all?market=yerevan         - start scraping market categories
GET  /api/v1/scrape/trend?source=...                 - scraper run volume over time
GET  /api/v1/listings?source=...                     - paginated listings with filters
GET  /api/v1/listings/stats?source=...               - district price breakdown
GET  /api/v1/listings/filter?market=yerevan          - Data Hub filter
GET  /api/v1/listings/summary?source=...             - AI market summary
GET  /api/v1/listings/yield?pair=...&market=yerevan  - rental yield per district
GET  /api/v1/exports/{source}                        - Excel export, one category
GET  /api/v1/exports?market=yerevan                  - Excel exports, all market categories
GET  /api/v1/exports/filtered?source=...             - Excel export with filters applied
POST /api/v1/ai/chat                                 - AI assistant chat (market passed in body)
GET  /api/v1/ai/history?market=yerevan               - AI chat history for current user + market
GET  /api/v1/ai/usage                                - current user token usage and cost
GET  /api/v1/ai/usage/system                         - system-wide usage, admin only
```

---

## Roles

| Role          | Access                                                                  |
| ------------- | ----------------------------------------------------------------------- |
| `super_admin` | Everything                                                              |
| `admin`       | All product screens + Users + Settings + Activity Log + API Tokens      |
| `editor`      | Intelligence screens only (Scraper Control, Market Stats, AI Assistant) |
| `viewer`      | 403 - no access                                                         |

---

## License

Private repository. Not for public distribution.
