<?php
/**
 * Admin docs page (Settings -> FX Shortcodes).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fx_shortcodes_render_docs_page(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    ?>
    <div class="wrap fx-doc">
        <h1>FX Shortcodes</h1>

        <style>
            .fx-doc { max-width: 980px; }
            .fx-doc h2 { margin-top: 2.4em; padding-top: 0.4em; border-top: 1px solid #dcdcde; }
            .fx-doc h3 { margin-top: 1.6em; }
            .fx-doc p, .fx-doc li { font-size: 14px; line-height: 1.6; }
            .fx-doc table { border-collapse: collapse; margin: 1em 0; width: 100%; background: #fff; }
            .fx-doc th, .fx-doc td { border: 1px solid #dcdcde; padding: 8px 12px; text-align: left; vertical-align: top; font-size: 13px; }
            .fx-doc th { background: #f6f7f7; }
            .fx-doc code { background: #f0f0f1; padding: 1px 5px; border-radius: 3px; font-size: 13px; }
            .fx-doc textarea.fx-doc-code { display: block; background: #1d2327; color: #e8eaed; padding: 14px 16px; border-radius: 4px; font-size: 13px; line-height: 1.55; font-family: monospace; width: 100%; box-sizing: border-box; border: none; resize: none; scrollbar-width: thin; color-scheme: dark; }
            .fx-doc blockquote { border-left: 4px solid #c3c4c7; margin: 1em 0; padding: 0.4em 1em; background: #f6f7f7; color: #50575e; }
            .fx-doc .fx-toc { background: #f6f7f7; border: 1px solid #dcdcde; padding: 12px 18px; border-radius: 4px; }
            .fx-doc .fx-toc ul { margin: 0; columns: 2; }
        </style>

        <p>Everything goes through one tag: <code>[element]</code>. Set the layout with <code>type="…"</code> (for example <code>type="cover"</code>). Paired blocks close with <code>[/element]</code>. Self-closing void blocks use a trailing slash: <code>[element type="spacer" height="3rem" /]</code>.</p>

        <p>Because the plugin parses <code>[element]</code> itself (innermost-first), <strong>same-name nesting works</strong>: you can put <code>[element type="group"]</code> … <code>[/element]</code> inside another group without WordPress closing on the first <code>[/element]</code>.</p>

        <div class="fx-toc">
            <strong>On this page</strong>
            <ul>
                <li><a href="#fx-types">Types</a></li>
                <li><a href="#fx-common">Common attributes</a></li>
                <li><a href="#fx-cover">Cover</a></li>
                <li><a href="#fx-group">Group</a></li>
                <li><a href="#fx-columns">Columns / Column</a></li>
                <li><a href="#fx-details">Details / Accordion</a></li>
                <li><a href="#fx-media-text">Media &amp; Text</a></li>
                <li><a href="#fx-button">Button</a></li>
                <li><a href="#fx-card">Card</a></li>
                <li><a href="#fx-spacer">Spacer</a></li>
                <li><a href="#fx-separator">Separator / HR</a></li>
                <li><a href="#fx-line">Line / Coloured line</a></li>
                <li><a href="#fx-nesting">Nesting</a></li>
                <li><a href="#fx-paired-void">Paired vs void</a></li>
            </ul>
        </div>

        <h2 id="fx-types">Types (<code>type</code> attribute)</h2>
        <table>
            <thead><tr><th><code>type</code></th><th>Role</th></tr></thead>
            <tbody>
                <tr><td><code>cover</code></td><td>Hero / cover</td></tr>
                <tr><td><code>group</code></td><td>Generic container</td></tr>
                <tr><td><code>section</code></td><td>Same renderer as group (semantic alias)</td></tr>
                <tr><td><code>columns</code></td><td>Column row</td></tr>
                <tr><td><code>column</code></td><td>Single column</td></tr>
                <tr><td><code>details</code></td><td><code>&lt;details&gt;</code></td></tr>
                <tr><td><code>accordion</code></td><td>Alias of details</td></tr>
                <tr><td><code>media-text</code></td><td>Media + text</td></tr>
                <tr><td><code>mediatext</code></td><td>Alias of media-text</td></tr>
                <tr><td><code>button</code></td><td>Button / link</td></tr>
                <tr><td><code>card</code></td><td>Card</td></tr>
                <tr><td><code>spacer</code></td><td>Spacer (void)</td></tr>
                <tr><td><code>separator</code></td><td>Rule (void)</td></tr>
                <tr><td><code>hr</code></td><td>Alias of separator</td></tr>
                <tr><td><code>line</code></td><td>Decorative line (void)</td></tr>
                <tr><td><code>colored-line</code></td><td>Alias of line</td></tr>
            </tbody>
        </table>

        <h2 id="fx-common">Common attributes</h2>
        <p>These work on (almost) every block type:</p>
        <table>
            <thead><tr><th>Attribute</th><th>Accepts</th><th>Notes</th></tr></thead>
            <tbody>
                <tr><td><code>class</code></td><td>space-separated CSS classes</td><td>merged onto the wrapper</td></tr>
                <tr><td><code>id</code></td><td>string</td><td>sets <code>id="..."</code> on the wrapper</td></tr>
                <tr><td><code>tag</code></td><td><code>div</code> <code>section</code> <code>article</code> <code>aside</code> <code>header</code> <code>footer</code> <code>main</code> <code>nav</code> <code>figure</code></td><td>wrapper element (where applicable)</td></tr>
                <tr><td><code>width</code></td><td><code>full</code> <code>wide</code> or any CSS length</td><td><code>full</code> → <code>alignfull</code>, <code>wide</code> → <code>alignwide</code></td></tr>
                <tr><td><code>align</code></td><td><code>left</code> <code>center</code> <code>right</code></td><td>text alignment</td></tr>
                <tr><td><code>vertical-align</code></td><td><code>top</code> <code>center</code> <code>bottom</code></td><td>flex/grid item alignment</td></tr>
                <tr><td><code>background</code></td><td>image URL</td><td>sets <code>background-image</code></td></tr>
                <tr><td><code>background-color</code></td><td>CSS color</td><td></td></tr>
                <tr><td><code>text</code> / <code>color</code></td><td>CSS color</td><td>text color (<code>text=</code> is the WP-cover-style alias)</td></tr>
                <tr><td><code>padding</code></td><td>CSS length / shorthand</td><td>e.g. <code>2rem</code> or <code>1rem 2rem</code></td></tr>
                <tr><td><code>margin</code></td><td>CSS length / shorthand</td><td></td></tr>
                <tr><td><code>gap</code></td><td>CSS length</td><td>flex/grid gap</td></tr>
                <tr><td><code>border-radius</code> / <code>radius</code></td><td>CSS length</td><td></td></tr>
                <tr><td><code>shadow</code></td><td>CSS box-shadow value</td><td>e.g. <code>0 4px 20px rgba(0,0,0,.1)</code></td></tr>
                <tr><td><code>min-height</code></td><td>CSS length</td><td></td></tr>
                <tr><td><code>max-width</code></td><td>CSS length</td><td></td></tr>
            </tbody>
        </table>

        <h2 id="fx-cover">Cover</h2>
        <p>Full-bleed hero with optional background image, color overlay, and parallax.</p>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="cover" height="100vh" width="full" background="/wp-content/uploads/hero.jpg" overlay="#000000" overlay-opacity="0.4" text="#ffffff" align="center" vertical-align="center"]
<h1>Welcome</h1>
<p>A short tagline goes here.</p>
[element type="button" url="/get-started" style="fill" size="large"]Get started[/element]
[/element]</textarea>
        <p>Cover-specific attributes:</p>
        <table>
            <thead><tr><th>Attribute</th><th>Notes</th></tr></thead>
            <tbody>
                <tr><td><code>background</code></td><td>background image URL</td></tr>
                <tr><td><code>overlay</code></td><td>overlay color (rendered above image)</td></tr>
                <tr><td><code>overlay-opacity</code></td><td><code>0</code>–<code>1</code>, default <code>0.5</code></td></tr>
                <tr><td><code>parallax</code></td><td><code>1</code> / <code>0</code> — fixes background-attachment</td></tr>
                <tr><td><code>focal</code></td><td>CSS background-position, e.g. <code>30% 70%</code></td></tr>
                <tr><td><code>height</code> / <code>min-height</code></td><td>becomes <code>min-height</code> of the cover</td></tr>
            </tbody>
        </table>

        <h2 id="fx-group">Group</h2>
        <p>Generic container. Use it for sections of content with a shared background, padding, or layout.</p>

        <h3>Plain</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="group" background-color="#f6f6f6" padding="3rem 1rem" width="full"]
<h2>About us</h2>
<p>Long-form text here.</p>
[/element]</textarea>

        <h3>Flex layout</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="group" layout="flex" justify="space-between" vertical-align="center" gap="2rem" padding="2rem"]
[element type="card" title="One"]Lorem ipsum.[/element]
[element type="card" title="Two"]Dolor sit amet.[/element]
[element type="card" title="Three"]Consectetur.[/element]
[/element]</textarea>

        <h3>Grid layout (auto-fit)</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="group" layout="grid" gap="1.5rem" padding="2rem"]
[element type="card" title="A"]…[/element]
[element type="card" title="B"]…[/element]
[element type="card" title="C"]…[/element]
[element type="card" title="D"]…[/element]
[/element]</textarea>

        <p>Group-specific attributes:</p>
        <table>
            <thead><tr><th>Attribute</th><th>Notes</th></tr></thead>
            <tbody>
                <tr><td><code>layout</code></td><td><code>default</code> (block), <code>flex</code>, <code>grid</code></td></tr>
                <tr><td><code>justify</code></td><td><code>start</code> <code>center</code> <code>end</code> <code>space-between</code> <code>space-around</code></td></tr>
            </tbody>
        </table>

        <h2 id="fx-columns">Columns / Column</h2>
        <p>Equal columns by default; per-column <code>span</code> overrides flex-basis.</p>

        <h3>Two equal columns</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="columns" columns="2" gap="2rem"]
    [element type="column"]
    <h3>Left</h3>
    <p>Some text.</p>
    [/element]
    [element type="column"]
    <h3>Right</h3>
    <p>Other text.</p>
    [/element]
[/element]</textarea>

        <h3>Three columns, no stack on mobile</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="columns" columns="3" gap="1rem" stack="0"]
    [element type="column"]One[/element]
    [element type="column"]Two[/element]
    [element type="column"]Three[/element]
[/element]</textarea>

        <h3>Asymmetric columns (1/3 + 2/3)</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="columns" columns="2" gap="2rem"]
    [element type="column" span="33%"]
    <p>Sidebar.</p>
    [/element]
    [element type="column" span="67%"]
    <p>Main content area.</p>
    [/element]
[/element]</textarea>

        <p>Columns-specific attributes:</p>
        <table>
            <thead><tr><th>Attribute</th><th>Notes</th></tr></thead>
            <tbody>
                <tr><td><code>columns</code></td><td>integer; default <code>2</code></td></tr>
                <tr><td><code>stack</code></td><td><code>1</code> / <code>0</code> — collapse to single column under 781px (default <code>1</code>)</td></tr>
                <tr><td><code>span</code> (on <code>column</code>)</td><td>flex-basis, e.g. <code>33%</code>, <code>400px</code></td></tr>
            </tbody>
        </table>

        <h2 id="fx-details">Details / Accordion</h2>
        <p>Native <code>&lt;details&gt;</code> element. <code>[element type="accordion"]</code> matches <code>[element type="details"]</code> (same renderer).</p>

        <h3>Closed by default</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="details" summary="What's your refund policy?"]
<p>30 days, no questions asked.</p>
[/element]</textarea>

        <h3>Open by default</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="accordion" summary="Important notice" open="1"]
<p>This panel is expanded on page load.</p>
[/element]</textarea>

        <h3>Stack of accordions</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="group" padding="1rem"]
[element type="details" summary="Question 1"]Answer 1.[/element]
[element type="details" summary="Question 2"]Answer 2.[/element]
[element type="details" summary="Question 3"]Answer 3.[/element]
[/element]</textarea>

        <p>Use a default group (not <code>layout="flex"</code>) for a vertical list — flex would put them in a row.</p>

        <p>Details-specific attributes:</p>
        <table>
            <thead><tr><th>Attribute</th><th>Notes</th></tr></thead>
            <tbody>
                <tr><td><code>summary</code></td><td>the always-visible header text</td></tr>
                <tr><td><code>open</code></td><td><code>1</code> / <code>0</code> — initial state (default <code>0</code>)</td></tr>
            </tbody>
        </table>

        <h2 id="fx-media-text">Media &amp; Text</h2>
        <p>Image on one side, text on the other. <code>[element type="mediatext"]</code> matches <code>[element type="media-text"]</code> (same renderer).</p>

        <h3>Media on the left (default)</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="media-text" media="/wp-content/uploads/photo.jpg" media-alt="A photo" media-width="40%" gap="2rem"]
<h2>Heading</h2>
<p>Body text alongside the image.</p>
[element type="button" url="/learn-more"]Learn more[/element]
[/element]</textarea>

        <h3>Media on the right, stacks on mobile</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="media-text" media="/wp-content/uploads/photo.jpg" media-position="right" media-width="50%" stack="1" vertical-align="center"]
<h2>Right-side image</h2>
<p>Stacks vertically below 781px.</p>
[/element]</textarea>

        <h3>Crop image to fill</h3>
        <p>The image stretches to match the height of the text column (like WordPress' <em>Crop image to fill</em> toggle on the Media &amp; Text block). The image is sized with <code>object-fit: cover</code>, so any aspect-ratio mismatch is cropped, not letter-boxed.</p>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="media-text" media="/wp-content/uploads/photo.jpg" media-width="40%" crop="1"]
<h2>Cropped image</h2>
<p>The photo on the left now matches the height of this text block, regardless of the image's natural aspect ratio.</p>
[/element]</textarea>

        <p>Media-text-specific attributes:</p>
        <table>
            <thead><tr><th>Attribute</th><th>Notes</th></tr></thead>
            <tbody>
                <tr><td><code>media</code></td><td>image URL</td></tr>
                <tr><td><code>media-alt</code></td><td>alt text</td></tr>
                <tr><td><code>media-position</code></td><td><code>left</code> (default) or <code>right</code></td></tr>
                <tr><td><code>media-width</code></td><td>CSS length, default <code>50%</code></td></tr>
                <tr><td><code>stack</code></td><td><code>1</code> / <code>0</code> — stack at narrow widths (default <code>1</code>)</td></tr>
                <tr><td><code>crop</code></td><td><code>1</code> / <code>0</code> — crop image to fill text column height (<code>object-fit: cover</code>); default <code>0</code></td></tr>
            </tbody>
        </table>

        <h2 id="fx-button">Button</h2>
        <p>Renders an <code>&lt;a&gt;</code> when <code>url</code> is set, otherwise a <code>&lt;button type="button"&gt;</code>.</p>
        <p>Every button has a <strong>background color</strong> and a <strong>text color</strong>. On hover both states darken automatically using <code>color-mix(in oklch, ...)</code> — perceptually-uniform darkening that works on any color you pass in, no need to pre-compute a hover variant.</p>

        <h3>Solid (default)</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="button" url="/signup"]Sign up[/element]</textarea>
        <p>The default palette is <code>#2271b1</code> background with white text. Hover darkens the background by 15% in OKLCH space.</p>

        <h3>Outline, large</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="button" url="/contact" style="outline" size="large"]Contact us[/element]</textarea>
        <p>Outline buttons borrow the <code>background-color</code> attribute as their accent. On hover they fill in with the darkened accent and switch to the text color.</p>

        <h3>External link, opens in new tab (auto adds <code>rel="noopener noreferrer"</code>)</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="button" url="https://example.com" target="_blank"]Visit site[/element]</textarea>

        <h3>Custom colors — hover darkens automatically</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="button" url="/buy" background-color="#222" color="#ffd400" border-radius="999px" padding="0.8rem 2rem"]Buy now[/element]</textarea>
        <p>You don't specify a hover color. The CSS computes it from the <code>background-color</code> value you pass: <code>color-mix(in oklch, #222, black 15%)</code>.</p>

        <h3>Brand-accent button using a CSS color name</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="button" url="/launch" background-color="tomato" color="white"]Launch[/element]</textarea>

        <h3>Button row inside a flex group</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="group" layout="flex" justify="center" gap="1rem"]
[element type="button" url="/primary" style="fill"]Primary[/element]
[element type="button" url="/secondary" style="outline"]Secondary[/element]
[element type="button" url="/tertiary" style="text"]Tertiary[/element]
[/element]</textarea>
        <p>All three variants share the same color logic — fill, outline, and text all darken on hover via <code>color-mix</code>.</p>

        <h3>Tweaking the hover strength</h3>
        <p>The hover darkening percentage is exposed as a CSS variable on <code>.fx-button</code> (<code>--fx-button-darken</code>, default <code>15%</code>). To override globally, in your theme CSS:</p>
        <textarea class="fx-doc-code" name="fx-code" rows="6">.fx-button { --fx-button-darken: 25%; }   /* stronger hover darkening */</textarea>
        <p>Or per-button by adding a class via the <code>class</code> attribute and targeting it.</p>

        <p>Button-specific attributes:</p>
        <table>
            <thead><tr><th>Attribute</th><th>Notes</th></tr></thead>
            <tbody>
                <tr><td><code>url</code></td><td>href; if omitted, renders a <code>&lt;button&gt;</code> instead of <code>&lt;a&gt;</code></td></tr>
                <tr><td><code>target</code></td><td><code>_blank</code>, <code>_self</code>, etc. — <code>_blank</code> auto-adds safe <code>rel</code></td></tr>
                <tr><td><code>rel</code></td><td>overrides the auto-rel</td></tr>
                <tr><td><code>style</code></td><td><code>fill</code> (default), <code>outline</code>, <code>text</code></td></tr>
                <tr><td><code>size</code></td><td><code>small</code>, default, <code>large</code></td></tr>
                <tr><td><code>background-color</code></td><td>sets <code>--fx-button-bg</code>; hover is derived from this</td></tr>
                <tr><td><code>color</code> (or <code>text</code>)</td><td>sets <code>--fx-button-color</code></td></tr>
            </tbody>
        </table>
        <blockquote><strong>Browser support:</strong> <code>color-mix()</code> requires Chrome 111+, Safari 16.4+, Firefox 113+ (all shipped 2023). On older browsers the hover rule is ignored and the button stays in its base state — still functional, just not animated.</blockquote>

        <h2 id="fx-card">Card</h2>
        <p>Optional image, title, body content, and an optional whole-card link.</p>

        <h3>Simple</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="card" image="/wp-content/uploads/photo.jpg" title="My Card"]
