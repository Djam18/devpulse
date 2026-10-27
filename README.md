<p align="center">
  <br />
  <h1 align="center">⚡ DevPulse</h1>
  <p align="center">
    <strong>The high-velocity developer productivity suite built for demanding engineers & freelancers.</strong>
  </p>
  <p align="center">
    Atomic Live Time Tracker · Sprint Kanban Board · Pomodoro Focus Engine · GitHub OAuth · Versioned REST API v1
  </p>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.3" />
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 11" />
  <img src="https://img.shields.io/badge/Vue-3.x-4FC08D?style=flat-square&logo=vuedotjs&logoColor=white" alt="Vue 3" />
  <img src="https://img.shields.io/badge/Inertia.js-2.x-9553E9?style=flat-square&logo=inertia&logoColor=white" alt="Inertia.js" />
  <img src="https://img.shields.io/badge/TypeScript-5.x-3178C6?style=flat-square&logo=typescript&logoColor=white" alt="TypeScript" />
  <img src="https://img.shields.io/badge/PostgreSQL-17-4169E1?style=flat-square&logo=postgresql&logoColor=white" alt="PostgreSQL 17" />
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=flat-square&logo=docker&logoColor=white" alt="Docker Ready" />
  <img src="https://img.shields.io/badge/Tests-66%20Passed-10B981?style=flat-square" alt="Tests" />
  <img src="https://img.shields.io/badge/License-MIT-blue?style=flat-square" alt="License MIT" />
</p>

---

## 📸 Interface Preview

<p align="center">
  <img src="docs/screenshots/dashboard.png" alt="DevPulse Dashboard & Live Time Tracker" width="95%" />
  <br />
  <em>High-velocity dashboard with live real-time timer, sprint analytics, and project distribution</em>
</p>

<p align="center">
  <img src="docs/screenshots/kanban-board.png" alt="DevPulse Sprint Kanban Board" width="95%" />
  <br />
  <em>Responsive Kanban board with visual priority tokens, one-click integrated timer, and sprint filters</em>
</p>

<p align="center">
  <img src="docs/screenshots/kanban-dark.png" alt="DevPulse Soft Dark Theme" width="95%" />
  <br />
  <em>Eye-strain-free "Soft Dark" theme crafted for late-night deep work sessions</em>
</p>

<p align="center">
  <img src="docs/screenshots/welcome-hero.png" alt="DevPulse Welcome Hero Landing" width="95%" />
  <br />
  <em>High-conversion landing page featuring an interactive live product showcase and 1-click GitHub OAuth</em>
</p>

---

## 💡 Why DevPulse?

Most project management tools (Jira, ClickUp) are bloated, slow, and resource-heavy. Standalone time trackers (Toggl, Clockify) create constant context-switching that disrupts flow state.

**DevPulse unifies everything into an ultra-fast (< 100ms) single-page experience**:
1. **Atomic Live Time Tracker**: Always accessible in the persistent top bar, triggerable via keyboard shortcuts or with a single click on any Kanban card.
2. **Responsive Sprint Kanban Board**: 3-column flow (`Todo`, `In Progress`, `Done`), visual priorities, and instant project filtering.
3. **Anti-Burnout Pomodoro Engine**: Calibrated 25-minute focus blocks with tracked vs estimated tomato cycle counts.
4. **1-Click GitHub OAuth**: Passwordless instant sign-up with strict verified email enforcement and avatar synchronization.
5. **"Plum & Peach" Design System**: Pure white high-contrast default theme meeting WCAG AA standards, alongside an ergonomic Soft Dark mode.
6. **Dedicated Mailables & Mailpit Integration**: Transactional emails rendered with custom responsive templates and captured locally on `http://localhost:8025`.

---

## 🏛️ Architecture & Clean Code

DevPulse strictly adheres to modern software design patterns and Laravel ecosystem best practices:
* **Thin Controllers**: HTTP controllers merely handle request adaptation and immediately delegate to single-purpose **Domain Actions** (`app/Actions/*`).
* **Concurrency Locking (`lockForUpdate`)**: Starting a timer runs inside an atomic PostgreSQL database transaction to eliminate duplicate running sessions.
* **DTOs & Strict Typing**: Immutable Data Transfer Objects guarantee schema integrity between HTTP requests and the domain layer.
* **Dedicated Mailables (`app/Mail/*`)**: Clean decoupling of mail presentation from notifications, with local browser preview routes.
* **Versioned REST API (`/api/v1`)**: Fully secured with Laravel Sanctum tokens and documented via OpenAPI specifications at `/docs/api`.

