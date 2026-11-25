# Atlas 🗺️

**Multi-market real estate intelligence platform**  
Yerevan · Baku · Tbilisi

---

## What it does

Atlas is a production Laravel platform that aggregates, normalises, and analyses commercial real estate listings across three South Caucasus markets. It is deployed for a multinational real estate consultancy operating across the region.

- Scrapes listings from market-specific portals (myhome.ge, bina.az, list.am and others) via a modular profile system built on `spatie/crawler`
- Deduplicates and normalises listings into a unified schema across currencies, area units, and address formats
- Runs AI-assisted valuation and market-snapshot generation via OpenAI (GPT-4o) and pgvector similarity search
- Exports structured Excel reports per market and category (land, residential, commercial)
- Exposes an internal admin panel (Inertia + React) with per-market dashboards, an AI assistant, and a scraper control interface
- Processes async jobs through Laravel Horizon backed by Redis

---

## Stack

| Layer     | Technology                                           |
| --------- | ---------------------------------------------------- |
| Framework | Laravel 12, PHP 8.4                                  |
| Frontend  | Inertia.js, React 19, TypeScript, Tailwind CSS 4     |
| Database  | PostgreSQL 16 + pgvector                             |
| Queue     | Redis + Laravel Horizon                              |
| AI        | OpenAI GPT-4o, pgvector embeddings                   |
| Scraping  | Spatie Crawler, Goutte, Browsershot                  |
| Testing   | Pest PHP                                             |
| CI        | GitHub Actions (pgvector + Redis service containers) |
| Deploy    | Docker, Apache, Vultr VPS                            |

---

## Architecture

Controllers → Services → Repositories

Strict layering — no queries in controllers, no business logic leaking into the HTTP layer.

Scraper module (app/Modules/Scraper/):

- Profiles/ — one class per portal, extending an abstract base per site family
- Processors/ — field normalisation (price, area, coordinates)
- Exports/ — PhpSpreadsheet report builders per market/category
- Jobs/ — dispatchable scrape and export jobs running through Horizon

AI module (app/Modules/AI/):

- Embedding generation and pgvector similarity search
- Market snapshot generation with structured GPT-4o output
- Conversational assistant with market-scoped context

---

## Configuration

All configuration is environment-driven — zero hardcoded values in application code.

Key environment variables (see .env.example):

MARKETS=yerevan,baku,tbilisi

PROXY_HOST=
PROXY_PORT=
PROXY_USER=
PROXY_PASS=

OPENAI_API_KEY=
OPENAI_MODEL=gpt-4o

HORIZON_AUTH_EMAIL=

ADMIN_EMAIL=
ADMIN_PASSWORD=

---

## Testing

527 tests, 986 assertions — run on host (not inside Docker):

php artisan test

CI runs the full suite on every push with PostgreSQL + pgvector and Redis service containers.

---

## CI

GitHub Actions runs:

- PHP 8.4 + Composer install
- PostgreSQL 16 with pgvector extension
- Redis
- Laravel migrations + Pest suite

See .github/workflows/ci-cd.yml.

---

## Key design decisions

- Config-driven markets — adding a new city is a config entry, not a code change
- Profile inheritance — shared scraping logic lives in abstract base classes; portal-specific overrides are minimal
- Rotating residential proxies — all scrape requests route through proxy to avoid blocks
- pgvector for similarity — embeddings stored in Postgres, no separate vector DB
- Horizon for observability — all queued jobs visible and retriable from the Horizon dashboard
- Pest for expressiveness — dataset-driven tests cover scraper normalisation edge cases

---

## Status

Production — actively maintained.
