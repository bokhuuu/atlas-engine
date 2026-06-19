# 🗺️ Atlas - Colliers Yerevan Market Intelligence Platform

Atlas scrapes Armenian real estate listings, cleans the data automatically and turns it into things a Colliers analyst can use: a public market snapshot, an internal dashboard, a price-estimate tool, an AI assistant that answers questions from real data and automated reports.

Built by merging three internal templates - a scraping engine, an AI/LLM integration layer and an admin dashboard skeleton - into one single Laravel application.

> **Project status:** active development, pre-launch. Not yet handed to the client.
> **Full architecture, decisions, and current status:** see [`ATLAS.md`](./ATLAS.md) - read that file first, it's the living source of truth for this project.

---

## Tech Stack

- **Backend:** Laravel (PHP), layered architecture (Controller → Service → Repository → Model)
- **Frontend:** Inertia.js + React + TypeScript, Tailwind
- **Auth/permissions:** Fortify + Sanctum, Spatie roles/permissions
- **AI layer:** Prism → OpenRouter
- **Database:** MySQL 8.0
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