<p>Card body text.</p>
[/element]</textarea>

        <h3>Whole card linked</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="card" image="/wp-content/uploads/photo.jpg" title="Read the article" url="/article-slug"]
<p>Click anywhere on the card to read.</p>
[/element]</textarea>

        <h3>Card grid</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="columns" columns="3" gap="1.5rem"]
    [element type="column"]
    [element type="card" image="/img/a.jpg" title="Alpha" url="/a"]Short summary.[/element]
    [/element]
    [element type="column"]
    [element type="card" image="/img/b.jpg" title="Beta" url="/b"]Short summary.[/element]
    [/element]
    [element type="column"]
    [element type="card" image="/img/c.jpg" title="Gamma" url="/c"]Short summary.[/element]
    [/element]
[/element]</textarea>

        <p>Card-specific attributes:</p>
        <table>
            <thead><tr><th>Attribute</th><th>Notes</th></tr></thead>
            <tbody>
                <tr><td><code>image</code></td><td>image URL</td></tr>
                <tr><td><code>image-alt</code></td><td>alt text</td></tr>
                <tr><td><code>title</code></td><td>rendered as <code>&lt;h3&gt;</code></td></tr>
                <tr><td><code>url</code></td><td>optional — wraps the card in a link</td></tr>
                <tr><td><code>target</code>, <code>rel</code></td><td>applied to the wrapping link</td></tr>
            </tbody>
        </table>

        <h2 id="fx-spacer">Spacer</h2>
        <p>Invisible vertical (or horizontal) gap. Self-closing form is recommended.</p>

        <h3>Vertical (default)</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="spacer" height="3rem" /]</textarea>

        <h3>Horizontal (inline)</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">Some text. [element type="spacer" direction="horizontal" width="2rem" /] More text.</textarea>

        <h3>Inside a flex layout</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="group" layout="flex" vertical-align="center"]
