# 🗺️ Atlas - Multi-Market Real Estate Intelligence Platform

Atlas is a multi-market real estate intelligence platform built for Colliers International. It scrapes listings across Caucasus markets (Yerevan, Baku, Tbilisi), cleans and deduplicates the data and delivers: a combined public market overview, per-market snapshots, an internal dashboard, an AI assistant with 11 tools, AI-generated summaries, a filterable Data Hub, on-demand Excel exports and property screenshots.

One platform, multiple markets. Switching between Yerevan, Baku and Tbilisi changes which data you see - the platform, AI and exports all scope automatically. A Global view shows all markets combined.

**Production:** `https://45-32-154-101.nip.io`

---

## What's Live

| Screen                   | URL                           | Description                                                                                                                                                                                                                                                                                                 |
| ------------------------ | ----------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Combined Overview        | `/`                           | Public combined view. All markets, total listings, per-market cards, animated counter. No login.                                                                                                                                                                                                            |
| Per-Market Snapshot      | `/yerevan` `/baku` `/tbilisi` | Public market snapshot per market. No login. Median price/sqm, listing counts, animated count-up, live badge.                                                                                                                                                                                               |
| Scraper Control          | `/yerevan/admin/dashboard`    | Scraper control panel scoped to market. Start scrapes, live status, listing volume chart, Excel exports.                                                                                                                                                                                                    |
| Market Stats             | `/yerevan/admin/market-stats` | District-level price breakdown by category. Median + average price/sqm, heat-shaded ranking, price reductions, disappeared and stale counts, commercial yield, AI summary, monthly/quarterly trend charts.                                                                                                  |
| Data Hub                 | `/yerevan/admin/data-hub`     | Filterable listing explorer scoped to market. Filter by category, district, status, rooms, price, area, agency/owner, bill of sale, mortgage, new building, posted date, collected date. Live count, race-condition protection. Background Excel export (no timeout). All listings shown (no dedup toggle). |
| AI Assistant             | `/yerevan/admin/assistant`    | Chat interface powered by Claude Sonnet 4.6. Market-aware - answers scoped to selected market. 11 callable tools, persistent per-user history per market, context-aware suggested queries. Clear Chat button with confirmation dialog.                                                                      |
| Global Dashboard         | `/global/admin/dashboard`     | Cross-market overview - total listings, median prices, last scraped, active categories per market.                                                                                                                                                                                                          |
| Global Market Comparison | `/global/admin/market-stats`  | Per-category price comparison table across all configured markets.                                                                                                                                                                                                                                          |
| Global AI Assistant      | `/global/admin/assistant`     | Cross-market AI - all sources accessible, compareMarkets tool, context-aware suggested queries.                                                                                                                                                                                                             |

Old `/admin/*` URLs redirect automatically to `/yerevan/admin/*`.

**521 tests passing** across unit, feature and integration layers (PostgreSQL 16 backend, Pest framework).

---

## Tech Stack

| Layer          | Choice                                                                                                          |
| -------------- | --------------------------------------------------------------------------------------------------------------- |
| Backend        | Laravel 12 (PHP 8.4), layered architecture (Controller → Service → Repository → Model)                          |
| Frontend       | Inertia.js + React + TypeScript, Tailwind, shadcn/Radix UI                                                      |
| Auth           | Fortify + Sanctum, Spatie roles/permissions                                                                     |
| AI             | Prism → OpenRouter, LarAgent, `anthropic/claude-sonnet-4-6` (agent), `anthropic/claude-haiku-4-5` (translation) |
| Database       | PostgreSQL 16 + pgvector                                                                                        |
| Scraping       | Laravel Dusk + ChromeDriver (list.am, myhome.ge — JS-rendered); Guzzle + DomCrawler (bina.az — server-rendered) |
| Queue          | Redis + Laravel Horizon (dual supervisor: default queue for HTTP scrapers, chrome queue for browser scrapers)   |
| Storage        | Backblaze B2 (screenshots + DB backups), rclone                                                                 |
| Screenshots    | Screenshotone API (headless browser as a service) → Backblaze B2                                                |
| Exchange Rates | open.er-api.com (free, no auth, daily)                                                                          |
| Proxy          | Webshare Rotating Residential Georgia — active for myhome.ge Chrome sessions                                    |
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

