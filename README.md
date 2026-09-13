# UnivNews

A modern, authoritative, and secure university-themed news portal built with Laravel. The platform provides a structured digital journalism space for university news, research, events, campus achievements, and editorial content, paired with a dedicated Content Management System (CMS) for approved authors and administrators.

The system uses an isolated role-based authentication and authorization architecture separating Public Readers, Authors, and Administrators.

> **Important:** UnivNews is an independent project and is **not an official university authentication system**. It does not depend on institutional SSO, SAML, Shibboleth, Azure AD, or other university identity providers.

## Appendix

Additional architectural details, authentication models, editorial workflows, and security principles:

### 1. Account Roles & Permissions

- **Guest (Unauthenticated Visitor):**
    - Browse public articles, categories, research, and tags.
    - Search public content.
    - _Restrictions:_ Cannot comment, rate, or access CMS/admin panels.
- **Reader (Public Registered User):**
    - Read published articles, browse public content.
    - Comment and rate articles (where enabled).
    - Manage personal account profile and upload profile avatar.
    - Sign in via Email/Password or Google OAuth.
    - Submit request to become an Author.
    - _Restrictions:_ Cannot access author CMS or admin features.
- **Author (Approved Writer):**
    - Access dedicated Author CMS (`/author/dashboard`, `/author/articles`).
    - Create, draft, edit, and submit articles for review.
    - Request article promotion/boost via Mayar payment gateway.
    - Manage author profile and biography.
    - _Restrictions:_ Cannot access admin management or approve other authors.
- **Admin (Editorial & System Administrator):**
    - Access dedicated Editorial Dashboard (`/admin/dashboard`).
    - Manage all articles, categories, tags, and publications.
    - Review author applications: approve, reject, or cancel pending approvals.
    - Manage boost pricing, application settings, and user sessions.
    - _Isolation:_ Accessible strictly via `/admin/sign-in`.

### 2. Authentication Architecture

#### Public & Reader Flow

```text
Guest
  │
  ├── Register (/register) ───────→ Reader
  │
  └── Continue with Google ───────→ Reader (password = NULL)
                                       │
                                       └── (Optional) Set Local Password
```

#### Reader-to-Author Request & Approval Flow

```text
Reader
   │
   └── Apply for Author Access (/author/apply)
              ↓
           Pending Application
              ↓
         Admin Review (/admin/authors)
          ┌────┴──────────────┐
          ↓                   ↓
       Approve              Reject
          ↓                   ↓
  Activation Email        Reader (Notified)
          ↓
   Set Password
          ↓
   Author Account
```

_(Admin can cancel approval anytime before the author sets their password, redirecting user back to reader status)_

#### Isolated Admin Sign-In

```text
/admin/sign-in
       ↓
     Admin (role = admin)
       ↓
Admin Editorial Dashboard
```

_Note: The public login `/login` is strictly for Readers and Authors. Public registration can NEVER create an Admin or Author directly._

### 3. Google OAuth & Account Safety

- Handled via **Laravel Socialite** through `/auth/google/redirect` and `/auth/google/callback`.
- Google authentication exclusively creates **Reader** accounts.
- Google accounts have `password = NULL` by default and can optionally create a local password without locking out Google sign-in.
- Existing email accounts cannot be silently hijacked by Google OAuth without security validation.

### 4. Design Guidelines & Typography

- **Headlines:** `Montserrat` (Bold, semi-bold for newspaper authority)
- **Body Text:** `Source Serif 4` (High-readability editorial serif)
- **Navigation & Labels:** `Work Sans` (Clean geometric sans-serif)
- **Shapes & Borders:** Sharp 0px border-radius containers and buttons for an authentic newspaper aesthetic. Circular shapes are strictly reserved for avatars and status indicators. 1px borders (`#c5c6cf`) without heavy drop shadows.

### 5. Useful Artisan Commands

- Check routes: `php artisan route:list`
- Run database migrations: `php artisan migrate`
- Seed initial data: `php artisan migrate --seed`
- Clear application cache: `php artisan optimize:clear`
- Symlink storage: `php artisan storage:link`
- Run automated test suite: `php artisan test`

### 6. Development & Database Safety

