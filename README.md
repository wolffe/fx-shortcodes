# FX Shortcodes

Block-style page layouts for ClassicPress, delivered as shortcodes.

## Why this exists

ClassicPress dropped the Gutenberg block editor in favour of the Classic editor. That's a deliberate choice — but it leaves a gap: the layout primitives that came with blocks (cover, columns, media + text, buttons, accordions, etc.) disappear with them. Themes still know how to style `alignfull` / `alignwide`, but there's no easy way to author those layouts in a Classic post.

FX Shortcodes fills that gap. Every layout block you'd reach for in Gutenberg is available as a shortcode, with the same visual result and the same alignment classes — but authored as plain `[element]` tags inside the Classic editor.

No JavaScript, no block editor, no React. Just one shortcode and a stylesheet.

## How it works

Everything goes through a single shortcode: **`[element]`**. The `type` attribute picks the layout:

```
[element type="cover" background="/hero.jpg" overlay="#000" overlay-opacity="0.4"]
  <h1>Welcome</h1>
[/element]
```

Self-closing void blocks use a trailing slash:

```
[element type="spacer" height="3rem" /]
```

Because the plugin parses `[element]` itself (innermost-first, before WordPress' shortcode regex runs), **same-name nesting works** — you can drop an `[element type="group"]` inside another `[element type="group"]` without WordPress closing on the first `[/element]`.

## Available types

| Type                             | Role                                                                    |
| -------------------------------- | ----------------------------------------------------------------------- |
| `cover`                          | Full-bleed hero with background image, overlay, parallax                |
| `group` (alias `section`)        | Generic container with optional flex / grid layout                      |
| `columns` + `column`             | Equal or asymmetric column rows                                         |
| `details` (alias `accordion`)    | Native `<details>` element                                              |
| `media-text` (alias `mediatext`) | Image on one side, text on the other; supports "crop image to fill"     |
| `button`                         | `<a>` or `<button>` with fill / outline / text variants and OKLCH hover |
| `card`                           | Image + title + body, optionally a whole-card link                      |
| `spacer`                         | Invisible vertical or horizontal gap (void)                             |
| `separator` (alias `hr`)         | Thematic break with style variants (void)                               |
| `line` (alias `colored-line`)    | Decorative striped / dotted / shaded line (void)                        |

All types accept a shared set of common attributes — `width` (`full` / `wide` / CSS length), `align`, `vertical-align`, `background-color`, `padding`, `margin`, `gap`, `border-radius`, `shadow`, etc. — plus a few type-specific ones.

## Install

1. Drop the `fx-shortcodes` folder into `wp-content/plugins/`.
2. Activate it from the Plugins screen.
3. Open **Settings → FX Shortcodes** for the full reference with code samples for every shortcode.

## Requirements

- ClassicPress 2.0+
- PHP 8.5+

## License

GPL-2.0-or-later.
