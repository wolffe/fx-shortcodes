<?php
/**
 * Media & Text renderer — image on one side, text on the other.
 * `crop="1"` enables WordPress' "Crop image to fill" behaviour.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_render_media_text( array $a, string $content ): string {
    $position = strtolower( trim( $a['media-position'] ) ) === 'right' ? 'right' : 'left';

    $classes = fx_class_list(
        [
            'fx-media-text',
            'fx-media-text--' . $position,
            fx_width_class( $a['width'] ),
            $a['vertical-align'] !== '' ? 'fx-valign-' . $a['vertical-align'] : '',
            $a['stack'] === '1' ? 'fx-media-text--stack' : '',
            $a['crop'] === '1' ? 'fx-media-text--crop' : '',
            $a['class'],
        ]
    );

    $style = fx_style(
        array_merge(
            fx_common_styles( $a ),
            [ '--fx-media-width' => $a['media-width'] ]
        )
    );

    $media_html = '';
    if ( $a['media'] !== '' ) {
        $media_html = sprintf(
            '<div class="fx-media-text__media"><img src="%s" alt="%s" loading="lazy" /></div>',
            esc_url( $a['media'] ),
            esc_attr( $a['media-alt'] )
        );
    }

    $text_html = '<div class="fx-media-text__text">' . $content . '</div>';

    return sprintf(
        '<div class="%1$s"%2$s%3$s>%4$s</div>',
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $style !== '' ? ' style="' . $style . '"' : '',
        $position === 'right' ? $text_html . $media_html : $media_html . $text_html
    );
}
