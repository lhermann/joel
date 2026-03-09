# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

WordPress theme for Joel Media Ministry (joelmediatv.de) — a media archive with recordings, livestream, events, and search. Uses Vue 3 + Vite frontend integrated into a PHP theme. This is a 10+ year old codebase undergoing incremental modernization.

**PHP requirement:** ^8.1

## Development Commands

```bash
npm run dev      # Start Vite dev server (proxies to WordPress at localhost:8080)
npm run build    # Build for production (outputs to dist/)
npm run preview  # Preview production build
composer install # Install PHP dependencies
```

Full stack requires Docker from the parent directory: `docker compose up -d` starts WordPress (port 8081) + MariaDB.

## Code Principles

- **Start simple, add complexity later** — avoid overengineering
- **Build incrementally** — get the basics working first, then enhance
- **YAGNI** — don't add features "just in case"
- **Ask before assuming** — clarify requirements before adding complexity
- **Prefer simple approaches** — add complexity only when necessary
- **Cover edge cases** — but document them clearly
- **Check existing patterns** before implementing new features
- **Verify dependencies** in composer.json / package.json before importing
- **Follow repository patterns** for new data modules

## Architecture

### Dual JavaScript Architecture

Two parallel JS systems, each with a main (frontend) and admin entry point:

- **`src-vue/`** — Vue 3 components (primary, actively developed)
- **`src-vanilla/`** — Legacy vanilla JS (being phased out, marked with TODO)

Entry points configured in `vite.config.js`: `vue-main`, `vue-admin`, `vanilla-main`, `vanilla-admin`.

### Vue Component Mounting

Vue components mount via `data-vue` attributes in PHP templates (not a SPA). The `src-vue/instantiate.js` system scans the DOM for `[data-vue="ComponentName"]` elements and creates isolated Vue app instances. Props are passed via `data-params` and `data-options` JSON attributes on the HTML element.

```html
<!-- In PHP template -->
<div data-vue="JoMedialist" data-params='{"type":"video"}'></div>
```

Global config available via `this.$joel` (from `window._joel`).

### PHP Structure

The theme was originally built on the Tonik Gin framework (now removed). The `Tonik\Theme\App` namespace is preserved throughout the codebase — it's just a namespace, not a dependency.

**Bootstrap flow:** `functions.php` → loads `app/helpers.php` → loads `config/app.php` → auto-loads all files in `app/` listed in config's `autoload` array.

**Core helpers** (`app/helpers.php`) — the backbone of the PHP side:
- `config($key)` — access theme configuration from `config/app.php`
- `template($file, $data)` — render a `.tpl.php` template with data passed via `extract()`
- `template_path($file)` — get relative path to a template
- `asset($file)` / `asset_path($file)` — resolve files in `dist/` to URIs or filesystem paths
- `theme($key)` — service container (used for 'slides' service)

**Directory layout:**
- **`config/app.php`** — Theme switches (livestream, slider, searchbar, etc.), URL prefixes, autoload list
- **`app/`** — Application code, autoloaded via `config/app.php`'s `autoload` array
  - `Setup/` — WordPress actions, filters, theme supports, Algolia search
  - `Structure/` — Custom post types (recordings, answers, events, speakers, series, podcasts), taxonomies, shortcodes
  - `Http/` — Asset loading (`assets.php`), REST API extensions (`restapi.php`), AJAX handlers, routes
  - `ACF/` — Advanced Custom Fields definitions
  - `Helper/` — Utility functions (recordings, Google API)
  - `Legacy/` — Old functionality maintained for compatibility
- **`resources/templates/`** — PHP view templates using `.tpl.php` extension
- **`bootstrap/`** — Compatibility checks (PHP version)

### Template System

Templates use the `template()` helper which supports two calling patterns:

```php
// Simple: render a specific template
template('partials/header');

// Named: array joins with '-', renders "single-page.tpl.php"
template(['single', 'page']);

// With data: variables are extracted into the template scope
template('partials/searchform', ['style_modifier' => 'c-search-bar--primary']);
// → inside template: $style_modifier is available directly
```

All templates get a default `$style_modifier = ''` via the `tonik/gin/template/context/` filter in `Setup/filters.php`.

### Styles

SCSS + Tailwind CSS, organized with ITCSS methodology in `styles/`:
- `settings/` → `tools/` → `elements/` → `objects/` → `components/` → `utilities/`
- Main entry: `styles/main.scss`, Tailwind entry: `styles/tailwind.css`
- Uses `sass-mq` for responsive breakpoints

### Build Output

Vite builds to `dist/` with hashed filenames. `app/Http/assets.php` scans `dist/assets/` to match entry points to their hashed output files. During dev, the Vite proxy serves source files directly.

## Key Conventions

- Theme textdomain: `'joel'` (for i18n: `__('text', 'joel')`)
- WordPress constants (defined in `docker-compose.yml`) control theme switches: `LIVESTREAM`, `SLIDER`, `SEARCHBAR`, `COMMENTS`, etc.
- Vue component names use `Jo` prefix (e.g., `JoMedialist`, `JoSlider`, `JoBoogleMain`)
- Axios base URL is set to `/wp-json/` for WP REST API calls
- PHP files use `Tonik\Theme\App\*` namespaces (historical, not a dependency)

## Modernization Notes

See `.claude/php-modernization.md` for the ongoing modernization tracking doc. Key items:
- Tonik Gin framework: **removed** (replaced with self-contained helpers)
- PHP version: **^8.1** (updated from >=7.0)
- `app/Legacy/` contains old code that may have further deprecation issues
