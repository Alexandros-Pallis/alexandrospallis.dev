# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

Personal site (alexandrospallis.dev), a Symfony 8.1 / PHP 8.5 app on the
[symfony-docker](https://github.com/dunglas/symfony-docker) template (FrankenPHP + Caddy, single-container by
default). The codebase is currently a near-stock Symfony skeleton plus EasyAdmin — most domain code does not exist
yet.

## Running the project

Everything runs via Docker Compose; there is no local (non-Docker) PHP setup. `Justfile` wraps the common commands:

```
just up        # docker compose up --wait
just down      # docker compose down --remove-orphans
just restart   # down then up
just build-fresh  # docker compose build --pull --no-cache
```

App is served at `https://localhost` (self-signed TLS cert). Run any PHP/Composer/Symfony command inside the `php`
container:

```
docker compose exec php bin/console <command>
docker compose exec php composer <command>
docker compose exec php bin/phpunit
```

A Mailpit service (dev only, from `compose.override.yaml`) catches outgoing mail.

### Dev container / firewall

`.devcontainer/` provides a sandboxed Dev Container for AI agents with an outbound firewall
(`.devcontainer/init-firewall.sh`, allowlist-based via `ipset`/`dnsmasq`). If a `curl`/`composer`/`npm` command fails
due to network restrictions, add the domain to the `ipset=` allowlist line in that script and rebuild the container.

## Tests

PHPUnit 13, config in `phpunit.dist.xml`, bootstrap `tests/bootstrap.php`, `APP_ENV=test` is forced. Test suite covers
`tests/`, source coverage is `src/`.

```
docker compose exec php bin/phpunit                              # all tests
docker compose exec php bin/phpunit tests/Path/To/SomeTest.php   # single file
docker compose exec php bin/phpunit --filter testMethodName      # single test
```

`failOnDeprecation`, `failOnNotice`, and `failOnWarning` are all enabled — deprecation warnings fail the suite.

## Code style

`.php-cs-fixer.dist.php` configures the `@auto` ruleset with risky rules disallowed, scanning the whole project
(default excludes `vendor/`). Note: the `php-cs-fixer` binary is **not** currently in `composer.json`/`vendor/bin` —
it needs to be installed (e.g. `composer require --dev friendsofphp/php-cs-fixer`) before it can be run.

## Architecture

- **Routing**: `config/routes.yaml` auto-imports all `#[Route]` attributes from `src/Controller/` via the
  `routing.controllers` resource. See `src/Controller/LuckyController.php` for the plain controller pattern.
- **Admin**: EasyAdmin is installed. `src/Controller/Admin/DashboardController.php` is the
  `#[AdminDashboard]` at `/admin` (route name `admin`); its routes are wired via `config/routes/easyadmin.yaml`
  (`easyadmin.routes` resource). Add CRUD controllers under `src/Controller/Admin/` and register them via
  `configureMenuItems()`.
- **Doctrine ORM**: attribute-mapped entities live in `src/Entity` (namespace `App\Entity`), underscore naming
  strategy, migrations in `migrations/`. `config/packages/doctrine.yaml` sets `PostgreSQLPlatform` identity-generation
  preferences (default recipe boilerplate) — **the actual dev database is MariaDB**: `compose.yaml` overrides
  `DATABASE_URL` to `mysql://...@mariadb:3306/...?serverVersion=mariadb-12.1.2`, which wins over the `postgresql://`
  default in `.env.example`. Both `pdo_mysql` and `pdo_pgsql` PHP extensions are installed.
- **Frontend**: Symfony AssetMapper (no Node bundler/build step) + Stimulus controllers (`assets/controllers/`,
  registered in `assets/controllers.json`) + Turbo + Twig Components. Twig components live under
  `templates/components/` with PHP classes in `App\Twig\Components\` (`config/packages/twig_component.yaml`).
- **Messenger**: configured with the Doctrine transport (`MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0`).
- **Security**: `config/packages/security.yaml` is essentially unconfigured — in-memory user provider only, no
  authenticators, empty `access_control`. The `dev` firewall pattern leaves `_profiler`, `_wdt`, `assets`, `build`
  unauthenticated.

## CI

`.github/workflows/ci.yaml` builds the Docker images (via `docker/bake-action`), brings the stack up with
`docker compose up --wait --no-build`, and smoke-tests with `curl` against `http://localhost` and the Mercure
endpoint. Steps for running PHPUnit, Doctrine migrations, and `doctrine:schema:validate` are present but **commented
out** — uncomment them once a test database / homepage route exists. A separate `lint` job runs `super-linter/slim`.
