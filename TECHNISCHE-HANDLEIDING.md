# Technische handleiding — Cinema Oostereiland

Handleiding voor collega’s die dit project moeten onderhouden of live zetten wanneer de vaste developer niet beschikbaar is.

**Laatst bijgewerkt:** september 2026  
**Repo:** https://github.com/indrukwekkend/cinema-oostereiland  
**Live:** https://cinemaoostereiland.nl  
**Staging (nieuwe omgeving / test):** o.a. `*.kinsta.cloud` (URL kan wisselen)

---

## 1. Wat is dit project?

WordPress-site voor Cinema Oostereiland, gebouwd als **Roots Bedrock** + thema **Sage 9** (`cinema`).

| Onderdeel | Technologie |
|-----------|-------------|
| WordPress stack | Bedrock (Composer) |
| Thema | Sage 9 — map `web/app/themes/cinema` |
| Custom films/blocks | Plugin `films` (= Film-blokken) |
| Sticky CTA op filmpagina | Plugin `cinema-cta-bar-fix` |
| Hosting | Kinsta (mu-plugins aanwezig); deploy vaak via FileZilla/SFTP |
| Tickets | Ticketlab (`TICKETURL`, o.a. `tickets.cinemaoostereiland.nl`) |

Document root op de server is de Bedrock-map **`web/`** (niet de projectroot).

---

## 2. Mappenstructuur (belangrijkste paden)

```
cinema-oostereiland/
├── .env                          # Omgeving (NIET in git, NIET overschrijven op live)
├── composer.json / composer.lock # WP + plugins via Composer
├── config/
│   ├── application.php
│   └── environments/             # development / staging / production
├── web/                          # ← document root
│   ├── app/
│   │   ├── plugins/              # Plugins (deels Composer, deels handmatig)
│   │   ├── themes/cinema/        # Custom thema (Sage 9)
│   │   ├── mu-plugins/
│   │   └── uploads/              # Media (NOOIT lokaal → live overschrijven)
│   ├── wp/                       # WordPress core (via Composer)
│   └── index.php
├── Film-blokken-main/            # Dev-kopie Film-blokken repo (niet deployen als geheel)
├── films/                        # Oudere/losse kopie — gebruik web/app/plugins/films/
└── cinema-cta-bar-fix.zip       # Deploy-zip voor CTA-plugin
```

### Padmapping Bedrock ↔ “klassiek” WordPress

| Bedrock (dit project) | Klassiek WordPress |
|-----------------------|--------------------|
| `web/app/themes/cinema/` | `wp-content/themes/cinema/` |
| `web/app/plugins/` | `wp-content/plugins/` |
| `web/app/uploads/` | `wp-content/uploads/` |
| `web/wp/` | WordPress core |

Op productie van Oostereiland staat de site als Bedrock (`/app/themes/...`, `/app/plugins/...`).

---

## 3. Omgevingen & configuratie

### `.env` (projectroot)

Belangrijke variabelen:

```env
WP_ENV='production'   # of development / staging
WP_HOME='https://cinemaoostereiland.nl'
WP_SITEURL="${WP_HOME}/wp"
TICKETURL='tickets.cinemaoostereiland.nl'
```

Plus databasegegevens en salts.

**Regels:**
- `.env` staat in `.gitignore` — nooit committen.
- Op live: bestaande `.env` **nooit** overschrijven met een lokale `.env`.
- Na URL-wijziging: search-replace in de database (staging-URL → live-URL).

### Admin

- Front: `https://domein/`
- WP Admin: `https://domein/wp/wp-admin/`

---

## 4. Thema `cinema` (Sage 9)

### Locatie
`web/app/themes/cinema/`

### Assets
Bronbestanden:
- Styles: `resources/assets/styles/` (SCSS)
- Scripts: `resources/assets/scripts/` (o.a. `routes/films.js`, `routes/common.js`)

