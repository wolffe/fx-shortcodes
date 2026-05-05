<?php
/**
 * Button renderer — <a> when `url` is set, otherwise <button type="button">.
 *
 * Color is driven by CSS custom properties so the stylesheet can derive the
 * hover color via color-mix() in oklch from whatever value the user passes.
 * Direct background-color / color rules would short-circuit the hover.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_render_button( array $a, string $content ): string {
    $style_keyword = strtolower( trim( $a['style'] ) );
    $size_keyword  = strtolower( trim( $a['size'] ) );

    $classes = fx_class_list(
        [
            'fx-button',
            'fx-button--' . ( in_array( $style_keyword, [ 'fill', 'outline', 'text' ], true ) ? $style_keyword : 'fill' ),
            $size_keyword !== '' ? 'fx-button--' . $size_keyword : '',
            $a['class'],
        ]
    );

    $style = fx_style(
        [
            '--fx-button-bg'    => $a['background-color'],
            '--fx-button-color' => $a['color'] !== '' ? $a['color'] : $a['text'],
            'border-radius'     => $a['border-radius'] !== '' ? $a['border-radius'] : $a['radius'],
            'padding'           => $a['padding'],
        ]
    );

    $tag    = $a['url'] !== '' ? 'a' : 'button';
    $target = '';
    $rel    = $a['rel'];

    if ( $a['target'] !== '' ) {
        $target = ' target="' . esc_attr( $a['target'] ) . '"';
        if ( strtolower( trim( $a['target'] ) ) === '_blank' && $rel === '' ) {
            $rel = 'noopener noreferrer';
        }
    }

    $href_or_type = $tag === 'a'
        ? ' href="' . esc_url( $a['url'] ) . '"'
        : ' type="button"';

    return sprintf(
        '<%1$s class="%2$s"%3$s%4$s%5$s%6$s%7$s>%8$s</%1$s>',
        $tag,
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $style !== '' ? ' style="' . $style . '"' : '',
        $href_or_type,
        $target,
        fx_attr( 'rel', $rel ),
        $content
    );
}
