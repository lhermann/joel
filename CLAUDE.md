# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

WordPress theme for Joel Media Ministry (joelmediatv.de) — a media archive with recordings, livestream, events, and search. Uses Vue 3 + Vite frontend integrated into a PHP theme based on the Tonik Gin framework.

## Development Commands

```bash
npm run dev      # Start Vite dev server (proxies to WordPress at localhost:8080)
npm run build    # Build for production (outputs to dist/)
npm run preview  # Preview production build
```

Full stack requires Docker from the parent directory: `docker compose up -d` starts WordPress (port 8081) + MariaDB.

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

### PHP Structure (Tonik Gin Framework)

- **`config/app.php`** — Theme switches (livestream, slider, searchbar, etc.), URL prefixes, autoload list
- **`app/`** — Application code, autoloaded via `config/app.php`'s `autoload` array
  - `Setup/` — WordPress actions, filters, theme supports, Algolia search
  - `Structure/` — Custom post types (recordings, answers, events, speakers, series, podcasts), taxonomies, shortcodes
  - `Http/` — Asset loading (`assets.php`), REST API extensions (`restapi.php`), AJAX handlers, routes
  - `ACF/` — Advanced Custom Fields definitions
  - `Helper/` — Utility functions (recordings, Google API)
  - `Legacy/` — Old functionality maintained for compatibility
- **`resources/templates/`** — PHP view templates using `.tpl.php` extension
- **`bootstrap/`** — Theme initialization (Tonik Gin container setup)

### Styles

SCSS + Tailwind CSS, organized with ITCSS methodology in `styles/`:
- `settings/` → `tools/` → `elements/` → `objects/` → `components/` → `utilities/`
- Main entry: `styles/main.scss`, Tailwind entry: `styles/tailwind.css`
- Uses `sass-mq` for responsive breakpoints

### Build Output

Vite builds to `dist/` with a `manifest.json`. PHP reads this manifest in `app/Http/assets.php` to load the correct hashed asset filenames.

## Key Conventions

- Theme textdomain: `'joel'` (for i18n: `__('text', 'joel')`)
- WordPress constants (defined in `docker-compose.yml`) control theme switches: `LIVESTREAM`, `SLIDER`, `SEARCHBAR`, `COMMENTS`, etc.
- Vue component names use `Jo` prefix (e.g., `JoMedialist`, `JoSlider`, `JoBoogleMain`)
- Axios base URL is set to `/wp-json/` for WP REST API calls
