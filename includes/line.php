<?php
/**
 * Decorative coloured line.
 *
 * Three nested divs so that the painted line can be sized, padded, and
 * horizontally aligned independently. Style variants (`fx-line--striped`,
 * `fx-line--dotted`, `fx-line--shade`) are rendered by the stylesheet
 * using `currentColor` against the inner element.
 *
 * Aliases: type="line", type="colored-line".
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_render_line( array $a ): string {
    $variants = [ 'striped', 'dotted', 'shade' ];

    $style_kw = strtolower( trim( $a['style'] ) );
    $variant  = in_array( $style_kw, $variants, true ) ? $style_kw : 'striped';

    $classes = fx_class_list(
        [
            'fx-line',
            'fx-line--' . $variant,
            $a['class'],
        ]
    );

    // align maps to a CSS justify-content value; left/right become flex-*
    $align_input = strtolower( trim( $a['align'] ) );
    $align       = $align_input !== '' ? $align_input : 'center';
    $justify     = match ( $align ) {
        'left'  => 'flex-start',
        'right' => 'flex-end',
        default => $align,
    };

    $layout_style = fx_style(
        [
            'padding'         => $a['padding'] !== '' ? $a['padding'] : '32px 0',
            'justify-content' => $justify,
        ]
    );

    $inner_style = fx_style(
        [
            // transparent by default; the variants paint via background-image
            'background-color' => $a['background-color'] !== '' ? $a['background-color'] : 'transparent',
            // drives currentColor for striped/dotted/shade
            'color'            => $a['color'] !== '' ? $a['color'] : ( $a['text'] !== '' ? $a['text'] : '#000000' ),
            'width'            => $a['width'] !== '' ? $a['width'] : '100%',
            'height'           => $a['height'] !== '' ? $a['height'] : '1px',
        ]
    );

    return sprintf(
        '<div class="%1$s"%2$s><div class="fx-line__layout"%3$s><div class="fx-line__inner"%4$s></div></div></div>',
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $layout_style !== '' ? ' style="' . $layout_style . '"' : '',
        $inner_style !== '' ? ' style="' . $inner_style . '"' : ''
    );
}
