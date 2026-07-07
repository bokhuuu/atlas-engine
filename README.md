# 🗺️ Atlas - Yerevan Real Estate Market Intelligence Platform

Atlas scrapes Armenian real estate listings from list.am, cleans the data automatically and turns it into tools a real estate analyst can use: a public market snapshot, an internal dashboard, an AI assistant that answers questions from real scraped data, AI-generated market summaries per category, a filterable Data Hub with Excel exports, and automated listing lifecycle tracking.

Built by merging three internal templates - a scraping engine, an AI/LLM integration layer and an admin dashboard skeleton - into one single Laravel application.

**Production:** `https://45-32-154-101.nip.io`

---

## What's Live

| Screen                     | URL                   | Description                                                                                                                                                                                                                                                                                  |
| -------------------------- | --------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Screen 0 - Public Snapshot | `/`                   | Public market snapshot. No login. 7 category cards with median price/sqm, listing counts, animated count-up, live badge.                                                                                                                                                                     |
| Screen 1 - Dashboard       | `/admin/dashboard`    | Scraper control panel. Start/stop scrapes, live status, listing volume trend chart, Excel exports.                                                                                                                                                                                           |
| Screen 2 - Market Stats    | `/admin/market-stats` | District-level price breakdown by category. Median + average price/sqm, heat-shaded ranking, price reductions, disappeared and stale listing counts per district, commercial rental yield, AI market summary.                                                                                |
| Screen 3 - Data Hub        | `/admin/data-hub`     | Filterable listing explorer. Filter by category, district, status (active/stale/disappeared), rooms, price range, area range, agency/owner, and date range. Live count updates with race-condition protection. Download filtered Excel export instantly.                                     |
| Screen 4 - AI Assistant    | `/admin/assistant`    | Chat interface powered by Claude Haiku. 8 callable tools: category stats, district breakdown, comparable-listing finder, Excel export, price change detection, rental yield estimate, semantic search. Persistent per-user conversation history (survives page reloads and device switches). |

---

## Tech Stack

| Layer      | Choice                                                                                        |
| ---------- | --------------------------------------------------------------------------------------------- |
| Backend    | Laravel (PHP), layered architecture (Controller → Service → Repository → Model)               |
| Frontend   | Inertia.js + React + TypeScript, Tailwind, shadcn/Radix UI                                    |
| Auth       | Fortify + Sanctum (stateful API for first-party dashboard requests), Spatie roles/permissions |
| AI         | Prism → OpenRouter, LarAgent agent framework, `anthropic/claude-haiku-4-5` as agent model     |
| Database   | PostgreSQL 16 + pgvector                                                                      |
| Scraping   | Laravel Dusk + ChromeDriver (real headless browser - list.am is JS-rendered)                  |
| Queue      | Redis + Laravel Horizon                                                                       |
| Storage    | Backblaze B2 (screenshots), rclone (DB backups)                                               |
| Monitoring | Sentry, UptimeRobot, Telegram alerts                                                          |
| DevOps     | Docker, Caddy (HTTPS), Vultr VPS, GitHub Actions CI/CD                                        |

---

## Local Setup

```bash
docker compose up -d --build
docker compose exec app bash scripts/setup.sh
npm install && npm run build
```

Then visit `http://localhost:8080`.

The setup script handles environment file creation, app key generation, migrations, database seeding, and storage linking in one pass.

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

---

## Data Sources

All data is scraped from [list.am](https://list.am) across 7 property categories:

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
| `last_seen_at`    | Last time the listing was confirmed live via HTTP check  |
| `last_checked_at` | Last time the check-listings command ran against it      |
| `disappeared_at`  | When the listing was first detected as gone (404)        |
| `screenshot_url`  | B2 path of the detail page screenshot (Thread 14)        |
| `days_on_market`  | Computed at export time from scraped_at → disappeared_at |
| `status`          | Active / Stale (90+ days) / Disappeared                  |

The `atlas:check-listings` command runs nightly on off-scrape days, checking 5% of active listings per run via lightweight HTTP HEAD requests. Full DB cycle completes in ~20 days.

---

## Scheduled Jobs

| Command                     | Schedule                      | Description                               |
| --------------------------- | ----------------------------- | ----------------------------------------- |
| `scraper:run [category]`    | Mon/Wed/Fri 05:00 Tbilisi     | Scrape one category from list.am          |
| `atlas:check-listings`      | Tue/Thu/Sat/Sun 10:00 Tbilisi | HTTP HEAD check for disappeared listings  |
| `atlas:capture-screenshots` | Tue/Thu/Sat 05:00 Tbilisi     | Screenshot new listings to B2 (Thread 14) |

---

## API

All authenticated endpoints require a Sanctum Bearer token. Create one at `/admin/tokens`.

A full Postman collection is available at [`postman/atlas.postman_collection.json`](postman/atlas.postman_collection.json). Import it, set `base_url` and `token` variables and every endpoint is ready to use.

**Key endpoints:**

```
GET  /api/v1/public/snapshot              - public market snapshot (no auth)
GET  /api/v1/scrape/status-all            - live status for all 7 categories
POST /api/v1/scrape/start-all             - start scraping all categories
GET  /api/v1/scrape/trend?source=...      - listing volume over time
GET  /api/v1/listings?source=...          - paginated listings with filters
GET  /api/v1/listings/stats?source=...    - district price breakdown with reductions, disappeared, stale
GET  /api/v1/listings/filter?source=...   - Data Hub filter (not rate-limited, UI polling safe)
GET  /api/v1/listings/summary?source=...  - AI market summary
GET  /api/v1/listings/yield?pair=...      - rental yield estimate per district
GET  /api/v1/exports/{source}             - Excel export, one category (all listings)
GET  /api/v1/exports                      - Excel exports, all categories (zip)
GET  /api/v1/exports/filtered?source=...  - Excel export with Data Hub filters applied
POST /api/v1/ai/chat                      - AI assistant chat (8 tools)
GET  /api/v1/ai/history                   - fetch last 20 AI chat messages for current user
GET  /api/v1/ai/usage                     - current user's token usage and cost
GET  /api/v1/ai/usage/system              - system-wide token usage, admin only
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
