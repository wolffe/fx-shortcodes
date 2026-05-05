<?php
/**
 * Card renderer — optional image, title, body, and an optional whole-card
 * link via `url`.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_render_card( array $a, string $content ): string {
    $tag = fx_safe_tag( $a['tag'], 'article' );

    $classes = fx_class_list(
        [
            'fx-card',
            fx_width_class( $a['width'] ),
            $a['class'],
        ]
    );

    $style = fx_style( fx_common_styles( $a ) );

    $image_html = '';
    if ( $a['image'] !== '' ) {
        $image_html = sprintf(
            '<div class="fx-card__media"><img src="%s" alt="%s" loading="lazy" /></div>',
            esc_url( $a['image'] ),
            esc_attr( $a['image-alt'] )
        );
    }

    $title_html = '';
    if ( $a['title'] !== '' ) {
        $title_html = '<h3 class="fx-card__title">' . esc_html( $a['title'] ) . '</h3>';
    }

    // Whole card linked when url is set
    $open  = '';
    $close = '';
    if ( $a['url'] !== '' ) {
        $open  = '<a class="fx-card__link" href="' . esc_url( $a['url'] ) . '"' .
            ( $a['target'] !== '' ? ' target="' . esc_attr( $a['target'] ) . '"' : '' ) .
            fx_attr( 'rel', $a['rel'] ) . '>';
        $close = '</a>';
    }

    return sprintf(
        '<%1$s class="%2$s"%3$s%4$s>%5$s%6$s%7$s<div class="fx-card__body">%8$s</div>%9$s</%1$s>',
        $tag,
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $style !== '' ? ' style="' . $style . '"' : '',
        $open,
        $image_html,
        $title_html,
        $content,
        $close
    );
}
