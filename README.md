# Hail Studio

Hail Studio is a web agency platform: an internal studio OS where teams curate reusable assets (like UI components) and standardize workflows (projects, checklists, templates, docs, client workspaces, and more).

The first module is **Components**: browse curated UI sections by category and copy the associated JSON payload to clipboard.

## Tech Stack

- Laravel + PHP
- Vue 3 + Inertia.js
- Vite
- Tailwind CSS
- PostgreSQL
- Eloquent ORM + Laravel migrations
- Authentication: session-based
- Authorization: Policies/Gates + RBAC roles (organization membership)

## App Structure

- Public landing:
  - `/` shows centered "Hail Studio"
  - Top navbar: "Hail Studio" (left) + hamburger (right)
  - Hamburger opens a full-screen mega menu (currently: link to Components)
- Authenticated app:
  - `/components` shows the component browser with left sidebar categories and a main panel of tiles
  - `/components/<category>` deep-links to a category and keeps selection on refresh

## Design System Notes

- Primary content containers should use `max-width: 1440px` via the `layout__container` utility class.
- Header inner container uses the BEM class `site-header__inner` for consistent targeting.
- Components page content wrapper uses the BEM class `components-page__content`.

## Components Module (MVP)

### UX

- Sidebar lists categories (Navbars, Footers, Heroes, Pricing, 404 Pages, etc.)
- Main panel displays component tiles:
  - Screenshot image
  - Name (e.g. "404 Pages 1", "Signup Sections 2")
  - Copy button
- Clicking Copy:
  - Copies JSON payload to clipboard
  - Shows toast: "Copied JSON to clipboard."

### Data Storage

Component items are stored in PostgreSQL:

- `payload` stored as JSON (JSONB recommended)
- `image_url` stored as a path or object storage URL
- Categories are stored in a table to control ordering and labels

Default catalog data is defined in `app/Support/ComponentCatalog.php` and seeded into the database.

## Multi-tenant Foundation

Even if Hail is the only tenant initially, the data model supports future client workspaces:

- `organizations`
- `users`
- `memberships` (user_id, organization_id, role)
- `roles/permissions` (RBAC)

## Authorization (RBAC)

Roles are assigned per organization membership:

- OWNER: full control
- ADMIN: manage users + content
- EDITOR: create/update content
- VIEWER: read-only

Authorization is enforced via:

- Laravel Policies/Gates (resource-level checks)
- Role helpers on memberships (e.g. editor/admin only for content mutation)

## Local Development

### Requirements

- PHP 8.2+
- Composer
- Node.js + pnpm/npm
- PostgreSQL

### Setup

1. Install PHP dependencies:
   - `composer install`
2. Install JS dependencies:
   - `pnpm install` (or `npm install`)
3. Configure environment:
   - copy `.env.example` to `.env`
   - set `DB_*` values for Postgres
4. Generate app key:
   - `php artisan key:generate`
5. Run migrations + seeders:
   - `php artisan migrate --seed`
6. Create the storage symlink for previews:
   - `php artisan storage:link`
7. Start dev servers:
   - `php artisan serve`
   - `pnpm dev`

### Dev Login

- Email: `admin@hail.studio`
- Password: `password`

## Adding a New Component Item

1. Use the Components page "Add Component" button to upload a screenshot and JSON payload.
2. (Optional) Update the catalog definitions in `app/Support/ComponentCatalog.php`.
3. Re-run the seeder to sync database records:
   - `php artisan db:seed --class=ComponentCatalogSeeder`

## Suggested Directory Layout

- `routes/web.php` (web routes)
- `app/Models/*` (Eloquent models)
- `database/migrations/*`
- `resources/js/`
  - `Pages/Components/Index.vue` (Inertia page)
  - `Components/*` (Vue components: Sidebar, Tile, MegaMenu, Navbar)
  - `lib/*` (registry helpers, clipboard utilities)

## Roadmap

- Admin UI for component CRUD (add/edit/delete/publish)
- Search and tagging across all categories
- Versioning for payloads + audit log for edits
- Additional modules: projects, SOPs, templates, checklists, assets
- Client workspaces + scoped permissions
