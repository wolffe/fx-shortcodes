<?php
/**
 * Spacer renderer — invisible vertical or horizontal gap.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_render_spacer( array $a ): string {
    $horizontal = strtolower( trim( $a['direction'] ) ) === 'horizontal';

    $classes = fx_class_list(
        [
            'fx-spacer',
            $horizontal ? 'fx-spacer--horizontal' : 'fx-spacer--vertical',
            $a['class'],
        ]
    );

    $style_rules = $horizontal
        ? [
            'display'   => 'inline-block',
            'width'     => $a['width'] !== '' ? $a['width'] : '1rem',
            'min-width' => $a['width'] !== '' ? $a['width'] : '1rem',
            'height'    => $a['height'] !== '' ? $a['height'] : '1px',
        ]
        : [
            'display'    => 'block',
            'height'     => $a['height'] !== '' ? $a['height'] : '2rem',
            'min-height' => $a['height'] !== '' ? $a['height'] : '2rem',
            'width'      => $a['width'] !== '' ? $a['width'] : '100%',
        ];

    $style = fx_style( $style_rules );

    return sprintf(
        '<span class="%1$s"%2$s%3$s aria-hidden="true"></span>',
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $style !== '' ? ' style="' . $style . '"' : ''
    );
}
