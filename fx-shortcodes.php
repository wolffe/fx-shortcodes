<?php
/**
 * Plugin Name: FX Shortcodes
 * Plugin URI: https://getbutterfly.com/classicpress-plugins/fx-shortcodes/
 * Description: Block-style layout shortcodes (cover, group, columns, details, media-text, button, card, sticky, and more) for ClassicPress. One shortcode, many types, fully nestable.
 * Version: 1.0.1
 * Requires PHP: 8.0
 * Requires CP: 2.5
 * Author: Ciprian Popescu
 * Author URI: https://getbutterfly.com/
 * License: GNU General Public License v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: fx-shortcodes
 * Domain Path: /languages/
 *
 * @author Ciprian Popescu <ciprian@getbutterfly.com>
 * @copyright Copyright (c) 2026, getButterfly
 *
 * FX Shortcodes
 * Copyright (C) 2026 Ciprian Popescu (getbutterfly@gmail.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * @package FX_Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

const FX_SHORTCODES_VERSION = '1.0.1';

/* -------------------------------------------------------------------------
 * Includes
 * ------------------------------------------------------------------------- */

$fx_includes_dir = plugin_dir_path( __FILE__ ) . 'includes/';

require_once $fx_includes_dir . 'cover.php';
require_once $fx_includes_dir . 'group.php';
require_once $fx_includes_dir . 'columns.php';
require_once $fx_includes_dir . 'details.php';
require_once $fx_includes_dir . 'media-text.php';
require_once $fx_includes_dir . 'button.php';
require_once $fx_includes_dir . 'card.php';
require_once $fx_includes_dir . 'spacer.php';
require_once $fx_includes_dir . 'separator.php';
require_once $fx_includes_dir . 'line.php';
require_once $fx_includes_dir . 'sticky.php';
require_once $fx_includes_dir . 'map-embed.php';
require_once $fx_includes_dir . 'settings.php';

unset( $fx_includes_dir );

/* -------------------------------------------------------------------------
 * Bootstrap
 * ------------------------------------------------------------------------- */

add_action( 'wp_enqueue_scripts', 'fx_shortcodes_enqueue' );
function fx_shortcodes_enqueue(): void {
    wp_enqueue_style(
        'fx-shortcodes',
        plugin_dir_url( __FILE__ ) . 'fx-shortcodes.css',
        [],
        FX_SHORTCODES_VERSION
    );
}

/*
 * We do NOT register [element] via add_shortcode().
 *
 * WordPress' shortcode regex cannot handle same-name nested shortcodes:
 * the outer [element] would close on the first [/element] it finds, which
 * breaks nesting. Instead we hook the_content at priority 8 (before
 * wpautop at 10 and do_shortcode at 11) and parse [element]...[/element]
 * blocks ourselves, innermost-out.
 *
 * Any non-[element] shortcodes inside the content are left untouched and
 * picked up by do_shortcode at priority 11.
 */
add_filter( 'the_content', 'fx_shortcodes_parse', 8 );
add_filter( 'widget_text', 'fx_shortcodes_parse', 8 );
add_filter( 'comment_text', 'fx_shortcodes_parse', 8 );

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'fx_shortcodes_action_links' );
function fx_shortcodes_action_links( array $links ): array {
    $docs_url = admin_url( 'options-general.php?page=fx-shortcodes' );
    $links[]  = sprintf(
        '<a href="%s">%s</a>',
        esc_url( $docs_url ),
        esc_html__( 'Help', 'fx-shortcodes' )
    );
    return $links;
}

add_action( 'admin_enqueue_scripts', 'fx_shortcodes_admin_enqueue' );
function fx_shortcodes_admin_enqueue( string $hook_suffix ): void {
    if ( $hook_suffix !== 'settings_page_fx-shortcodes' ) {
        return;
    }
    wp_enqueue_style(
        'fx-shortcodes-admin',
        plugins_url( 'fx-shortcodes-admin.css', __FILE__ ),
        [],
        FX_SHORTCODES_VERSION
    );
}

add_action( 'admin_menu', 'fx_shortcodes_admin_menu' );
function fx_shortcodes_admin_menu(): void {
    add_options_page(
        'FX Shortcodes',
        'FX Shortcodes',
        'manage_options',
        'fx-shortcodes',
        'fx_shortcodes_render_docs_page'
    );
}

/* -------------------------------------------------------------------------
 * Parser
 * ------------------------------------------------------------------------- */

/**
 * @param mixed $content Filter value (WordPress may pass null in edge cases).
 */
function fx_shortcodes_parse( $content ): string {
    $content = (string) $content;
    if ( ! str_contains( $content, '[element' ) ) {
        return $content;
    }

    // Self-closing form: [element type="spacer" height="2rem" /]
    $after_self = preg_replace_callback(
        '/\[element\b([^\]]*?)\/\]/s',
        static function ( array $m ): string {
            $atts = shortcode_parse_atts( $m[1] );
            if ( ! is_array( $atts ) ) {
                $atts = [];
            }
            return fx_render_element( $atts, '' );
        },
        $content
    );
    $content    = is_string( $after_self ) ? $after_self : $content;

    // Paired form: [element ...]...[/element] -- innermost-first.
    $pattern = '/\[element\b([^\]]*)\]((?:(?!\[element\b|\[\/element\]).)*?)\[\/element\]/s';

    $guard = 0;
    while ( $guard++ < 50 && preg_match( $pattern, $content ) ) {
        $next = preg_replace_callback(
            $pattern,
            static function ( array $m ): string {
                $atts = shortcode_parse_atts( $m[1] );
                if ( ! is_array( $atts ) ) {
                    $atts = [];
                }
                return fx_render_element( $atts, trim( $m[2] ) );
            },
            $content
        );
        if ( ! is_string( $next ) ) {
            break;
        }
        $content = $next;
    }

    return $content;
}

