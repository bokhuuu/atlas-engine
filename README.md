# 🗺️ Atlas - Yerevan Real Estate Market Intelligence Platform

Atlas scrapes Armenian real estate listings from list.am, cleans the data automatically and turns it into tools a real estate analyst can use: a public market snapshot, an internal dashboard, an AI assistant that answers questions from real scraped data, AI-generated market summaries per category and on-demand Excel exports.

Built by merging three internal templates - a scraping engine, an AI/LLM integration layer and an admin dashboard skeleton - into one single Laravel application.

**Production:** `https://45-32-154-101.nip.io`

---

## What's Live

| Screen                     | URL                   | Description                                                                                                                                                                                                          |
| -------------------------- | --------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Screen 0 - Public Snapshot | `/`                   | Public market snapshot. No login. 7 category cards with median price/sqm, listing counts, animated count-up, live badge.                                                                                             |
| Screen 1 - Dashboard       | `/admin/dashboard`    | Scraper control panel. Start/stop scrapes, live status, listing volume trend chart, Excel exports.                                                                                                                   |
| Screen 1 - Market Stats    | `/admin/market-stats` | District-level price breakdown by category. Median + average price/sqm, heat-shaded ranking, price reductions, commercial rental yield, AI market summary.                                                           |
| Screen 3 - AI Assistant    | `/admin/assistant`    | Chat interface powered by Claude Haiku. 7 callable tools: category stats, district breakdown, comparable-listing finder, Excel export, price change detection, rental yield estimate. Per-user conversation history. |

---

## Tech Stack

| Layer      | Choice                                                                                        |
| ---------- | --------------------------------------------------------------------------------------------- |
| Backend    | Laravel (PHP), layered architecture (Controller → Service → Repository → Model)               |
| Frontend   | Inertia.js + React + TypeScript, Tailwind, shadcn/Radix UI                                    |
| Auth       | Fortify + Sanctum (stateful API for first-party dashboard requests), Spatie roles/permissions |
| AI         | Prism → OpenRouter, LarAgent agent framework, `anthropic/claude-haiku-4-5` as agent model     |
| Database   | MySQL 8.0                                                                                     |
| Scraping   | Laravel Dusk + ChromeDriver (real headless browser - list.am is JS-rendered)                  |
| Queue      | Redis + Laravel Horizon                                                                       |
| Monitoring | Sentry                                                                                        |
| DevOps     | Docker, Caddy (HTTPS), Vultr VPS                                                              |

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
- Password: `zura1234`

---

## Production Deploy

After every `git push`, SSH into the server and run:

```bash
cd /var/www/atlas
bash scripts/deploy.sh
```

This handles: git pull, composer install, migrations, permissions, cache rebuild, horizon restart.

**Do not run while a scrape is in progress** — deploy restarts Horizon and kills running jobs.

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

## API

All authenticated endpoints require a Sanctum Bearer token. Create one at `/admin/tokens`.

A full Postman collection is available at [`postman/atlas.postman_collection.json`](postman/atlas.postman_collection.json). Import it, set `base_url` and `token` variables and every endpoint is ready to use.

**Key endpoints:**

```
GET  /api/v1/public/snapshot              - public market snapshot (no auth)
GET  /api/v1/scrape/status-all            - live status for all 7 categories
POST /api/v1/scrape/start-all             - start scraping all categories
GET  /api/v1/scrape/trend?source=...      - listing volume over time
GET  /api/v1/listings/stats?source=...    - district price breakdown with price reductions
GET  /api/v1/listings/summary?source=...  - AI market summary
GET  /api/v1/listings/yield?pair=...      - rental yield estimate per district (residential|commercial)
GET  /api/v1/exports/{source}             - Excel export, one category
GET  /api/v1/exports                      - Excel exports, all categories (zip)
POST /api/v1/ai/chat                      - AI assistant chat (7 tools)
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