| Market  | Slug    | Flag | Data Source | Status                                                                                      |
| ------- | ------- | ---- | ----------- | ------------------------------------------------------------------------------------------- |
| Yerevan | yerevan | 🇦🇲   | list.am     | ✅ Live — 7 categories                                                                      |
| Baku    | baku    | 🇦🇿   | bina.az     | ✅ Live — 10 categories (land, offices, apartments, houses, commercial)                     |
| Tbilisi | tbilisi | 🇬🇪   | myhome.ge   | ✅ **Thread 18** Live — 9 categories (apartments, houses, country houses, land, commercial) |

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

| Profile                      | Category                   | categoryId | Deal Type | Status                                                 |
| ---------------------------- | -------------------------- | ---------- | --------- | ------------------------------------------------------ |
| `binaz_land_sale`            | Land for Sale              | 9          | Sale      | ✅ Live — ~4,800 listings                              |
| `binaz_land_sale_sea_breeze` | Land for Sale — Sea Breeze | 9          | Sale      | ✅ Live — ~74 listings                                 |
| `binaz_offices_sale`         | Offices for Sale           | 7          | Sale      | ✅ Live — ~140 listings                                |
| `binaz_offices_rent`         | Offices for Rent           | 7          | Rent      | ✅ Live — ~1,769 listings                              |
| `binaz_apartments_sale`      | Apartments for Sale        | 1          | Sale      | ⏸ ~1,588 listings — paused pending Colliers strategy   |
| `binaz_apartments_rent`      | Apartments for Rent        | 1          | Rent      | ⏸ ~800 listings — paused pending Colliers strategy     |
| `binaz_houses_sale`          | Houses for Sale            | 5          | Sale      | ⏸ ~48 listings — paused pending Colliers strategy      |
| `binaz_houses_rent`          | Houses for Rent            | 5          | Rent      | ⏸ ~48 listings — paused pending Colliers strategy      |
| `binaz_commercial_sale`      | Commercial for Sale        | 10         | Sale      | ✅ Live — ~2,401 listings fully backfilled (Thread 17) |
| `binaz_commercial_rent`      | Commercial for Rent        | 10         | Rent      | ✅ Live — ~2,510 listings fully backfilled (Thread 17) |

**Scraping approach:**

1. `POST https://ru.bina.az/graphql` — persisted query, cursor pagination, 16 listings/fetch
2. `GET https://ru.bina.az/items/{id}` — server-rendered HTML detail page
3. `GET https://ru.bina.az/items/{id}/phones?react=true` — phone number

**Baku-specific fields:**

- `has_bill_of_sale` (купчая) — significantly affects land pricing, filterable in Data Hub
- `has_mortgage` — mortgage availability, filterable in Data Hub
- `is_new_building` — boolean, apartments only. Extracted from "Категория" property row. Filterable in Data Hub.
- `original_price_azn` — original AZN price preserved in extras
- `area_sotka` — area in sotka (computed in Excel: area_m² ÷ 100, land only)
- `price_per_sotka_usd` — price per sotka in USD (computed in Excel, land only)

---

## Data Sources (Tbilisi) — Thread 18