/* -------------------------------------------------------------------------
 * Dispatcher
 * ------------------------------------------------------------------------- */

function fx_render_element( array $atts, string $content ): string {
    $defaults = [
        'type'             => 'group',

        // common
        'class'            => '',
        'id'               => '',
        'tag'              => '',
        'width'            => '',     // full | wide | <css value>
        'align'            => '',     // left | center | right
        'vertical-align'   => '',     // top | center | bottom
        'background'       => '',     // image URL
        'background-color' => '',
        'text'             => '',     // alias for color
        'color'            => '',
        'padding'          => '',
        'margin'           => '',
        'gap'              => '',
        'radius'           => '',     // alias for border-radius
        'border-radius'    => '',
        'shadow'           => '',
        'height'           => '',     // for cover -> min-height
        'min-height'       => '',
        'max-width'        => '',

        // cover
        'overlay'          => '',
        'overlay-opacity'  => '0.5',
        'parallax'         => '',
        'focal'            => '',     // "50% 50%"

    // columns
        'columns'          => '2',
        'stack'            => '1',    // 1 = stack on mobile, 0 = keep side-by-side

    // column
        'span'             => '',     // flex-basis e.g. "33%"

    // details / accordion
        'summary'          => '',
        'open'             => '0',    // 1 = expanded on load, 0 = collapsed

    // media-text
        'media'            => '',
        'media-alt'        => '',
        'media-position'   => 'left', // left | right
        'media-width'      => '', // empty or 50% → equal columns (1fr / 1fr) in renderer
        'crop'             => '0',    // 1 = crop image to fill text column height

    // button
        'url'              => '',
        'target'           => '',
        'rel'              => '',
        'size'             => '',     // small | default | large
        'style'            => 'fill', // fill | outline | text

    // card
        'image'            => '',
        'image-alt'        => '',
        'title'            => '',

        // group / layout
        'layout'           => '',     // default | flex | grid
        'justify'          => '',

        // spacer
        'direction'        => 'vertical', // vertical | horizontal

        // sticky
        'rotate'           => '',     // e.g. "-2deg"
        'aspect-ratio'     => '',     // e.g. "1/1"
    ];

    $a    = array_merge( $defaults, array_change_key_case( $atts, CASE_LOWER ) );
    $type = strtolower( trim( (string) $a['type'] ) );

    return match ( $type ) {
        'cover'                   => fx_render_cover( $a, $content ),
        'group', 'section'        => fx_render_group( $a, $content ),
        'columns'                 => fx_render_columns( $a, $content ),
        'column'                  => fx_render_column( $a, $content ),
        'details', 'accordion'    => fx_render_details( $a, $content ),
        'media-text', 'mediatext' => fx_render_media_text( $a, $content ),
        'button'                  => fx_render_button( $a, $content ),
        'card'                    => fx_render_card( $a, $content ),
        'spacer'                  => fx_render_spacer( $a ),
        'separator', 'hr'         => fx_render_separator( $a ),
        'line', 'colored-line'    => fx_render_line( $a ),
        'sticky', 'note'          => fx_render_sticky( $a, $content ),
        default                   => fx_render_group( $a, $content ),
    };
}

/* -------------------------------------------------------------------------
 * Helpers (shared by all renderers)
 * ------------------------------------------------------------------------- */

function fx_class_list( array $classes ): string {
    $classes = array_filter( array_map( 'trim', $classes ) );
    $classes = array_map( 'sanitize_html_class', $classes );
    return implode( ' ', array_unique( $classes ) );
}

function fx_style( array $rules ): string {
    $out = [];
    foreach ( $rules as $prop => $val ) {
        $val = trim( (string) $val );
        if ( $val === '' ) {
            continue;
        }
        // Strip characters that could break out of a style attribute / value.
        $val   = str_replace( [ '<', '>', '"', "\n", "\r" ], '', $val );
        $out[] = $prop . ':' . $val;
    }
    return $out === [] ? '' : esc_attr( implode( ';', $out ) );
}

function fx_attr( string $name, string $value ): string {
    $value = trim( $value );
    return $value === '' ? '' : sprintf( ' %s="%s"', $name, esc_attr( $value ) );
}

/**
 * Width keyword -> WordPress alignment class.
 *
 * "full" -> alignfull, "wide" -> alignwide, anything else (or empty) -> ''.
 * Themes with theme support 'align-wide' will respect these.
 */
function fx_width_class( string $width ): string {
    return match ( strtolower( trim( $width ) ) ) {
        'full' => 'alignfull',
        'wide' => 'alignwide',
        default => '',
    };
}

/**
 * Build the common subset of style rules shared by most block types.
 */
function fx_common_styles( array $a ): array {
    return [
        'background-color' => $a['background-color'],
        'color'            => $a['color'] !== '' ? $a['color'] : $a['text'],
        'padding'          => $a['padding'],
        'margin'           => $a['margin'],
        'gap'              => $a['gap'],
        'border-radius'    => $a['border-radius'] !== '' ? $a['border-radius'] : $a['radius'],
        'box-shadow'       => $a['shadow'],
        'min-height'       => $a['min-height'] !== '' ? $a['min-height'] : ( $a['height'] !== '' ? $a['height'] : '' ),
        'max-width'        => $a['max-width'],
    ];
}

/**
 * Choose a safe HTML tag from user input, falling back to a default.
 */
function fx_safe_tag( string $requested, string $fallback ): string {
    $requested = strtolower( trim( $requested ) );
    $allowed   = [ 'div', 'section', 'article', 'aside', 'header', 'footer', 'main', 'nav', 'figure' ];
    return in_array( $requested, $allowed, true ) ? $requested : $fallback;
}
