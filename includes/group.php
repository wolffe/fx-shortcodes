<?php
/**
 * Group renderer — generic container. Also serves type="section" (alias)
 * and is the dispatcher's fallback for unknown types.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_render_group( array $a, string $content ): string {
    $tag = fx_safe_tag( $a['tag'], 'div' );

    $layout = strtolower( trim( $a['layout'] ) );

    $classes = fx_class_list(
        [
            'fx-group',
            fx_width_class( $a['width'] ),
            $layout !== '' ? 'fx-group--' . $layout : '',
            $a['align'] !== '' ? 'fx-align-' . $a['align'] : '',
            $a['justify'] !== '' ? 'fx-justify-' . $a['justify'] : '',
            $a['vertical-align'] !== '' ? 'fx-valign-' . $a['vertical-align'] : '',
            $a['class'],
        ]
    );

    $style_rules = fx_common_styles( $a );
    if ( $a['background'] !== '' ) {
        $style_rules['background-image']    = 'url(' . esc_url( $a['background'] ) . ')';
        $style_rules['background-size']     = 'cover';
        $style_rules['background-position'] = $a['focal'] !== '' ? $a['focal'] : 'center center';
    }
    $style = fx_style( $style_rules );

    return sprintf(
        '<%1$s class="%2$s"%3$s%4$s>%5$s</%1$s>',
        $tag,
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $style !== '' ? ' style="' . $style . '"' : '',
        $content
    );
}
