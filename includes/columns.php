<?php
/**
 * Columns + Column renderers — equal columns by default, with per-column
 * `span` overrides flex-basis for asymmetric layouts.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_render_columns( array $a, string $content ): string {
    $count = max( 1, (int) $a['columns'] );

    $classes = fx_class_list(
        [
            'fx-columns',
            'fx-columns--' . $count,
            fx_width_class( $a['width'] ),
            $a['stack'] === '1' ? 'fx-columns--stack' : '',
            $a['vertical-align'] !== '' ? 'fx-valign-' . $a['vertical-align'] : '',
            $a['class'],
        ]
    );

    $style = fx_style(
        array_merge(
            fx_common_styles( $a ),
            [ '--fx-columns' => (string) $count ]
        )
    );

    return sprintf(
        '<div class="%1$s"%2$s%3$s>%4$s</div>',
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $style !== '' ? ' style="' . $style . '"' : '',
        $content
    );
}

function fx_render_column( array $a, string $content ): string {
    $classes = fx_class_list(
        [
            'fx-column',
            $a['vertical-align'] !== '' ? 'fx-valign-' . $a['vertical-align'] : '',
            $a['class'],
        ]
    );

    $style_rules = fx_common_styles( $a );
    if ( $a['span'] !== '' ) {
        $style_rules['flex-basis'] = $a['span'];
        $style_rules['flex-grow']  = '0';
    }
    $style = fx_style( $style_rules );

    return sprintf(
        '<div class="%1$s"%2$s%3$s>%4$s</div>',
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $style !== '' ? ' style="' . $style . '"' : '',
        $content
    );
}