<span>Left</span>
[element type="spacer" direction="horizontal" width="auto" /]
<span>Right</span>
[/element]</textarea>

        <p>Spacer-specific attributes:</p>
        <table>
            <thead><tr><th>Attribute</th><th>Notes</th></tr></thead>
            <tbody>
                <tr><td><code>direction</code></td><td><code>vertical</code> (default) or <code>horizontal</code></td></tr>
                <tr><td><code>height</code></td><td>vertical mode size, default <code>2rem</code></td></tr>
                <tr><td><code>width</code></td><td>horizontal mode size, default <code>1rem</code></td></tr>
            </tbody>
        </table>

        <h2 id="fx-separator">Separator / HR</h2>
        <p>Visible thematic break (<code>&lt;hr&gt;</code>) with style variants. <code>[element type="hr" /]</code> matches <code>[element type="separator" /]</code> (same renderer).</p>

        <h3>Default short rule</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="separator" /]</textarea>

        <h3>Wide (full-container) rule</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="separator" style="wide" /]</textarea>

        <h3>Dotted</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="hr" style="dots" /]</textarea>

        <h3>Dashed</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="separator" style="dashed" /]</textarea>

        <h3>Double line</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="separator" style="double" /]</textarea>

        <h3>Colored, custom width, left-aligned</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="separator" color="#c0392b" width="120px" align="left" /]</textarea>

        <h3>Inside a card</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="card" title="Section"]
