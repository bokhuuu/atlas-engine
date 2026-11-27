# Atlas 🗺️

**Multi-market real estate intelligence platform**  
Yerevan · Baku · Tbilisi

Production system deployed for a multinational real estate consultancy operating across the South Caucasus.

---

## What it does

Atlas aggregates, normalises and analyses commercial real estate listings across three markets daily. It is the analytical backbone for a consultancy's regional operations — not a demo, not a side project.

- Scrapes listings from market-specific portals (myhome.ge, bina.az, list.am) across 26 property categories
- Deduplicates and normalises into a unified schema across currencies (AMD/AZN/GEL/USD), area units and address formats
- Tracks the full listing lifecycle from first appearance through price changes to removal
- Runs an AI assistant that answers market questions exclusively from live database records — never from model memory
- Exports structured Excel reports per market and category with computed analytics
- Processes all async work through Laravel Horizon backed by Redis, with a dual-queue architecture for browser vs HTTP workers

---

## Stack

| Layer      | Technology                                                              |
| ---------- | ----------------------------------------------------------------------- |
| Framework  | Laravel 12, PHP 8.4                                                     |
| Frontend   | Inertia.js, React 19, TypeScript, Tailwind CSS 4                        |
| Database   | PostgreSQL 16 + pgvector                                                |
| Queue      | Redis + Laravel Horizon                                                 |
| AI         | Claude (claude-sonnet-4-6) via Prism + OpenRouter, pgvector embeddings  |
| Scraping   | Laravel Dusk + ChromeDriver, Guzzle + DomCrawler, GraphQL API ingestion |
| Testing    | Pest PHP — 527 tests, 986 assertions                                    |
| CI         | GitHub Actions (pgvector + Redis service containers)                    |
| Deploy     | Docker, Apache, Vultr VPS                                               |
| Storage    | Backblaze B2 (screenshots + DB backups)                                 |
| Monitoring | Sentry, UptimeRobot, Telegram alerts                                    |

---

## Architecture

```
Controllers → Services → Repositories
```

Strict layering — no queries in controllers, no business logic leaking into the HTTP layer.

### Scraper module (`app/Modules/Scraper/`)

- `Profiles/` — one class per portal, extending an abstract base per site family. Adding a new portal = new profile class, no changes to core
- `Processors/` — field normalisation (price, area, coordinates, currency conversion)
- `Exports/` — PhpSpreadsheet report builders per market and category
- `Jobs/` — dispatchable scrape and export jobs running through Horizon

### AI module (`app/Modules/AI/`)

- Embedding generation and pgvector similarity search
- Market snapshot generation with structured Claude output
- Conversational assistant with 11 callable tools, scoped per market

### Queue architecture

Two Horizon supervisors running in parallel:

| Queue     | Workers | Used by                           |
| --------- | ------- | --------------------------------- |
| `default` | 2       | bina.az (Guzzle HTTP)             |
| `chrome`  | 1       | list.am + myhome.ge (Chrome/Dusk) |

The `chrome` queue runs a single worker — one Chrome session at a time, preventing session flooding and proxy detection.

---

## Markets and data sources

### Yerevan 🇦🇲 — list.am

7 categories: apartments (sale/rent), houses, commercial (sale/rent), land, new development. All currencies normalised to USD via daily exchange rate fetch.

### Baku 🇦🇿 — bina.az

10 categories across land, offices, apartments, houses and commercial (sale/rent).

Scraping approach: `POST https://ru.bina.az/graphql` — persisted query, cursor pagination, 16 listings per fetch. Detail pages fetched via server-rendered HTML.

Baku-specific fields:

- `has_bill_of_sale` — significantly affects land pricing, filterable in Data Hub
- `has_mortgage` — mortgage availability
- `is_new_building` — new build vs secondary (apartments only)
- `area_sotka` + `price_per_sotka_usd` — computed at export time for land listings

### Tbilisi 🇬🇪 — myhome.ge

9 categories across apartments, houses, country houses, land and commercial (sale/rent).

Scraping approach: myhome.ge is Next.js SSR with Cloudflare protection. All listing data is extracted from `__NEXT_DATA__` JSON embedded in the page — no DOM scraping required. Chrome (Dusk) handles JavaScript execution and Cloudflare.

---

## Listing lifecycle

Every listing is tracked end to end:

| Field                    | Description                                     |
| ------------------------ | ----------------------------------------------- |
| `scraped_at`             | When first collected                            |
| `last_seen_at`           | Last confirmed live                             |
| `last_checked_at`        | Last time the check-listings job ran against it |
| `disappeared_at`         | When first detected as gone (404)               |
| `days_on_market`         | Computed at export time                         |
| `status`                 | Active / Stale (90+ days) / Disappeared         |
| `translated_description` | English translation via Claude API              |
| `screenshot_url`         | Backblaze B2 path of detail page screenshot     |

