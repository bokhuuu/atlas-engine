# 🗺️ Atlas - Multi-Market Real Estate Intelligence Platform

Atlas is a multi-market real estate intelligence platform built for Colliers International. It scrapes listings across Caucasus markets (Yerevan, Baku, Tbilisi), cleans and deduplicates the data and delivers: a combined public market overview, per-market snapshots, an internal dashboard, an AI assistant with 11 tools, AI-generated summaries, a filterable Data Hub, on-demand Excel exports and property screenshots.

One platform, multiple markets. Switching between Yerevan and Baku changes which data you see - the platform, AI and exports all scope automatically. A Global view shows all markets combined.

**Production:** `https://45-32-154-101.nip.io`

---

## What's Live

| Screen                   | URL                           | Description                                                                                                                                                                                                                                                                                |
| ------------------------ | ----------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Combined Overview        | `/`                           | Public combined view. All markets, total listings, per-market cards, animated counter. No login.                                                                                                                                                                                           |
| Per-Market Snapshot      | `/yerevan` `/baku` `/tbilisi` | Public market snapshot per market. No login. Median price/sqm, listing counts, animated count-up, live badge.                                                                                                                                                                              |
| Scraper Control          | `/yerevan/admin/dashboard`    | Scraper control panel scoped to market. Start scrapes, live status, listing volume chart, Excel exports.                                                                                                                                                                                   |
| Market Stats             | `/yerevan/admin/market-stats` | District-level price breakdown by category. Median + average price/sqm, heat-shaded ranking, price reductions, disappeared and stale counts, commercial yield, AI summary, monthly/quarterly trend charts.                                                                                 |
| Data Hub                 | `/yerevan/admin/data-hub`     | Filterable listing explorer scoped to market. Filter by category, district, status, rooms, price, area, agency/owner, bill of sale, mortgage, posted date, collected date. Live count, race-condition protection. Download filtered Excel instantly. All listings shown (no dedup toggle). |
| AI Assistant             | `/yerevan/admin/assistant`    | Chat interface powered by Claude Sonnet 4.6. Market-aware - answers scoped to selected market. 11 callable tools, persistent per-user history per market, context-aware suggested queries. Clear Chat button with confirmation dialog.                                                     |
| Global Dashboard         | `/global/admin/dashboard`     | Cross-market overview - total listings, median prices, last scraped, active categories per market.                                                                                                                                                                                         |
| Global Market Comparison | `/global/admin/market-stats`  | Per-category price comparison table across all configured markets.                                                                                                                                                                                                                         |
| Global AI Assistant      | `/global/admin/assistant`     | Cross-market AI - all sources accessible, compareMarkets tool, context-aware suggested queries.                                                                                                                                                                                            |

Old `/admin/*` URLs redirect automatically to `/yerevan/admin/*`.

**347 tests passing** across unit, feature and integration layers (PostgreSQL 16 backend, Pest framework).

---

## Tech Stack

| Layer          | Choice                                                                                                          |
| -------------- | --------------------------------------------------------------------------------------------------------------- |
| Backend        | Laravel 12 (PHP 8.4), layered architecture (Controller → Service → Repository → Model)                          |
| Frontend       | Inertia.js + React + TypeScript, Tailwind, shadcn/Radix UI                                                      |
| Auth           | Fortify + Sanctum, Spatie roles/permissions                                                                     |
| AI             | Prism → OpenRouter, LarAgent, `anthropic/claude-sonnet-4-6` (agent), `anthropic/claude-haiku-4-5` (translation) |
| Database       | PostgreSQL 16 + pgvector                                                                                        |
| Scraping       | Laravel Dusk + ChromeDriver (list.am is JS-rendered); Guzzle + DomCrawler (bina.az is server-rendered)          |
| Queue          | Redis + Laravel Horizon                                                                                         |
| Storage        | Backblaze B2 (screenshots + DB backups), rclone                                                                 |
| Screenshots    | Screenshotone API (headless browser as a service) → Backblaze B2                                                |
| Exchange Rates | open.er-api.com (free, no auth, daily)                                                                          |
| Monitoring     | Sentry, UptimeRobot, Telegram alerts                                                                            |
| DevOps         | Docker, Caddy (HTTPS), Vultr VPS, GitHub Actions CI/CD                                                          |

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

