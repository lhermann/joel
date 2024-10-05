# Joel – The media archive Wordpress theme by Joel Media Ministry e.V.

Built with [tonik/theme](https://github.com/tonik/theme)

## Deploy

DO NOT DEPLOY
host.42lh.com is too outdated. The deploy will fail and break.

## Develop

1. Start Wordpress on port 8080
2. Run `npm run build` so that PHP can find the assets in the dist folder
3. Run `npm run dev` an access the dev environment at http://localhost:8081

## Requirements

* WordPress >= 4.7
* PHP >= 7.0
* [Composer](https://getcomposer.org)
* [Node.js](https://nodejs.org)

## Documentation

Comprehensive documentation of the starter is available at http://labs.tonik.pl/theme/

## Update translation files

1. Run `./resources/languages/make-pot.sh`
2. Ignore deprecation warnings
3. This should show up: `Success: POT file successfully generated!`
4. Open PoEdit
5. Open `resources/languages/de_DE.po`
6. Translation -> Update from POT file