---

## AI assistant (11 tools)

The assistant answers natural language questions against live database records. It never makes up data — every answer is grounded in the actual listings.

| Tool                   | Description                                                   |
| ---------------------- | ------------------------------------------------------------- |
| `getCategoryStats`     | Total listings + citywide median price/sqm                    |
| `getDistrictBreakdown` | Pricing by district for a category                            |
| `getDistrictStats`     | Full stats for a district — prices, size range breakdown      |
| `getLocationStats`     | Exact data for a specific location or keyword search fallback |
| `findComparables`      | Find listings by price, district, rooms                       |
| `getCategoryList`      | List all available categories                                 |
| `exportToExcel`        | Generate Excel report, return download link                   |
| `getPriceChanges`      | Districts with price reductions in last 7 days                |
| `getYieldEstimate`     | Estimated rental yield by district                            |
| `compareMarkets`       | Side-by-side comparison across all configured markets         |
| `semanticSearch`       | Natural language search via pgvector embeddings               |

Persistent per-user conversation history, scoped per market. Global assistant has access to all markets simultaneously.

---

## Excel export structure

All exports show raw listings — counts match source site exactly, no deduplication artefacts.

| Property type                 | Sheets                                |
| ----------------------------- | ------------------------------------- |
| Land                          | All Listings + Land Size Analysis     |
| Apartments / Houses / New Dev | All Listings + Room Type Analysis     |
| Offices / Commercial          | All Listings + Building Type Analysis |

Background export: Data Hub dispatches a queued job for Excel generation — no nginx timeout constraints regardless of dataset size.

---

## Scheduled jobs

| Command                      | Schedule                    | Description                                        |
| ---------------------------- | --------------------------- | -------------------------------------------------- |
| `atlas:capture-screenshots`  | Daily 00:00                 | Screenshot new listings via Screenshotone API → B2 |
| `scraper:clean-zombies`      | Daily 03:00                 | Auto-mark stale scraper runs as failed             |
| `atlas:fetch-exchange-rates` | Daily 04:00                 | Fetch AMD/AZN/GEL/EUR rates                        |
| `scraper:run listam_*`       | Mon/Wed/Fri 05:00–06:00     | All 7 Yerevan categories                           |
| `scraper:run binaz_*`        | Daily 12:00–13:30           | All active Baku categories                         |
| `atlas:embed-listings`       | Daily 14:00–15:30           | Embed active listings for semantic search          |
| `scripts/backup-db.sh`       | Daily 03:00 UTC (host cron) | pg_dump → Backblaze B2 via rclone                  |

---

## Configuration

All configuration is environment-driven — zero hardcoded values in application code. Adding a new market is a single config entry in `config/scraper.php` — no code changes required.

Key environment variables (see `.env.example`):

```env
MARKETS=yerevan,baku,tbilisi

PROXY_HOST=
PROXY_PORT=
PROXY_USER=
PROXY_PASS=

OPENAI_API_KEY=
OPENROUTER_API_KEY=

HORIZON_AUTH_EMAIL=

ADMIN_EMAIL=
ADMIN_PASSWORD=
```

---

## Roles

| Role          | Access                                                                  |
| ------------- | ----------------------------------------------------------------------- |
| `super_admin` | Everything                                                              |
| `admin`       | All product screens + Users + Settings + Activity Log + API Tokens      |
| `editor`      | Intelligence screens only (Scraper Control, Market Stats, AI Assistant) |
| `viewer`      | 403 — no access                                                         |

---

## Testing

527 tests, 986 assertions. Run on host (not inside Docker):

```bash
php artisan test
```

CI runs the full suite on every push with PostgreSQL 16 + pgvector and Redis service containers. See `.github/workflows/ci-cd.yml`.

---

## Key design decisions

- **Config-driven markets** — one codebase serves all cities; a new market is a config entry, not a code change
- **Profile inheritance** — shared scraping logic in abstract base classes; portal-specific overrides are minimal
- **Dual-queue Horizon** — browser and HTTP scrapers run on separate supervisors to prevent Chrome session flooding
- **Rotating residential proxies** — all scrape requests route through proxy for reliability and avoid blocks
- **pgvector for similarity** — embeddings stored in Postgres, no separate vector DB
- **Claude via OpenRouter** — model routing through Prism keeps the AI layer provider-agnostic
- **Horizon for observability** — all queued jobs visible, monitorable and retriable from the dashboard
- **Pest for expressiveness** — dataset-driven tests cover scraper normalisation edge cases

---

## Status

Production — actively maintained.
