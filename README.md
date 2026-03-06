# QweerT Punk Zine — WordPress/WooCommerce Theme

**Version:** 1.0.0  
**Requires WordPress:** 6.0+  
**Requires PHP:** 8.0+  
**WooCommerce:** Fully compatible (5.0+)

---

## Design Philosophy

**Queer Punk Zine / Riot Grrrl Manifesto** — A dark, raw, DIY aesthetic that matches the hand-drawn, thick-outline art style of QweerT.ART.

- **Background:** Near-black `#0d0d0d` with subtle noise grain
- **Accent colors:** Neon hot pink `#ff2d78` + electric blue `#55cfff` (trans flag energy) + acid yellow `#f5e642`
- **Typography:** Bebas Neue (display) + Space Mono (body) + Permanent Marker (handwritten accents)
- **Aesthetic:** Thick black outlines, sticker-pop hover effects, halftone textures, activist poster layout

---

## Installation

1. Download the `qweert-punk-zine.zip` file
2. In your WordPress admin, go to **Appearance → Themes → Add New → Upload Theme**
3. Upload the zip file and click **Activate**
4. Make sure **WooCommerce** is installed and activated

---

## Required Plugins

- **WooCommerce** (for the shop, cart, and checkout)

---

## Customization

All key theme options are available in the **WordPress Customizer** (`Appearance → Customize → QweerT Theme Options`):

| Option | Description |
|---|---|
| Hero Background Image | Upload your own punk art hero background |
| Hero Headline Lines 1, 2, 3 | Edit the three lines of the hero heading |
| Hero Description | The paragraph text below the hero headline |
| Hero Handwritten Tagline | The yellow handwritten text at the bottom of the hero |
| Marquee Slogans | Pipe-separated `\|` activist slogans for the scrolling ticker |
| About Section Background | Background image for the About/Our Story section |
| Contact Email | Shown in the header utility bar |
| Instagram URL | Link to your Instagram profile |
| Facebook URL | Link to your Facebook page |
| Footer Handwritten Tagline | Yellow handwritten text in the footer |
| Homepage Shop Section Title | The heading above the featured products grid |

---

## Navigation Menus

Go to **Appearance → Menus** to set up:

- **Primary Menu** — Main navigation in the header
- **Footer Menu** — Quick links in the footer
- **Social Links Menu** — Optional social links

---

## Theme File Structure

```
qweert-punk-zine/
├── style.css               ← Theme metadata + ALL CSS styles
├── functions.php           ← Theme setup, WooCommerce support, Customizer
├── header.php              ← Site header with nav
├── footer.php              ← Site footer
├── front-page.php          ← Homepage (Hero, Marquee, Shop, About, Events)
├── index.php               ← Blog index / fallback
├── page.php                ← Static pages
├── single.php              ← Single blog posts
├── woocommerce/
│   ├── archive-product.php ← Shop page override
│   └── single-product.php  ← Product page override
├── assets/
│   ├── css/                ← (optional extra CSS files)
│   └── js/
│       └── main.js         ← Mobile menu, animations, cart updates
└── inc/                    ← (optional extra PHP includes)
```

---

## Adding Events

The Events section on the homepage currently shows static placeholder events. To manage events dynamically:

1. Install a plugin like **The Events Calendar** (free) or **Custom Post Type UI**
2. Create a custom post type called `event` with meta fields: `event_date`, `event_location`, `event_status`
3. The theme will automatically display them

---

## WooCommerce Notes

- The theme declares full WooCommerce support including gallery zoom, lightbox, and slider
- Product cards use a sticker-style design with thick black borders and pop-hover effects
- The shop page displays 12 products per page in a 3-column grid (desktop)
- All WooCommerce notices, cart, checkout, and account pages are styled to match the punk theme
- Cart count in the header updates automatically via AJAX fragments

---

## Credits

- **Fonts:** [Bebas Neue](https://fonts.google.com/specimen/Bebas+Neue), [Space Mono](https://fonts.google.com/specimen/Space+Mono), [Permanent Marker](https://fonts.google.com/specimen/Permanent+Marker) via Google Fonts
- **Art & Brand:** QweerT.ART / Piper — https://qweert.art
- **Theme Design:** Manus AI
