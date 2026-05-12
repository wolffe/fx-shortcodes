=== FX Shortcodes ===
Contributors: butterflymedia
Tags: classicpress, shortcodes, blocks, layout, gutenberg
Requires at least: 2.5
Requires PHP: 8.5
Tested up to: 2.7.0
Stable tag: 1.0.0
License: GNU General Public License v3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Block-style page layouts for ClassicPress, delivered as shortcodes. Replaces Gutenberg layout blocks with one nestable `[element]` tag.

== Description ==

ClassicPress dropped the Gutenberg block editor in favour of the Classic editor — but that leaves a gap: the layout primitives that came with blocks (cover, columns, media + text, buttons, accordions, decorative lines, and so on) disappear with it. Themes still know how to style `alignfull` / `alignwide`, but there's no easy way to author those layouts in a Classic post.

FX Shortcodes fills that gap. Every layout block you'd reach for in Gutenberg is available as a shortcode, with the same visual result and the same alignment classes — but authored as plain `[element]` tags inside the Classic editor.

No JavaScript, no block editor, no React. Just one shortcode and a stylesheet.

= How it works =

Everything goes through a single shortcode: `[element]`. The `type` attribute picks the layout. Self-closing void blocks use a trailing slash (`[element type="spacer" height="3rem" /]`).

Because the plugin parses `[element]` itself (innermost-first, before WordPress' shortcode regex runs), same-name nesting works — you can drop an `[element type="group"]` inside another `[element type="group"]` without WordPress closing on the first `[/element]`.

= Available types =

* `cover` — full-bleed hero with background image, overlay, parallax
* `group` (alias `section`) — generic container with optional flex / grid layout
* `columns` + `column` — equal or asymmetric column rows
* `details` (alias `accordion`) — native `<details>` element
* `media-text` (alias `mediatext`) — image on one side, text on the other; supports "crop image to fill"
* `button` — `<a>` or `<button>` with fill / outline / text variants and OKLCH hover
* `card` — image + title + body, optionally a whole-card link
* `spacer` — invisible vertical or horizontal gap (void)
* `separator` (alias `hr`) — thematic break with style variants (void)
* `line` (alias `colored-line`) — decorative striped / dotted / shaded line (void)

All types share a common attribute set: `width` (`full` / `wide` / CSS length), `align`, `vertical-align`, `background-color`, `padding`, `margin`, `gap`, `border-radius`, `shadow`, and more — plus a few type-specific ones.

The full reference, with code samples for every shortcode, lives in the admin under **Settings → FX Shortcodes**.

== Installation ==

1. Upload the `fx-shortcodes` folder to `/wp-content/plugins/`.
2. Activate the plugin from the Plugins screen.
3. Open **Settings → FX Shortcodes** for the full reference and code samples.

== Frequently Asked Questions ==

= Does this require Gutenberg? =

No. The plugin runs in the Classic editor and outputs plain HTML on the front end. No block editor, no JavaScript.

= Will the shortcodes nest? =

Yes. The plugin uses its own innermost-first parser, so an `[element]` inside an `[element]` is supported — including same-type nesting (group inside group, columns inside columns).

= What about WordPress? =

It works on WordPress too — the codebase has no ClassicPress-only dependencies — but the target audience is ClassicPress users who use the Classic editor and want layout primitives without blocks.

== Changelog ==

= 1.0.0 =
* Initial release.