Gecompileerd:
- `dist/styles/`, `dist/scripts/`, `dist/assets.json`

Het thema laadt assets via **`dist/assets.json`** (gehashte bestandsnamen, bijv. `main_2d6f3302.js`).

### Lokaal thema builden

Het build-proces is **oud** (Node 10 / node-sass). Dat kan op moderne Macs lastig zijn.

```bash
cd web/app/themes/cinema
# .nvmrc = Node 10
nvm use 10
npm rebuild node-sass   # indien nodig
yarn build:production
```

Na een geslaagde build: **hele `dist/`** (of minimaal de nieuwe hashes + bijgewerkte `assets.json`) uploaden.

### Als je niet kunt builden

Zie §8 “Deploy zonder build”.

### Bekende theme-eigenaardigheden

| Onderwerp | Locatie / notitie |
|-----------|-------------------|
| Font | **Assistant** (Google Fonts) in `_variables.scss` — niet recent gewijzigd |
| Navigatie submenu | Oude accordion-JS in `resources/assets/scripts/routes/common.js` (sinds ~2021). Zet bij elk klik een lege `<a>` in `.link_text` — buggy zodra er submenu’s zijn |
| CTA bar desktop | In `_header.scss`: `.cta-bar { bottom: 70%; }` op ≥768px. In combinatie met `position: fixed; top: 0` zonder `bottom: auto` wordt de balk enorm hoog |
| GSAP CTA pin | Stond in `films.js`; moet **uit** blijven — sticky wordt door `cinema-cta-bar-fix` gedaan |

---

## 5. Plugin: CPT Films (`films` / Film-blokken)

### Wat doet hij?
- Custom post type **films** (+ gerelateerde blocks)
- Import/koppeling Ticketlab / Filmdepot
- ACF-velden (“Film instellingen”, “Film gegevens”)
- Gutenberg-blokken (agenda, business-card, all-films-grid, …)

### Bron van waarheid
- Live/deploy: **`web/app/plugins/films/`**
- Ontwikkelrepo: https://github.com/indrukwekkend/Film-blokken  
- Map `Film-blokken-main/` in de projectroot = lokale werk-/zipkopie — **niet** zomaar als geheel de live plugin vervangen zonder te vergelijken.

### Belangrijke CSS-fixes (WP 7 editor)

| Bestand | Doel |
|---------|------|
| `src/editor-wp7-layout-fix.css` | Meta-vakken (“Film instellingen”) weer zichtbaar in block editor |
| `src/editor-hide-film-info.css` | Verbergt afbeeldingskolom in de editor |
| `src/frontend-theme-compat.css` | Herstelt CTA-knopstyling op frontend |

**Enqueue:** in `src/init.php` via `enqueue_block_editor_assets`.

#### WP 7 meta-vakken leeg / dichtgeklapt

Symptoom: HTML van Film instellingen staat in de DOM, maar je ziet alleen het tabje “Films”.

Oorzaak: WP 7 resizable meta-panel. Fix staat in `editor-wp7-layout-fix.css` (`min-height: 60vh`, etc.).

**Let op:** `__presenter { display: none }` **niet** terugzetten — op WP **7.0.2** maakt dat het paneel leeg; op 7.0.1 leek het soms nog te werken. Die regel is bewust verwijderd.

---

## 6. Plugin: Cinema CTA Bar Fix

### Locatie
`web/app/plugins/cinema-cta-bar-fix/`  
Zip: `cinema-cta-bar-fix.zip` (projectroot)

### Versie
`2.0.0` (zie `cinema-cta-bar-fix.php`)

### Doel
Sticky titelbalk (`#cta-bar`) op single film-pagina’s, **zonder** dat:
- tekst onder de balk verdwijnt bij omlaag scrollen, of
- er een groot wit blok ontstaat bij omhoog scrollen.