- **Never run `php artisan migrate:fresh` or `db:wipe`** on databases containing live or test data without explicit consent.
- Always make incremental changes, reuse existing controllers/models, and verify changes with `php artisan test`.
- Related detailed specifications are documented in `docs/` (`AUTHENTICATION.MD`, `Author Approval Flow — Spesifikasi.md`, `DESIGN.MD`, `NAVIGATION_UPDATE.MD`, etc.).

## Run Locally

Clone the project

```bash
  git clone https://github.com/UnivNews/UnivNews.git
```

Go to the project directory

```bash
  cd News_Web
```

Install PHP dependencies

```bash
  composer install
```

Install frontend dependencies

```bash
  npm install
```

Set up environment file

```bash
  cp .env.example .env
  php artisan key:generate
```

Run database migrations and seeders

```bash
  php artisan migrate --seed
```

Create storage symlink for uploaded media

```bash
  php artisan storage:link
```

Start the application servers (run in separate terminals):

Terminal 1 — Laravel backend server:

```bash
  php artisan serve
```

Terminal 2 — Vite frontend asset server:

```bash
  npm run dev
```

## Deployment

To deploy this project to production:

Optimize Laravel configuration and routes:

```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
```

Build production frontend assets:

```bash
  npm run build
```

Run migrations:

```bash
  php artisan migrate --force
```

Alternatively, deploy using Docker:

```bash
  docker compose up -d --build
```

## Environment Variables

To run this project, you will need to add the following environment variables to your `.env` file:

`APP_NAME`

`APP_ENV`

`APP_KEY`

`APP_DEBUG`

`APP_URL`

`DB_CONNECTION`

`DB_HOST`

`DB_PORT`

`DB_DATABASE`

`DB_USERNAME`

`DB_PASSWORD`

`GOOGLE_CLIENT_ID`

`GOOGLE_CLIENT_SECRET`

`GOOGLE_REDIRECT_URI`

`MAYAR_API_KEY`

`MAYAR_WEBHOOK_SECRET`

`CAPTCHA_SITE_KEY`

`CAPTCHA_SECRET_KEY`

## Features

- **Multi-Role Access Control**: Strict segregation between Public Guests, Readers, Authors, and Administrators.
- **Public News Portal**: High-speed browsing, category filtering, search, tags, reader comments, and rating.
- **Editorial CMS**: Dedicated author workspace for drafting, formatting, and submitting articles for editorial review.
- **Author Onboarding Workflow**: Application form, administrative review, one-time token verification, and password setup.
- **Approval Cancellation**: Admin capability to revoke unverified author approvals with immediate session redirection.
- **Isolated Admin Portal**: Dedicated `/admin/sign-in` gateway preventing public privilege escalation.
- **Google OAuth 2.0**: Seamless reader sign-in via Google with optional local password generation.
- **Article Monetization & Boosting**: Paid article promotion powered by Mayar payment gateway integration.
- **Profile & Avatar Management**: User avatar upload with client validation (JPG/PNG <= 2MB) and server-side compression.
- **Interactive UI/UX**: Unified branding, 3D book page loader, responsive navigation, and modal alerts.
- **Anti-Bot & Security Protections**: Rate limiting, CSRF protection, and optional CAPTCHA verification.

## Tech Stack

**Client:** Blade Templates, TailwindCSS, Alpine.js, Vite

**Server:** PHP 8.2+, Laravel 11, PostgreSQL (or SQLite for local testing)

**Authentication & Security:** Laravel Breeze, Laravel Socialite, Custom Role Middleware

![UnivNews Logo](docs/logo.png)

## Color Reference

| Color        | Hex                                                                |
| ------------ | ------------------------------------------------------------------ |
| Primary Navy | ![#00081e](https://via.placeholder.com/10/00081e?text=+) `#00081e` |
| Crimson      | ![#b71032](https://via.placeholder.com/10/b71032?text=+) `#b71032` |
| Background   | ![#fcf8f9](https://via.placeholder.com/10/fcf8f9?text=+) `#fcf8f9` |
| Surface      | ![#ffffff](https://via.placeholder.com/10/ffffff?text=+) `#ffffff` |
| Border       | ![#c5c6cf](https://via.placeholder.com/10/c5c6cf?text=+) `#c5c6cf` |
| Text Main    | ![#1b1b1c](https://via.placeholder.com/10/1b1b1c?text=+) `#1b1b1c` |

## Authors

- [@cnaresr](https://github.com/cnaresr)
- [@roihan](https://github.com/RoihansLab)
- [@valhalla](https://github.com/va11h411a)
