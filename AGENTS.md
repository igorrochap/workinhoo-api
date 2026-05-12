# Repository Guidelines

## Project Structure & Module Organization

This is a Laravel API. Application code lives in `app/`, with domain folders such as `Actions/`, `DTO/`, `Models/`, `Services/`, `Policies/`, `Events/`, and `Listeners/`. HTTP routes are in `routes/api.php`, `routes/auth.php`, and `routes/web.php`. Database migrations, factories, seeders, and CSV seed data are under `database/`. Tests are split into `tests/Feature`, `tests/Integration`, and `tests/Unit` when needed. Vite entry points are in `resources/js` and `resources/css`; public assets are served from `public/`. Docker configuration is in `Dockerfile`, `docker-compose.yml`, and `docker/`.

## Build, Test, and Development Commands

- `docker compose up -d --build`: build and start PHP-FPM, Nginx, and PostgreSQL. The API is served at `http://localhost:8000`.
- `composer run dev`: run the local Laravel server, queue listener, logs, and Vite concurrently for non-Docker development.
- `composer test` or `php artisan test`: clear config and run the Pest/PHPUnit suite.
- `./vendor/bin/pint --test`: check PHP formatting as CI does.
- `./vendor/bin/pint`: apply PHP formatting.
- `./vendor/bin/phpstan analyse --memory-limit=512M`: run Larastan static analysis at level 5.
- `npm run dev` / `npm run build`: start Vite or build frontend assets.

## Coding Style & Naming Conventions

Use PSR-4 namespaces from `composer.json`: `App\`, `Database\Factories\`, and `Database\Seeders\`. Follow Laravel conventions for migrations, seeders, factories, models, mailables, and console commands. Keep the existing Portuguese domain vocabulary, for example `CriaPrestador`, `NovoPortfolioDTO`, and `RecuperarSenhaListener`. `.editorconfig` requires UTF-8, LF endings, 4-space indentation, final newlines, and trimmed trailing whitespace; YAML uses 2 spaces. Run Laravel Pint before opening a PR.

## Testing Guidelines

Tests use Pest with the Laravel plugin. Place HTTP/API behavior tests in `tests/Feature`, action/service-level flows in `tests/Integration`, and isolated logic in `tests/Unit`. Name tests with the existing `*Test.php` pattern, such as `LoginTest.php` or `CriaPortfolioTest.php`. The test environment uses in-memory SQLite, array cache/session drivers, sync queues, and array mail, as defined in `phpunit.xml`.

## Commit & Pull Request Guidelines

Recent commits follow Conventional Commits, often scoped: `feat(auth): ...`, `feat(api): ...`, `chore(config): ...`, and `feat(docs): ...`. Keep messages imperative and concise; avoid committing unfinished `WiP` work. PRs should describe the change, list tests run, link related issues, and note API or migration impacts.

## Security & Configuration Tips

Do not commit `.env`, secrets, generated tokens, or local database dumps. For Docker, use `DB_HOST=workinhoo_db` in `.env`. Prefer `.env.example` for documenting required configuration changes.