<p>Intro paragraph.</p>
[element type="separator" style="dots" /]
<p>Continued content.</p>
[/element]</textarea>

        <p>Separator-specific attributes:</p>
        <table>
            <thead><tr><th>Attribute</th><th>Notes</th></tr></thead>
            <tbody>
                <tr><td><code>style</code></td><td><code>default</code> (short), <code>wide</code>, <code>dots</code>, <code>dashed</code>, <code>double</code></td></tr>
                <tr><td><code>color</code> (or <code>text</code>)</td><td>line color</td></tr>
                <tr><td><code>width</code></td><td><code>full</code>, <code>wide</code>, or any CSS length (length sets the line width directly)</td></tr>
                <tr><td><code>align</code></td><td><code>left</code> <code>center</code> (default) <code>right</code></td></tr>
            </tbody>
        </table>

        <h2 id="fx-line">Line / Coloured line</h2>
        <p>A more controllable decorative line than <code>[element type="separator" /]</code>. Renders three nested divs so you can independently control outer padding, horizontal alignment of the bar, and the bar's own dimensions. Painted via three style variants — <code>striped</code> (default), <code>dotted</code>, <code>shade</code> — using <code>currentColor</code> against the value passed to <code>color</code>.</p>
        <p><code>[element type="colored-line" /]</code> matches <code>[element type="line" /]</code> (same renderer).</p>

        <h3>Striped (default), 256×10px, centred</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="line" style="striped" height="10px" width="256px" padding="32px 0" color="#95afc0" align="center" /]</textarea>

        <h3>Dotted, full width</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="line" style="dotted" height="6px" width="100%" color="#222" /]</textarea>

        <h3>Shaded fade-in/fade-out</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="line" style="shade" height="2px" width="60%" color="#0a0a0a" /]</textarea>

        <h3>Solid bar (override <code>background-color</code>)</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="line" style="striped" background-color="#0a0a0a" color="#0a0a0a" height="4px" width="120px" /]</textarea>
        <p>When you pass a non-transparent <code>background-color</code> you get a solid filled bar in addition to whatever the variant paints over it.</p>

        <h3>Left-aligned with extra vertical padding</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="line" style="dotted" align="left" padding="48px 0" height="8px" width="200px" color="#c0392b" /]</textarea>

        <h3>Inside a card or section divider</h3>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="card" title="Specifications"]
