<?php
/**
 * Sticky note renderer — colored "post-it" card with optional rotation,
 * aspect-ratio, and shadow.
 *
 * Usage:
 *     [element type="sticky" background-color="#fff3cd" rotate="-2deg" aspect-ratio="1/1" shadow="1"]
 *         Content
 *     [/element]
 *
 * Back-compat: a standalone [sticky] shortcode is also registered below for
 * content imported from FX Builder. It translates legacy attribute names
 * (bg-color, text-color, aspect_ratio, ...) and forwards to fx_render_sticky().
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_render_sticky( array $a, string $content ): string {
    $tag = fx_safe_tag( $a['tag'], 'div' );

    // The shadow attr is overloaded for sticky:
    //   - "1" / "true" / "yes" / "on" means "apply the default sticky shadow"
    //     (handled by the .fx-sticky--shadow modifier class)
    //   - any other non-empty value is treated as a raw CSS box-shadow value
    $shadow_raw      = strtolower( trim( (string) $a['shadow'] ) );
    $has_flag_shadow = in_array( $shadow_raw, [ '1', 'true', 'yes', 'on' ], true );
    $shadow_value    = $has_flag_shadow ? '' : $a['shadow'];

    $classes = fx_class_list(
        [
            'fx-sticky',
            fx_width_class( $a['width'] ),
            $a['align'] !== '' ? 'fx-align-' . $a['align'] : '',
            $has_flag_shadow ? 'fx-sticky--shadow' : '',
            $a['class'],
        ]
    );

    $style = fx_style(
        array_merge(
            fx_common_styles( $a ),
            [
                'box-shadow'   => $shadow_value,
                'transform'    => $a['rotate'] !== '' ? 'rotate(' . $a['rotate'] . ')' : '',
                'aspect-ratio' => $a['aspect-ratio'],
                'color'        => $a['color'] !== '' ? $a['color'] : $a['text'],
            ]
        )
    );

    return sprintf(
        '<%1$s class="%2$s"%3$s%4$s><div class="fx-sticky__inner">%5$s</div></%1$s>',
        $tag,
        esc_attr( $classes ),
        fx_attr( 'id', $a['id'] ),
        $style !== '' ? ' style="' . $style . '"' : '',
        $content
    );
}

/* -------------------------------------------------------------------------
 * Back-compat: legacy [sticky] shortcode (FX Builder)
 * ------------------------------------------------------------------------- */

add_shortcode( 'sticky', 'fx_sticky_compat_shortcode' );

/**
 * @param array<string,string>|string $atts
 */
function fx_sticky_compat_shortcode( $atts, ?string $content = null ): string {
    $atts = is_array( $atts ) ? array_change_key_case( $atts, CASE_LOWER ) : [];

    // Legacy attribute aliases -> new attribute names.
    $alias_map = [
        'bg-color'      => 'background-color',
        'bg_color'      => 'background-color',
        'text-color'    => 'color',
        'text_color'    => 'color',
        'border_radius' => 'border-radius',
        'aspect_ratio'  => 'aspect-ratio',
    ];
    foreach ( $alias_map as $from => $to ) {
        if ( isset( $atts[ $from ] ) && ! isset( $atts[ $to ] ) ) {
            $atts[ $to ] = $atts[ $from ];
        }
        unset( $atts[ $from ] );
    }

    // Legacy [sticky] defaults: a post-it card look.
    $atts = array_merge(
        [
            'background-color' => '#fff3cd',
            'color'            => '#212529',
            'padding'          => '1.5em',
            'rotate'           => '-2deg',
            'border-radius'    => '6px',
            'shadow'           => '1',
            'aspect-ratio'     => '1/1',
        ],
        $atts
    );

    // Dispatch through fx_render_element so the full defaults array is built
    // for us (and any future shared behavior gets picked up automatically).
    $atts['type'] = 'sticky';

    return fx_render_element( $atts, do_shortcode( shortcode_unautop( (string) $content ) ) );
}
