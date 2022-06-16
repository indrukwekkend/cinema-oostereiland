# Indrukwekkend Thema
Dit Sage 9 thema staat in FTP verbinding met de test server "http://indrukwekkendplatform.local/"

## Sage bijzonderheden
Je hebt op de server verbinding nodig met SSH op de server om een composer install te draaien. 
Kijk bij SG hoe je je locale machine toegang kan geven door een sleutelpaar aan te maken. 

## Git
Dit Thema staat op Git en dient gelijk gehouden te worden. 

## FTP 
Siteground

## NPM version 13
gebruik nvm use 13 om de compiler goed te laten draaien. 

Met ignore in de vscode kan je de bestanden over slaan. Upload on save maakt het dat het online en local gesynchroniseerd is. Ander synchroniseren via "Sync"

## FTP instructies
Zorg dat het locale pad (van de werkmap) gelijk is aan het pad dat is ingesteld op de server dan kan je alles synchroon houden.

{
    "name": "Indrukwekkend server",
    "host": "35.214.165.65",
    "protocol": "ftp",
    "port": 21,
    "username": "******",
    "remotePath": "/public_html/concept/wp-content/",
    "password": "******************",
    "ignore": [
        ".vscode",
        ".git",
        ".DS_Store",
        "themes/concept/vendor",
        "themes/concept/node_modules"
    ],
    "uploadOnSave": true
}

## Fontawsome 
Kit fontawesome in de head.blade toegevoegd als de makkelijkste optie om dit te doen.
zie: fontawesome link

## ACF
Verschillende instellingen staan in ACF. Deze is dan ook vereist en staat in MU-Plugins. 

## Header 
Nieuw menu, settings op de maximum breedte: stel in variables de "header width" in om gelijk te zijn aan de content of de breedte van het scherm.
Link naar content voor screenreaders (accessability)

## main content (align)
Allign in te stellen in de variables

## Footer 
Mobile menu (uitklapbaar);
Breedte van de footer gekoppeld aan breedte van de header
ACF titel instelleingen
ACF socials instellingen

# Toevoegingen per versie:
Versie nummering van Sage + aanassingen van Indrukwekkend
9.0.10 + indrukwekkend 1.0

## versie 1.2
Block editor js toegevoegd in composer en in admin.php
Butotn style in de js
customizer code gekopieerd van 2021 (in classes)
- hierin zit een simple toggle om transparante navigatie te maken
Button styles toegevoegd. 
Patronen toegevoegd voor nieuwe header



## versie 1.1
Teogevoegd:
Resources/classes  Testen van het aanpassen van de Customizer: Transparantie in de navigatie ingesteld
Block patterns: 
- speciefiek patroon ingesteld om een "standaard" achtergrond header block te vullen met speciefieke elemanten en een block style
  -- app/block-styles
- Indrukwekkend Block scss toegevoegd in de scss mappen 
  - - resourses/styles/06-ind-blokken
- Klant scss toegevoegd 
- 


## versie 1.0
Toegevoegd:
app/block-patterns
app/block-styles
Colorscheme

  ### Blockpatterns 
  Het toevoegen van patronen in het thema, zorgt voor een "thema voorinstelling". Hiermee kunnen we dus een patroon toevoegen
  in een pagina. De patronen bestaan uit een serie van blokken die net als een template worden geplaatst. 
  De patronen die er nu opstaan zijn gecopieerd van 2011 theme van WP

  ### Block styles
  Door filters toe te voegen kun je op blokken een andere style toevoegen. Hierdoor dit voegt een style class toe aan welk blok dan ook. 
  Hiermee kun je dus makkelijk eigen stijlen toevoegen aan het thema per blok waar nodig. 
  Dit is bij uitstek ook geschikt om te combineren met de Blockpatterns, door de stylen daar van te voren in te stellen, dan staan ze gelijk goed.

  ### Colorscheme
  Aanpassingen aan het kleurenschema: variabelen toegevoegd + expreriment met het gebruiken van een Gradient gebaseerd op het kleurenschema.




