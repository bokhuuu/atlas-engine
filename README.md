# 🗺️ Atlas - Colliers Yerevan Market Intelligence Platform

Atlas scrapes Armenian real estate listings, cleans the data automatically and turns it into things a Colliers analyst can use: a public market snapshot, an internal dashboard, an AI assistant that answers questions from real data (now also covering comparable-listing lookup, what was originally planned as a separate valuation tool) and automated reports.

Built by merging three internal templates - a scraping engine, an AI/LLM integration layer and an admin dashboard skeleton - into one single Laravel application.

> **Project status:** active development. Data layer complete, public market snapshot (Screen 0) live, internal dashboard and scraper control panel (Screen 1) live, district-level market stats live, AI Assistant (Screen 3) live. Automated reports still ahead.

> **Full architecture, decisions, and current status:** see [`ATLAS.md`] - read that file first, it's the living source of truth for this project.

---

## Tech Stack

- **Backend:** Laravel (PHP), layered architecture (Controller → Service → Repository → Model)
- **Frontend:** Inertia.js + React + TypeScript, Tailwind, shadcn/Radix UI components
- **Auth/permissions:** Fortify + Sanctum (including stateful API auth for first-party dashboard requests), Spatie roles/permissions
- **AI layer:** Prism → OpenRouter, LarAgent (tool-calling agent framework), `anthropic/claude-haiku-4-5` for agent model
- **Database:** MySQL
- **Scraping:** Laravel Dusk + ChromeDriver (real headless browser - list.am's listings are JS-rendered)
- **Monitoring:** Sentry
- **DevOps:** Docker, GitHub Actions (CI)

## Local Setup

```bash
docker compose up -d --build
docker compose exec app bash scripts/setup.sh
npm install && npm run build
```

Then visit `http://localhost:8080`.

The setup script handles environment file creation, app key generation, migrations, database seeding, and storage linking in one pass.

## License

Private repository. Not for public distribution.
