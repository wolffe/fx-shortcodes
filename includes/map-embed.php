<?php
/**
 * Google Maps embed — responsive iframe from Google's official embed URL only.
 *
 * Usage:
 *
 *     [fx_google_map url="https://www.google.com/maps/embed?pb=..." height="450"]
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'fx_google_map', 'fx_google_map_shortcode' );

/**
 * Whether the attribute value is allowed as iframe src for Google Maps embed.
 *
 * @param string $url Raw URL string.
 */
function fx_google_map_is_allowed_embed_url( string $url ): bool {
    if ( '' === $url ) {
        return false;
    }

    $scheme = strtolower( (string) wp_parse_url( $url, PHP_URL_SCHEME ) );
    if ( 'http' !== $scheme && 'https' !== $scheme ) {
        return false;
    }

    $host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
    if ( '' === $host ) {
        return false;
    }

    /*
     * google.com / google.co.jp / maps.google.example, etc.
     */
    $no_www_raw = preg_replace( '#^www\.#', '', $host );
    $no_www     = is_string( $no_www_raw ) ? $no_www_raw : $host;

    $google_prefix_len = strlen( 'google.' );
    $looks_like_google = strlen( $no_www ) >= $google_prefix_len
        && 0 === strncmp( $no_www, 'google.', $google_prefix_len );

    $looks_like_google = $looks_like_google
        || ( false !== strpos( $host, '.google.' ) );

    if ( ! $looks_like_google ) {
        return false;
    }

    $path = strtolower( (string) wp_parse_url( $url, PHP_URL_PATH ) );

    return false !== strpos( $path, '/maps/embed' );
}

/**
 * @param array<string,string|string[]>|string $atts Shortcode attributes.
 */
function fx_google_map_shortcode( $atts, $content = null, $tag = '' ): string {
    /*
     * Do not declare string type hints here: cores may pass empty string /
     * arbitrary values positionally depending on WP/CP versions.
     */
    $atts_key = is_string( $tag ) && preg_match( '/^[a-z0-9_-]+$/i', $tag ) === 1
        ? $tag
        : 'fx_google_map';

    $atts = shortcode_atts(
        [
            'url'    => '',
            'height' => '450',
        ],
        is_array( $atts ) ? $atts : [],
        $atts_key
    );

    $url = trim( $atts['url'] );
    if ( '' === $url || ! fx_google_map_is_allowed_embed_url( $url ) ) {
        return '';
    }

    $safe_url = esc_url( $url );
    if ( '' === $safe_url ) {
        return '';
    }

    $height = absint( $atts['height'] );
    if ( 0 === $height ) {
        $height = 450;
    }

    return sprintf(
        '<div class="fx-google-map"><iframe class="fx-google-map__frame" src="%1$s" width="100%%" height="%2$d" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="%3$s" allowfullscreen=""></iframe></div>',
        $safe_url,
        $height,
        esc_attr__( 'Embedded map', 'fx-shortcodes' )
    );
}
