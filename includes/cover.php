<?php
/**
 * Cover renderer — full-bleed hero with optional background image
 * and color overlay.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_render_cover( array $a, string $content ): string {
    $tag = fx_safe_tag( $a['tag'], 'section' );

    $classes = fx_class_list(
        [
            'fx-cover',
            fx_width_class( $a['width'] ),
            $a['align'] !== '' ? 'fx-align-' . $a['align'] : '',
            $a['vertical-align'] !== '' ? 'fx-valign-' . $a['vertical-align'] : '',
            $a['class'],
        ]
    );

    $style = fx_style(
        array_merge(
            fx_common_styles( $a ),
            [
                // height on cover means "minimum content height"
                'min-height' => $a['min-height'] !== '' ? $a['min-height'] : ( $a['height'] !== '' ? $a['height'] : '' ),
                'color'      => $a['text'] !== '' ? $a['text'] : $a['color'],
            ]
        )
    );

    $bg = '';
    if ( $a['background'] !== '' ) {
        $img_style = $a['focal'] !== '' ? fx_style( [ 'object-position' => $a['focal'] ] ) : '';
        $bg        = sprintf(
            '<div class="fx-cover__bg" aria-hidden="true"><img src="%1$s" alt="" fetchpriority="high" loading="eager" decoding="async"%2$s></div>',
            esc_url( $a['background'] ),
            $img_style !== '' ? ' style="' . $img_style . '"' : ''
        );
    }

    $overlay = '';
    if ( $a['overlay'] !== '' ) {
        $overlay = sprintf(
            '<div class="fx-cover__overlay" style="%s" aria-hidden="true"></div>',
            fx_style(
                [
                    'background-color' => $a['overlay'],
                    'opacity'          => $a['overlay-opacity'],
                ]
            )
        );
    }

    return sprintf(
        '<%1$s class="%2$s"%3$s%4$s>%5$s%6$s<div class="fx-cover__inner">%7$s</div></%1$s>',
        $tag,
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $style !== '' ? ' style="' . $style . '"' : '',
        $bg,
        $overlay,
        $content
    );
}
