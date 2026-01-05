# Hail Studio

Hail Studio is a Laravel + Inertia (Vue 3) application. In this repo's current state, the implemented "module" is a **Components catalog**:

- Users authenticate via a simple **email + password** login.
- Each user belongs to an **Organization** via a **Membership** (with a role).
- Authenticated users can browse **Component Categories** and view **Components** in that category.
- A Component has:
  - `name`, `slug`, `position`
  - a preview **screenshot** (stored on the `public` disk)
  - a JSON **payload** (stored in Postgres as `json`, cast to an array in Laravel)
- Users can **copy the JSON payload to clipboard** from the UI.
- Users with the right roles can **create components** (upload screenshot + paste JSON payload).

> Notes:
> - Categories are seeded from `App\Support\ComponentCatalog`.
> - Multi-tenancy is implemented at the data layer (`organization_id` on categories/components). There is no organization switching UI in this repo; the "current organization" is derived from the user's latest membership.

---

## Tech stack

- **Laravel 12** (PHP **^8.2**)
- **Inertia.js** (Laravel adapter) + **Vue 3**
- **Vite 7** + `laravel-vite-plugin`
- **Tailwind CSS v4**
- **PostgreSQL** (`DB_CONNECTION=pgsql`)
- Database-backed **sessions**, **cache**, and **queues** (via standard Laravel tables)

---

## Requirements

- PHP **8.2+**
- Composer
- Node.js + npm (versions compatible with Vite 7)
- PostgreSQL 13+ (any recent Postgres should work)

---

## Quickstart (local development)

### 1) Install dependencies

```bash
composer install
npm install
```

### 2) Create your environment file

```bash
cp .env.example .env
```

Update these values in `.env` as needed:

- `APP_URL` (default `http://localhost:8000`)
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

Create the database in Postgres (example):

```sql
CREATE DATABASE hail_studio;
```

### 3) Generate app key

```bash
php artisan key:generate
```

### 4) Run migrations + seed

This will create:

- the standard Laravel tables (users, sessions, cache, jobs, etc.)
- organizations/memberships tables
- component_categories/components tables
- a default admin user + org + seeded component categories

```bash
php artisan migrate --seed
```

### 5) Create the storage symlink (required for component screenshots)

Components store screenshots on the public disk (e.g. `storage/app/public/...`) and serve them via `/storage/...`.

```bash
php artisan storage:link
```

### 6) Run the app (two terminals)

Terminal A (Laravel):

```bash
php artisan serve
```

Terminal B (Vite):

```bash
npm run dev
```

Then open:

- Home: `http://localhost:8000/`
- Login: `http://localhost:8000/login`
- Components: `http://localhost:8000/components`

---

## Default login (after seeding)

The database seeder creates an initial admin user:

- Email: `admin@hail.studio`
- Password: `password`

Seeder location: `database/seeders/DatabaseSeeder.php`.

---

## Roles & permissions

Membership roles are defined in `App\Models\Membership`:

- OWNER
- ADMIN
- EDITOR
- VIEWER

Creating components (POST `/components`) is authorized by `App\Http\Requests\StoreComponentRequest`:

- Allowed: OWNER, ADMIN, EDITOR
- Not allowed: VIEWER

---

## Key routes

Defined in `routes/web.php`:

- `GET /` -> Home (Inertia page)
- `GET /login` / `POST /login` -> session auth
- `POST /logout`
- `GET /components/{category?}` -> browse components by category slug (optional)
- `POST /components` -> create a component (role-gated)

---

## Data model (tables)

Domain tables created by migrations:

`organizations`

- id, name, slug (unique)

`memberships`

- user_id, organization_id, role
- unique (user_id, organization_id)

`component_categories`

- organization_id, name, slug, position
- unique (organization_id, slug)

`components`

- organization_id, component_category_id
- name, slug, image_url, payload (json), position
- unique (organization_id, slug)
- unique (organization_id, component_category_id, position) (added in `database/migrations/2026_01_04_213153_add_component_position_unique_index.php`)

Standard Laravel tables are also present (users, sessions, cache, jobs, etc.).

---

## Creating components (what the backend expects)

The create endpoint validates (`App\Http\Requests\StoreComponentRequest`) the following fields:

- name (string, max 120)
- component_category_id (must exist for the user's organization)
- payload (valid JSON)
- screenshot (image: jpg/jpeg/png/webp, max 4096 KB)

Screenshots are stored under:

`storage/app/public/component-previews/{organizationId}/...`

and served via:

`/storage/component-previews/{organizationId}/...`

---

## Scripts

### npm

From `package.json`:

- `npm run dev` -> start Vite dev server
- `npm run build` -> build production assets

### composer

From `composer.json`:

- `composer run setup` -> install deps, create `.env` if missing, generate key, migrate, build assets
- `composer run test` -> run Laravel tests
- `composer run dev` -> defined, but in this repo snapshot the command string appears truncated; if it fails, use the "two terminals" approach above (`php artisan serve` + `npm run dev`).

---

## Testing & code style

Run tests:

```bash
php artisan test
```

Format PHP (Laravel Pint is installed):

```bash
./vendor/bin/pint
```

---

## Project structure (high-level)

- `app/Http/Controllers/*` -- Auth + Components controllers
- `app/Http/Requests/StoreComponentRequest.php` -- create validation + authorization
- `app/Models/*` -- Organization, Membership, ComponentCategory, Component, User
- `app/Support/ComponentCatalog.php` -- seeded category definitions
- `database/migrations/*` -- schema
- `database/seeders/*` -- default org/user + seeded categories
- `resources/js/Pages/*` -- Inertia pages (Home, Auth/Login, Components/Index)
- `resources/js/Components/*` -- UI components (tiles, sidebar, add modal, toast)
- `resources/views/app.blade.php` -- Inertia root view (+ Font Awesome CDN)
