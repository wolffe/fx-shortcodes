<?php
/**
 * Separator / HR renderer — visible thematic break with style variants.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_render_separator( array $a ): string {
    $style_keyword = strtolower( trim( $a['style'] ) );
    $variants      = [ 'default', 'wide', 'dots', 'dashed', 'double' ];
    $variant       = in_array( $style_keyword, $variants, true ) ? $style_keyword : 'default';

    $classes = fx_class_list(
        [
            'fx-separator',
            'fx-separator--' . $variant,
            fx_width_class( $a['width'] ),
            $a['align'] !== '' ? 'fx-separator--align-' . $a['align'] : '',
            $a['class'],
        ]
    );

    $style_rules = [
        'color'      => $a['color'] !== '' ? $a['color'] : $a['text'],
        'margin-top' => $a['margin'],
        'max-width'  => $a['max-width'],
    ];

    // Numeric / CSS-length width (not full/wide keyword) sets max-width on the rule itself.
    if ( $a['width'] !== '' && fx_width_class( $a['width'] ) === '' ) {
        $style_rules['width']     = $a['width'];
        $style_rules['max-width'] = $a['width'];
    }

    $style = fx_style( $style_rules );

    return sprintf(
        '<hr class="%1$s"%2$s%3$s />',
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $style !== '' ? ' style="' . $style . '"' : ''
    );
}