---

## 🚀 Quick Start

### Option A — Using Docker (Recommended, Single Command)

Prerequisites: [Docker](https://docs.docker.com/get-docker/) and Docker Compose installed.

```bash
# 1. Clone the repository
git clone https://github.com/Djam18/devpulse.git
cd devpulse

# 2. Start all containers (App, PostgreSQL 17, Redis, Mailpit)
docker compose up -d

# 3. Access in your browser:
# Main Application: http://localhost:8001
# Mailpit Webmail:  http://localhost:8025
# Email Previews:   http://localhost:8001/mail-preview/welcome
```

The Docker entrypoint automatically configures `.env`, generates `APP_KEY`, waits for PostgreSQL to become healthy, and executes database migrations.

---

### Option B — Traditional Local Installation

Prerequisites: PHP 8.3+, Composer, Node.js 22+, PostgreSQL 17.

```bash
# 1. Clone and install dependencies
git clone https://github.com/Djam18/devpulse.git
cd devpulse
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Set your PostgreSQL credentials in .env, then run migrations:
php artisan migrate

# 4. Build frontend assets and start servers
npm run build
php artisan serve --port=8001
```

Visit [http://localhost:8001](http://localhost:8001).

---

## 🔑 GitHub OAuth Setup (Optional)

To enable 1-click GitHub authentication:
1. Create a new OAuth App at [github.com/settings/applications/new](https://github.com/settings/applications/new).
2. Set the **Authorization callback URL** to:
   `http://localhost:8001/auth/github/callback`
3. Add your client credentials to `.env`:
   ```env
   GITHUB_CLIENT_ID=your_github_client_id
   GITHUB_CLIENT_SECRET=your_github_client_secret
   GITHUB_REDIRECT_URI=http://localhost:8001/auth/github/callback
   ```

---

## 🧪 Testing & Code Quality

The codebase is backed by **66 automated tests (Feature & Unit)** with 100% assertions green:

```bash
# Run the complete test suite
php artisan test --compact

# Check code formatting (Laravel Pint)
vendor/bin/pint --test

# Type-check Vue components and compile Vite assets
npm run build

# Run automated E2E browser tests (Playwright)
npx playwright test tests/e2e/auth.spec.ts
```

---

## 📬 Transactional Email Previews

In local development mode, you can inspect custom responsive email templates directly in your browser:
* **Welcome Email**: [http://localhost:8001/mail-preview/welcome](http://localhost:8001/mail-preview/welcome)
* **Prolonged Timer Alert**: [http://localhost:8001/mail-preview/timer](http://localhost:8001/mail-preview/timer)
* **Weekly Performance Summary**: [http://localhost:8001/mail-preview/weekly](http://localhost:8001/mail-preview/weekly)

---

## 📖 API Documentation

A complete OpenAPI / Swagger interactive API specification is built into the application:
* **Interactive UI**: [http://localhost:8001/docs/api](http://localhost:8001/docs/api)
* **Raw JSON Specification**: [http://localhost:8001/docs/api.json](http://localhost:8001/docs/api.json)

---

## 📦 Tech Stack

| Domain | Technology |
|---|---|
| **Backend** | PHP 8.3 · Laravel 11.x · Laravel Sanctum · Laravel Socialite |
| **Frontend** | Vue 3 (Composition API) · Inertia.js v2 · TypeScript · Pinia · Tailwind CSS v3 |
| **Database** | PostgreSQL 17 (Optimized JSONB indexes, atomic locks & foreign keys) |
| **Cache & Queues** | Redis (Valkey) |
| **Tooling & Quality** | PHPUnit 12 · Laravel Pint · vue-tsc · Vite 8 · Playwright · Lucide Icons |
| **Containerization** | Multi-stage Dockerfile · Docker Compose |

---

## 🤝 Contributing & License

Contributions are always welcome! To propose a change:
1. Fork the repository.
2. Create your feature branch (`git checkout -b feature/amazing-feature`).
3. Ensure all tests pass (`php artisan test`).
4. Commit your changes following [Conventional Commits](https://www.conventionalcommits.org/).
5. Open a Pull Request.

Distributed under the **MIT** License. See `LICENSE` for more information.