<p>Top section.</p>
[element type="line" style="shade" height="1px" color="#999" /]
<p>Bottom section.</p>
[/element]</textarea>

        <p>Line-specific attributes:</p>
        <table>
            <thead><tr><th>Attribute</th><th>Notes</th></tr></thead>
            <tbody>
                <tr><td><code>style</code></td><td><code>striped</code> (default), <code>dotted</code>, <code>shade</code></td></tr>
                <tr><td><code>height</code></td><td>bar height; default <code>1px</code></td></tr>
                <tr><td><code>width</code></td><td>bar width; default <code>100%</code></td></tr>
                <tr><td><code>padding</code></td><td>outer wrapper padding; default <code>32px 0</code></td></tr>
                <tr><td><code>background-color</code></td><td>bar background; default <code>transparent</code> (variant paints over it)</td></tr>
                <tr><td><code>color</code> (or <code>text</code>)</td><td>drives <code>currentColor</code> for the <code>striped</code> and <code>dotted</code> variants; default <code>#000000</code>. The <code>shade</code> variant uses fixed translucent black tones and ignores this.</td></tr>
                <tr><td><code>align</code></td><td><code>left</code> <code>center</code> (default) <code>right</code> — sets the bar's horizontal position via <code>justify-content</code></td></tr>
            </tbody>
        </table>
        <h2 id="fx-nesting">Nesting</h2>
        <p>Different <code>type</code> values nest arbitrarily (<code>[element type="group"]</code> containing <code>[element type="columns"]</code> …). Because parsing is innermost-first, <strong><code>[element]</code> inside <code>[element]</code></strong> is supported (for example a column inside columns inside a cover).</p>
        <textarea class="fx-doc-code" name="fx-code" rows="6">[element type="cover" height="80vh" width="full" background="/img/hero.jpg" overlay="#000" overlay-opacity="0.5" text="#fff" align="center" vertical-align="center"]
    [element type="group" max-width="900px"]
    <h1>Big idea</h1>
    <p>Subtitle.</p>
    [element type="columns" columns="2" gap="1.5rem"]
        [element type="column"]
        [element type="button" url="/start" style="fill" size="large"]Start[/element]
        [/element]
        [element type="column"]
        [element type="button" url="/learn" style="outline" size="large"]Learn more[/element]
        [/element]
    [/element]
    [element type="spacer" height="2rem" /]
    [element type="separator" style="dots" /]
    [/element]
[/element]</textarea>

        <h2 id="fx-paired-void">Paired vs void tags</h2>
        <table>
            <thead><tr><th>Form</th><th>Use for</th></tr></thead>
            <tbody>
                <tr><td><code>[element type="cover"]…[/element]</code> (paired blocks — swap <code>type</code> per layout)</td><td>cover, group, section, columns, column, details, accordion, media-text, mediatext, button, card</td></tr>
                <tr><td><code>[element type="spacer" height="3rem" /]</code> / <code>[element type="separator" /]</code> / <code>[element type="line" /]</code> …</td><td>void blocks — single bracket pair, no closing tag</td></tr>
            </tbody>
        </table>
        <p>Self-closing void tags (<code>… /]</code>) are recommended; an empty paired <code>[/element]</code> after a void type is unnecessary.</p>
    </div>
    <?php
}