All Tbilisi data is scraped from [myhome.ge](https://www.myhome.ge) via Laravel Dusk (Chrome browser):

| Profile                      | Category                | Deal Type | Status  |
| ---------------------------- | ----------------------- | --------- | ------- |
| `myhome_apartments_sale`     | Apartments for Sale     | Sale      | ✅ Live |
| `myhome_apartments_rent`     | Apartments for Rent     | Rent      | ✅ Live |
| `myhome_houses_sale`         | Houses for Sale         | Sale      | ✅ Live |
| `myhome_houses_rent`         | Houses for Rent         | Rent      | ✅ Live |
| `myhome_country_houses_sale` | Country Houses for Sale | Sale      | ✅ Live |
| `myhome_country_houses_rent` | Country Houses for Rent | Rent      | ✅ Live |
| `myhome_land_sale`           | Land for Sale           | Sale      | ✅ Live |
| `myhome_commercial_sale`     | Commercial for Sale     | Sale      | ✅ Live |
| `myhome_commercial_rent`     | Commercial for Rent     | Rent      | ✅ Live |

**Scraping approach:** myhome.ge is Next.js SSR. All listing data is extracted from `__NEXT_DATA__` JSON embedded in the page — no DOM scraping required. Chrome browser (Dusk) is used because the site requires JavaScript execution and has Cloudflare protection.

**Tbilisi-specific fields:**

- `original_price_gel` — original GEL price preserved in extras
- `statement_uuid` — TNet listing UUID stored in extras (for future phone extraction)
- `sub_district` — granular neighborhood stored in extras (e.g. Saburtalo within Vake-Saburtalo)

**Phone extraction:** deferred to Thread 19. Requires persistent Chrome session across all detail jobs (session reuse architecture).

**Proxy:** Webshare Rotating Residential Georgia — active (`SCRAPER_PROXY_ENABLED=true` on production). Georgian residential IPs give ~54s/listing and better reCAPTCHA scores.

---

## Queue Architecture (Thread 18)

Two queues, two Horizon supervisors:

| Queue     | Supervisor   | maxProcesses | Used by                      |
| --------- | ------------ | ------------ | ---------------------------- |
| `default` | supervisor-1 | 2            | bina.az (Guzzle HTTP)        |
| `chrome`  | supervisor-2 | 1            | list.am + myhome.ge (Chrome) |

The `chrome` queue with `maxProcesses=1` ensures only one Chrome session runs at a time, preventing session flooding.

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
| `has_bill_of_sale`       | Bill of sale status (bina.az only)                         |
| `has_mortgage`           | Mortgage availability (bina.az only)                       |
| `is_new_building`        | New build vs secondary (apartments only, bina.az)          |
| `days_on_market`         | Computed at export time from listing_date → disappeared_at |
| `status`                 | Active / Stale (90+ days) / Disappeared                    |

---

## Excel Export Structure

All exports show raw listings (no deduplication) — counts match source site exactly.

| Property type                 | Sheets                                |
| ----------------------------- | ------------------------------------- |
| Land                          | All Listings + Land Size Analysis     |
| Apartments / Houses / New Dev | All Listings + Room Type Analysis     |
| Offices / Commercial          | All Listings + Building Type Analysis |

**All Listings sheet** includes: `original_price` (GEL for Tbilisi, AZN for Baku), `area_sotka`/`price_per_sotka_usd` (land only), `translated_description`, `phone`, `agency_name` and more.

**Background export:** Data Hub "Download Excel" dispatches a background job to avoid nginx timeouts on large datasets.

---

## Scheduled Jobs

| Command                                  | Schedule                    | Description                                          |
| ---------------------------------------- | --------------------------- | ---------------------------------------------------- |
| `atlas:capture-screenshots`              | Daily 00:00                 | Screenshot new listings via Screenshotone API → B2   |
| `scraper:clean-zombies --hours=8`        | Daily 03:00                 | Auto-mark stale scraper runs as failed               |
| `atlas:fetch-exchange-rates`             | Daily 04:00                 | Fetch AMD/AZN/GEL/EUR rates from open.er-api.com     |
| `scraper:run listam_*`                   | Mon/Wed/Fri 05:00–06:00     | Scrape all 7 Yerevan categories from list.am         |
| `scraper:run binaz_land_sale`            | Daily 12:00                 | Scrape Baku land listings                            |
| `scraper:run binaz_land_sale_sea_breeze` | Daily 12:10                 | Scrape Sea Breeze land                               |
| `scraper:run binaz_offices_sale`         | Daily 12:20                 | Scrape Baku office sale listings                     |
| `scraper:run binaz_offices_rent`         | Daily 12:30                 | Scrape Baku office rent listings                     |
| `scraper:run binaz_commercial_sale`      | Daily 13:20                 | Scrape Baku commercial sale listings                 |
| `scraper:run binaz_commercial_rent`      | Daily 13:30                 | Scrape Baku commercial rent listings                 |
| `atlas:embed-listings --source=binaz_*`  | Daily 14:00–15:30           | Embed all active Baku categories for semantic search |
| myhome.ge schedules                      | ⬜ Thread 19                | 9 categories, 16:00–23:55, after test data wipe      |
| `scripts/backup-db.sh`                   | Daily 03:00 UTC (host cron) | pg_dump → B2 via rclone                              |

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

A full Postman collection is available at [`postman/atlas.postman_collection.json`](postman/atlas.postman_collection.json). Import it, set `base_url`, `market` (yerevan/baku/tbilisi) and `token` variables and every endpoint is ready to use.

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