### Bestanden
- `assets/cta-bar-fix.js` — pin/unpin + placeholder; neutraliseert GSAP pin waar mogelijk
- `assets/cta-bar-fix.css` — `position: fixed` + **`bottom: auto !important`** (kritisch)

### Geschiedenis / valkuilen

| Aanpak | Omlaag | Omhoog |
|--------|--------|--------|
| Alleen GSAP (theme) | Problemen | Vaak ok |
| Custom pin + `padding-top` op `.entry-content` | Ok | Wit blok |
| Alleen placeholder, zonder `bottom: auto` | Balk te hoog (theme `bottom: 70%`) | Wit/lelijk |
| **v2: eigen pin + `bottom: auto` + geen content-padding + GSAP uit** | Beoogde fix | Beoogde fix |

**Nooit** opnieuw `padding-top` op `.entry-content` zetten als “snelle fix” zonder scroll-omhoog te testen.

### Deploy
Hele pluginmap of zip uploaden naar `web/app/plugins/cinema-cta-bar-fix/` en activeren in WP-admin. Cache legen + hard refresh (assets gebruiken `filemtime` als versie).

---

## 7. Overige plugins (kort)

| Plugin | Rol |
|--------|-----|
| advanced-custom-fields-pro | Velden (o.a. filmdata) |
| gravityforms | Formulieren |
| seo-by-rank-math | SEO |
| narrowcasting | Narrowcasting |
| subpaginas | Subpagina-blokken |
| duracelltomi-google-tag-manager | GTM |
| Ticketlab (indien geïnstalleerd) | Tickets — zie `ticketlab.zip` / `ticketlab-frontend.zip` in projectroot |

Composer beheert een deel; premium/custom plugins staan soms handmatig in `web/app/plugins/`.

---

## 8. Deploy / live zetten (FileZilla)

### Wat wél uploaden (typische code-update)

| Lokaal | Live |
|--------|------|
| `web/app/themes/cinema/dist/` | `…/app/themes/cinema/dist/` |
| `web/app/plugins/films/` (gewijzigde bestanden) | `…/app/plugins/films/` |
| `web/app/plugins/cinema-cta-bar-fix/` | `…/app/plugins/cinema-cta-bar-fix/` |

### Wat **nooit** overschrijven op live

- `.env`
- `web/app/uploads/` (richting: live → lokaal bij kopiëren, niet andersom tenzij bewust)
- Hele WordPress-core “voor de zekerheid” zonder plan
- `node_modules/`, root-`vendor/` (tenzij je Composer-deploy doet)
- Losse mappen `Film-blokken-main/`, `films/` (projectroot), testbestanden, `.DS_Store`

### Deploy zonder theme-build

Het thema kan vaak niet meer makkelijk gebuild worden (oude Node/node-sass).

**Optie 1 — gehashte assets + `assets.json`:**
1. Upload nieuw bestand, bijv. `dist/scripts/main_XXXXXXXX.js`
2. Pas op de server in `dist/assets.json` **alleen** de JS-regel aan:
   ```json
   "scripts/main.js": "scripts/main_XXXXXXXX.js"
   ```
3. CSS-regels in `assets.json` niet wijzigen tenzij je ook de nieuwe CSS uploadt

**Optie 2 — productie gebruikt `main.js` zonder hash:**  
Overschrijf `dist/scripts/main.js` met de inhoud van je nieuwe build-bestand.

**Check:** op een filmpagina in de browserbron kijken welke URL `main.js` heeft (`main.js` vs `main_hash.js`).

### Simple Custom CSS and JS

Plugin in WP-admin die **extra** CSS/JS toevoegt — dit **overschrijft `main.js` niet**.

Wel bruikbaar voor een tijdelijke hotfix (script in **footer**, bij voorkeur alleen filmpagina’s). Geen vervanging voor een nette plugin/theme-deploy.

### Na elke upload

