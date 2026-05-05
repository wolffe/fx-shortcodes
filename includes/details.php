<?php
/**
 * Details / Accordion renderer — native <details> element.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_render_details( array $a, string $content ): string {
    $classes = fx_class_list(
        [
            'fx-details',
            fx_width_class( $a['width'] ),
            $a['class'],
        ]
    );

    $style = fx_style( fx_common_styles( $a ) );
    $open  = $a['open'] === '1' ? ' open' : '';

    return sprintf(
        '<details class="%1$s"%2$s%3$s%4$s><summary class="fx-details__summary">%5$s</summary><div class="fx-details__content">%6$s</div></details>',
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $style !== '' ? ' style="' . $style . '"' : '',
        $open,
        esc_html( $a['summary'] ),
        $content
    );
}