**Production docker rule:** always use `docker compose -f docker-compose.prod.yml` on production. Never bare `docker compose`. After any `docker-compose.prod.yml` change, use `--force-recreate` on the affected container.

---

## Markets

| Market  | Slug    | Flag | Data Source       | Status                   |
| ------- | ------- | ---- | ----------------- | ------------------------ |
| Yerevan | yerevan | 🇦🇲   | list.am           | ✅ Live — 7 categories   |
| Baku    | baku    | 🇦🇿   | bina.az           | ✅ Live — Land + Offices |
| Tbilisi | tbilisi | 🇬🇪   | myhome.ge + ss.ge | ⬜ Planned               |

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

## Data Sources (Baku)

All Baku data is scraped from [bina.az](https://ru.bina.az) via GraphQL API (no browser required):

| Profile                      | Category                   | categoryId | Deal Type | Status                    |
| ---------------------------- | -------------------------- | ---------- | --------- | ------------------------- |
| `binaz_land_sale`            | Land for Sale              | 9          | Sale      | ✅ Live — ~4,600 listings |
| `binaz_land_sale_sea_breeze` | Land for Sale — Sea Breeze | 9          | Sale      | ✅ Live — ~66 listings    |
| `binaz_offices_sale`         | Offices for Sale           | 7          | Sale      | ✅ Live — ~83 listings    |
| `binaz_offices_rent`         | Offices for Rent           | 7          | Rent      | ✅ Live — ~1,595 listings |
| `binaz_apartments_sale`      | Apartments for Sale        | 1          | Sale      | ⬜ Thread 17              |
| `binaz_apartments_rent`      | Apartments for Rent        | 1          | Rent      | ⬜ Thread 17              |
| `binaz_houses_sale`          | Houses for Sale            | 5          | Sale      | ⬜ Thread 17              |
| `binaz_commercial_sale`      | Commercial for Sale        | 10         | Sale      | ⬜ Thread 17              |
| `binaz_commercial_rent`      | Commercial for Rent        | 10         | Rent      | ⬜ Thread 17              |

**Scraping approach:**

1. `POST https://ru.bina.az/graphql` — persisted query, cursor pagination, 16 listings/fetch
2. `GET https://ru.bina.az/items/{id}` — server-rendered HTML detail page
3. `GET https://ru.bina.az/items/{id}/phones?react=true` — phone number

**Baku-specific fields:**

- `has_bill_of_sale` (купчая) — significantly affects land pricing, filterable in Data Hub
- `has_mortgage` — mortgage availability, filterable in Data Hub
- `original_price_azn` — original AZN price preserved in extras
- `area_sotka` — area in sotka (computed in Excel: area_m² ÷ 100)
- `price_per_sotka_usd` — price per sotka in USD (computed in Excel)

**Sea Breeze profile:** uses bina.az locationId 380 via `locationIds` GraphQL filter. Returns exactly what bina.az shows for Sea Breeze location. Note: locationId 380 covers a broad zone — some nearby listings not strictly inside the development. Use weighted average as primary metric.

**District normalization:** Russian settlement names normalized to parent administrative districts via `NormalizeToEnglishStage`. Multiple spaces collapsed before lookup (bina.az sometimes returns "Насиминский р." with double space).

**Building type normalization:** `NormalizeToEnglishStage` translates Russian → English, then `CleanBuildingTypeStage` normalizes spacing (preserves casing — do NOT lowercase after translation).

**Duplicate detection:** `FlagBinazDuplicatesStage` — price ±3% + area ±2% + same district. Office rent shows ~34% duplicate rate (normal — same office posted by multiple agencies). All listings shown in Data Hub — `duplicate_group_id` column in Excel identifies groups.

**Persisted query hash:** stored in `.env` as `BINAZ_PERSISTED_QUERY_HASH`. Rotates on bina.az frontend deploy — update from DevTools: Network → SearchItems → Payload → extensions.

**Pagination:** `BINAZ_*_MAX_PAGES` in `.env` controls cursor fetch count. Always cache config on BOTH `app` AND `horizon` containers after `.env` changes.

**Backfill pattern:**

- Set `BINAZ_{PROFILE}_MAX_PAGES=300` for initial backfill
- After confirmed complete → reduce to 10 for daily runs
- Set `BINAZ_{PROFILE}_TIMEOUT=7200` for backfill

---

## Listing Lifecycle

Every listing is tracked through its full lifecycle:

| Field                    | Description                                                |
| ------------------------ | ---------------------------------------------------------- |
| `listing_date`           | When the seller last updated/bumped on the source site     |
| `scraped_at`             | When the listing was first collected by Atlas              |
| `last_seen_at`           | Last time confirmed live via HTTP check                    |
| `last_checked_at`        | Last time check-listings ran against it                    |
| `disappeared_at`         | When first detected as gone (404)                          |
| `description`            | Raw listing description (native language)                  |
| `translated_description` | English translation via Claude API                         |
| `screenshot_url`         | B2 path of detail page screenshot                          |
| `has_bill_of_sale`       | Bill of sale status (bina.az only, affects Baku pricing)   |
| `has_mortgage`           | Mortgage availability (bina.az only)                       |
| `days_on_market`         | Computed at export time from listing_date → disappeared_at |
| `status`                 | Active / Stale (90+ days) / Disappeared                    |

Stale detection uses `COALESCE(listing_date, scraped_at)` consistently across Data Hub, Market Stats and Excel exports.

**Note on bina.az listing_date:** bina.az only exposes `updatedAt` (last bumped date), not original posting date.

The `atlas:check-listings` command checks 5% of active listings per run. Currently disabled for list.am - requires residential proxies. Can be enabled for bina.az (server-rendered, no proxy needed).

---

## Excel Export Structure

All exports show raw listings (no deduplication) — counts match source site exactly.

| Property type                 | Sheets                                |
| ----------------------------- | ------------------------------------- |
| Land                          | All Listings + Land Size Analysis     |
| Apartments / Houses / New Dev | All Listings + Room Type Analysis     |
| Offices / Commercial          | All Listings + Building Type Analysis |

**Land Size Analysis sheet** — answers Colliers' four questions:

1. Total listing count
2. Min/max area (sqm)
3. Count by size range (0–1,000 / 1,001–5,000 / 5,001–10,000 / 10,000+ sqm)
4. Min/max/weighted avg price per sqm (AZN + USD) — overall and per range

Weighted avg = sum of prices ÷ sum of areas (more accurate than averaging per-sqm prices).

**All Listings sheet** includes 36 columns: `area_sotka`, `price_per_sotka_usd`, `has_bill_of_sale` (Yes/No), `has_mortgage` (Yes/No), `original_price_azn`, `translated_description`, `phone`, `agency_name`, `duplicate_group_id`, `screenshot_url` and more.

---

## Scheduled Jobs

| Command                                  | Schedule                    | Description                                                  |
| ---------------------------------------- | --------------------------- | ------------------------------------------------------------ |
| `atlas:capture-screenshots`              | Daily 00:00                 | Screenshot new listings via Screenshotone API → B2 (200/day) |
| `atlas:fetch-exchange-rates`             | Daily 04:00                 | Fetch AMD/AZN/GEL/EUR rates from open.er-api.com             |
| `scraper:run listam_*`                   | Mon/Wed/Fri 05:00–06:00     | Scrape all 7 Yerevan categories from list.am (10 min apart)  |
| `scraper:run binaz_land_sale`            | Daily 12:00                 | Scrape Baku land listings                                    |
| `scraper:run binaz_land_sale_sea_breeze` | Daily 12:10                 | Scrape Sea Breeze land (exact locationId filter)             |
| `scraper:run binaz_offices_sale`         | Daily 12:20                 | Scrape Baku office sale listings                             |
| `scraper:run binaz_offices_rent`         | Daily 12:30                 | Scrape Baku office rent listings                             |
| `atlas:embed-listings --source=binaz_*`  | Daily 13:00–13:30           | Embed all Baku categories for semantic search                |
| `atlas:check-listings`                   | Commented out               | Requires residential proxies for list.am                     |
| `scripts/backup-db.sh`                   | Daily 03:00 UTC (host cron) | pg_dump → B2 via rclone                                      |

---

## AI Assistant Tools (11 total)

| Tool                   | Description                                                           |
| ---------------------- | --------------------------------------------------------------------- |
| `getCategoryStats`     | Total listings + citywide median price/sqm                            |
| `getDistrictBreakdown` | Pricing by district for a category                                    |
| `getDistrictStats`     | Full stats for a district — AZN+USD prices, size range breakdown      |
| `getLocationStats`     | Exact data for Sea Breeze (locationId 380) or keyword search fallback |
| `findComparables`      | Find listings by price, district, rooms                               |
| `getCategoryList`      | List all available categories                                         |
| `exportToExcel`        | Generate Excel report, return download link                           |
| `getPriceChanges`      | Districts with price reductions in last 7 days                        |
| `getYieldEstimate`     | Estimated rental yield by district                                    |
| `compareMarkets`       | Side-by-side comparison across all markets                            |
| `semanticSearch`       | Natural language search via pgvector embeddings                       |

---

## API

All authenticated endpoints require a Sanctum Bearer token. Create one at `/yerevan/admin/tokens`.

A full Postman collection is available at [`postman/atlas.postman_collection.json`](postman/atlas.postman_collection.json). Import it, set `base_url`, `market` and `token` variables and every endpoint is ready to use.

**Market-aware endpoints** - pass `?market=yerevan` (or `baku`/`tbilisi`) to scope results:

```
GET    /api/v1/public/snapshot?market=yerevan          - public market snapshot (no auth)
GET    /api/v1/trends/volume?market=yerevan&groupBy=month   - monthly/quarterly listing volume
GET    /api/v1/trends/price?market=yerevan&groupBy=month    - monthly/quarterly median price/sqm
GET    /api/v1/scrape/status-all?market=yerevan        - live status for market categories
POST   /api/v1/scrape/start-all?market=yerevan         - start scraping market categories
GET    /api/v1/scrape/trend?source=...                 - scraper run volume over time
GET    /api/v1/listings?source=...                     - paginated listings with filters
GET    /api/v1/listings/stats?source=...               - district price breakdown
GET    /api/v1/listings/filter?market=yerevan          - Data Hub filter (all listings, no dedup)
GET    /api/v1/listings/summary?source=...             - AI market summary
GET    /api/v1/listings/yield?pair=...&market=yerevan  - rental yield per district
GET    /api/v1/exports/{source}                        - Excel export, one category
GET    /api/v1/exports?market=yerevan                  - Excel exports, all market categories
GET    /api/v1/exports/filtered?source=...             - Excel export with filters applied
POST   /api/v1/ai/chat                                 - AI assistant chat (market passed in body)
GET    /api/v1/ai/history?market=yerevan               - AI chat history for current user + market
DELETE /{market}/admin/assistant/history               - clear AI chat history (web route, XSRF-TOKEN)
GET    /api/v1/ai/usage                                - current user token usage and cost
GET    /api/v1/ai/usage/system                         - system-wide usage, admin only
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