# [Sage](https://roots.io/sage/)
[![Packagist](https://img.shields.io/packagist/vpre/roots/sage.svg?style=flat-square)](https://packagist.org/packages/roots/sage)
[![devDependency Status](https://img.shields.io/david/dev/roots/sage.svg?style=flat-square)](https://david-dm.org/roots/sage#info=devDependencies)
[![Build Status](https://img.shields.io/travis/roots/sage.svg?style=flat-square)](https://travis-ci.org/roots/sage)

Sage is a WordPress starter theme with a modern development workflow.

## Features

* Sass for stylesheets
* Modern JavaScript
* [Webpack](https://webpack.github.io/) for compiling assets, optimizing images, and concatenating and minifying files
* [Browsersync](http://www.browsersync.io/) for synchronized browser testing
* [Blade](https://laravel.com/docs/5.6/blade) as a templating engine
* [Controller](https://github.com/soberwp/controller) for passing data to Blade templates
* CSS framework (optional): [Bootstrap 4](https://getbootstrap.com/), [Bulma](https://bulma.io/), [Foundation](https://foundation.zurb.com/), [Tachyons](http://tachyons.io/), [Tailwind](https://tailwindcss.com/)

See a working example at [roots-example-project.com](https://roots-example-project.com/).

## Requirements

Make sure all dependencies have been installed before moving on:

* [WordPress](https://wordpress.org/) >= 4.7
* [PHP](https://secure.php.net/manual/en/install.php) >= 7.1.3 (with [`php-mbstring`](https://secure.php.net/manual/en/book.mbstring.php) enabled)
* [Composer](https://getcomposer.org/download/)
* [Node.js](http://nodejs.org/) >= 8.0.0
* [Yarn](https://yarnpkg.com/en/docs/install)

## Theme installation

Install Sage using Composer from your WordPress themes directory (replace `your-theme-name` below with the name of your theme):

```shell
# @ app/themes/ or wp-content/themes/
$ composer create-project roots/sage your-theme-name
```

To install the latest development version of Sage, add `dev-master` to the end of the command:

```shell
$ composer create-project roots/sage your-theme-name dev-master
```

During theme installation you will have options to update `style.css` theme headers, select a CSS framework, and configure Browsersync.

## Theme structure

```shell
themes/your-theme-name/   # → Root of your Sage based theme
├── app/                  # → Theme PHP
│   ├── Controllers/      # → Controller files
│   ├── admin.php         # → Theme customizer setup
│   ├── filters.php       # → Theme filters
│   ├── helpers.php       # → Helper functions
│   └── setup.php         # → Theme setup
├── composer.json         # → Autoloading for `app/` files
├── composer.lock         # → Composer lock file (never edit)
├── dist/                 # → Built theme assets (never edit)
├── node_modules/         # → Node.js packages (never edit)
├── package.json          # → Node.js dependencies and scripts
├── resources/            # → Theme assets and templates
│   ├── assets/           # → Front-end assets
│   │   ├── config.json   # → Settings for compiled assets
│   │   ├── build/        # → Webpack and ESLint config
│   │   ├── fonts/        # → Theme fonts
│   │   ├── images/       # → Theme images
│   │   ├── scripts/      # → Theme JS
│   │   └── styles/       # → Theme stylesheets
│   ├── functions.php     # → Composer autoloader, theme includes
│   ├── index.php         # → Never manually edit
│   ├── screenshot.png    # → Theme screenshot for WP admin
│   ├── style.css         # → Theme meta information
│   └── views/            # → Theme templates
│       ├── layouts/      # → Base templates
│       └── partials/     # → Partial templates
└── vendor/               # → Composer packages (never edit)
```

## Theme setup

Edit `app/setup.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, and sidebars.

## Theme development

* Run `yarn` from the theme directory to install dependencies
* Update `resources/assets/config.json` settings:
  * `devUrl` should reflect your local development hostname
  * `publicPath` should reflect your WordPress folder structure (`/wp-content/themes/sage` for non-[Bedrock](https://roots.io/bedrock/) installs)

### Build commands

* `yarn start` — Compile assets when file changes are made, start Browsersync session
* `yarn build` — Compile and optimize the files in your assets directory
* `yarn build:production` — Compile assets for production

## Documentation

* [Sage documentation](https://roots.io/sage/docs/)
* [Controller documentation](https://github.com/soberwp/controller#usage)

## Contributing

Contributions are welcome from everyone. We have [contributing guidelines](https://github.com/roots/guidelines/blob/master/CONTRIBUTING.md) to help you get started.

## Sage sponsors

Help support our open-source development efforts by [becoming a patron](https://www.patreon.com/rootsdev).

<a href="https://kinsta.com/?kaid=OFDHAJIXUDIV"><img src="https://cdn.roots.io/app/uploads/kinsta.svg" alt="Kinsta" width="200" height="150"></a> <a href="https://k-m.com/"><img src="https://cdn.roots.io/app/uploads/km-digital.svg" alt="KM Digital" width="200" height="150"></a> <a href="https://www.itineris.co.uk/"><img src="https://cdn.roots.io/app/uploads/itineris.svg" alt="itineris" width="200" height="150"></a> <a href="http://www.hbgdesignlab.se/"><img src="https://cdn.roots.io/app/uploads/helsingborgdesignlab.png" alt="Helsingborg Design LAB" with="200" height="150">

## Community

Keep track of development and community news.

* Participate on the [Roots Discourse](https://discourse.roots.io/)
* Follow [@rootswp on Twitter](https://twitter.com/rootswp)
* Read and subscribe to the [Roots Blog](https://roots.io/blog/)
* Subscribe to the [Roots Newsletter](https://roots.io/subscribe/)
* Listen to the [Roots Radio podcast](https://roots.io/podcast/)