1. Hard refresh / cache legen (Kinsta, Rank Math, browser)
2. Testen: homepage, filmdetail (scroll omhoog én omlaag), tickets, submenu (indien aanwezig), film bewerken in admin (meta-vakken)

### Database

Alleen bestanden volstaan als content op live al klopt.  
Als pagina’s/ACF/filmdata alleen op staging bestaan: database (of die content) migreren + search-replace van staging-URL → `https://cinemaoostereiland.nl`.

---

## 9. Lokaal ontwikkelen (Local WP)

1. Local site, PHP bij voorkeur **8.1 / 8.2** (niet bleeding edge zonder test)
2. Document root = map **`web`**
3. `.env` met lokale `WP_HOME` (bijv. `http://localhost:PORT`)
4. `composer install` in projectroot (indien nodig)
5. In thema: `composer install` + (indien mogelijk) `yarn` / `nvm use 10` + build
6. Database: dump van staging/live importeren; URLs search-replace naar lokaal
7. Uploads: van live downloaden naar `web/app/uploads/` (niet andersom)

Theme `devUrl` staat in `resources/assets/config.json` (alleen relevant voor BrowserSync/`yarn start`).

---

## 10. Troubleshooting

### Filmpagina: wit blok onder CTA na omhoog scrollen
- Vaak restanten van oude `padding-top` op `.entry-content.cta-bar-scroll-smooth`
- Controleer of `cinema-cta-bar-fix` v2 actief is en of CSS `bottom: auto !important` op `#cta-bar.is-pinned` heeft
- Cache / oude JS in browser

### Filmpagina: tekst verdwijnt onder sticky balk bij omlaag scrollen
- Plugin actief? GSAP pin in theme weer aan? (moet uit)
- Conflicterende Custom CSS and JS?

### Admin: Film instellingen onzichtbaar, HTML wel aanwezig
- `films/src/editor-wp7-layout-fix.css` aanwezig en geladen?
- Geen `display: none` op `.edit-post-meta-boxes-main__presenter`
- Paneel handmatig omhoog slepen ter bevestiging

### Submenu styling / lege `<a>`-tags in menu
- Oude bug in theme `common.js` (`prepend('<a>')` bij `.has-sub`)
- Bestond al lang; triggert pas als er submenu-items zijn
- Niet veroorzaakt door WP-update of CTA-plugin

### Fonts “anders”
- Theme gebruikt nog **Assistant**; recente updates hebben `font-family` niet gewijzigd
- WP 7 kan editor-styles op de frontend laden via block-script dependencies → system fonts op sommige UI-elementen
- Als Google Fonts niet laadt → fallback Helvetica/system

### Assets laden niet na upload
- `assets.json` wijst naar bestand dat niet op de server staat
- Of oude hash wordt nog gecachet

---

## 11. Snelle checklist “ik moet iets live zetten”

1. [ ] Weet ik welk onderdeel? (theme / films / cta-bar-fix)
2. [ ] Upload alleen die mappen/bestanden
3. [ ] Geen `.env`, geen `uploads/` overschrijven
4. [ ] Bij theme-JS: `assets.json` of `main.js` correct laten wijzen
5. [ ] Plugin activeren indien nieuw
6. [ ] Cache legen + hard refresh
7. [ ] Test filmpagina scroll (beide richtingen) + admin film-edit + menu

---

## 12. Contactpunten / repo’s

| Onderdeel | Repo / locatie |
|-----------|----------------|
| Site (Bedrock) | `indrukwekkend/cinema-oostereiland` |
| Film-blokken | `indrukwekkend/Film-blokken` |
| Narrowcasting | `indrukwekkend/narrowcasting` |
| Subpaginas | `indrukwekkend/subpaginas` |

Bij twijfel: eerst op staging testen, daarna dezelfde bestanden naar productie.

---

*Dit document beschrijft de stand van het project zoals onderhouden rond de livegang / WP7- en CTA-fixes (2026). Pas het bij structurele wijzigingen bij.*
