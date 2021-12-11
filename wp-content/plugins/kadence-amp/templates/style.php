<?php
// Get the options
$kadence_amp = kadence_amp_options();
// Get content width
$content_max_width       = absint( $this->get( 'content_max_width' ) );

// Get template colors
$body_background_color            	= $kadence_amp[ 'body_bg' ];
$footer_background_color			= $kadence_amp[ 'footer_bg' ];

$highlight_color              = $kadence_amp[ 'highlight_color' ];
$header_background_color 	= $kadence_amp[ 'head_bg' ];
$logo_color            		= $kadence_amp[ 'logo_text_font' ][ 'color' ];
$logo_weight            	= $kadence_amp[ 'logo_text_font' ][ 'font-weight' ];
$logo_lspace            	= $kadence_amp[ 'logo_text_font' ][ 'letter-spacing' ];
$logo_font            		= $kadence_amp[ 'logo_text_font' ][ 'font-family' ];

$menu_icon_color            	= $kadence_amp[ 'menu_text_font' ][ 'color' ];
$menu_icon_weight            	= $kadence_amp[ 'menu_text_font' ][ 'font-weight' ];
$menu_icon_lspace            	= $kadence_amp[ 'menu_text_font' ][ 'letter-spacing' ];
$menu_icon_font            		= $kadence_amp[ 'menu_text_font' ][ 'font-family' ];

$menu_icon_icon_color            	= $kadence_amp[ 'menu_icon_color' ];

$menu_background_color      = $kadence_amp[ 'menu_bg_color' ];

$menu_color            		= $kadence_amp[ 'menu_font' ][ 'color' ];
$menu_weight            	= $kadence_amp[ 'menu_font' ][ 'font-weight' ];
$menu_lspace            	= $kadence_amp[ 'menu_font' ][ 'letter-spacing' ];
$menu_font            		= $kadence_amp[ 'menu_font' ][ 'font-family' ];

$body_color            		= $kadence_amp[ 'body_font' ][ 'color' ];
$body_weight            	= $kadence_amp[ 'body_font' ][ 'font-weight' ];
$body_lspace            	= $kadence_amp[ 'body_font' ][ 'letter-spacing' ];
$body_font            		= $kadence_amp[ 'body_font' ][ 'font-family' ];

$title_color            	= $kadence_amp[ 'h_font' ][ 'color' ];
$title_weight            	= $kadence_amp[ 'h_font' ][ 'font-weight' ];
$title_lspace            	= $kadence_amp[ 'h_font' ][ 'letter-spacing' ];
$title_font            		= $kadence_amp[ 'h_font' ][ 'font-family' ];

$footer_color            	= $kadence_amp[ 'footer_font' ][ 'color' ];
$footer_weight            	= $kadence_amp[ 'footer_font' ][ 'font-weight' ];
$footer_size         		= $kadence_amp[ 'footer_font' ][ 'font-size' ];
$footer_line_height         = $kadence_amp[ 'footer_font' ][ 'line-height' ];

$muted_text_color           = $kadence_amp[ 'muted_text_color' ];

$border_color = '#eee';

?>
/* Generic WP styling */

.alignright {
	float: right;
}

.alignleft {
	float: left;
}

.aligncenter {
	display: block;
	margin-left: auto;
	margin-right: auto;
}
input, textarea {
	padding: 5px;
	box-sizing: border-box;
}
.amp-wp-enforced-sizes {
	max-width: 100%;
	margin: 0 auto;
}

.amp-wp-unknown-size img {
	object-fit: contain;
}
.clearfix:after {
	content: '';
	display: table;
	clear: both;
}
table th, table td, table th {
    padding: 5px;
}
table.shop_attributes td {
    font-style: italic;
}
table tr:nth-child(even) {
    background: rgba(0,0,0,.03);
}
/* Flex */

.full-flex {
	flex: 1 0 100%;
}
.flex-space-between {
	justify-content: space-between;
}
.flex-align-center {
	align-items: center;
}
.flex-wrap {
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
}
.flex {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
}
.items-center {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}

/* Template Styles */
* {
    -webkit-tap-highlight-color: rgba(255,255,255,0);
}
html body {
	margin:0;
}

h1, h2, h3 {
    margin: 10px 0;
    font-family:  <?php echo wp_kses_post( $title_font ); ?>;
	color: <?php echo sanitize_hex_color( $title_color ); ?>;
	font-weight: <?php echo esc_attr( $title_weight ); ?>;
	letter-spacing: <?php echo esc_attr( $title_lspace ); ?>;
}
body {
	background: <?php echo sanitize_hex_color( $body_background_color ); ?>;
	color: <?php echo sanitize_hex_color( $body_color ); ?>;
	font-family: <?php echo wp_kses_post( $body_font ); ?>;
	font-weight: <?php echo esc_attr( $body_weight ); ?>;
	letter-spacing: <?php echo esc_attr( $body_lspace ); ?>;
	font-size:16px;
	line-height: 24px;
}

p,
ol,
ul,
figure {
	margin: 0 0 16px;
	padding: 0;
}

a {
	color: <?php echo sanitize_hex_color( $highlight_color ); ?>;
}

a:hover {
	color: <?php echo sanitize_hex_color( $highlight_color ); ?>;
	text-decoration: underline;
}
.btn, .button, button {
    color: #fff;
    cursor: pointer;
    padding: .518em 1.287em;
    text-decoration: none;
    font-weight: 600;
	border: 0;
    text-shadow: none;
    display: inline-block;
    outline: 0;
    -webkit-appearance: none;
    -webkit-font-smoothing: antialiased;
    border-radius: 0;
    font-family: <?php echo wp_kses_post( $body_font ); ?>;
	letter-spacing: <?php echo esc_attr( $body_lspace ); ?>;
	font-size:16px;
	line-height: 24px;
    background: <?php echo sanitize_hex_color( $highlight_color ); ?>;
}
.btn:hover, .button:hover, .btn:active, .button:active, .btn:focus .button:focus  {
    color: #fff;
    text-decoration: none;
    background: <?php echo sanitize_hex_color( $highlight_color ); ?>;
    opacity:.8;
}
/* Breadcrumbs */
#amp-bread {
    font-size: 12px;
    margin: 5px 0;
}
/* Quotes */

blockquote {
	color: <?php echo sanitize_hex_color( $body_color ); ?>;
	background: rgba(127,127,127,.1);
	border-left: 2px solid <?php echo sanitize_hex_color( $highlight_color ); ?>;
	margin: 8px 0 24px 0;
	padding: 16px;
}

blockquote p:last-child {
	margin-bottom: 0;
}

/* Header */

.amp-wp-header {
	background-color: <?php echo sanitize_hex_color( $header_background_color ); ?>;
}

.amp-wp-header > div {
	color: <?php echo sanitize_hex_color( $logo_color ); ?>;
	font-size: 18px;
	line-height:50px;
	max-width: <?php echo absint( $content_max_width ); ?>px;
	margin: 0 auto;
	padding: 0 16px;
	position: relative;
	min-height: 50px;
	box-sizing: border-box;
}

.amp-wp-header a {
	text-decoration: none;
	display:block;
	float:left;
	font-family:  <?php echo wp_kses_post( $logo_font ); ?>;
	color: <?php echo sanitize_hex_color( $logo_color ); ?>;
	font-weight: <?php echo esc_attr( $logo_weight ); ?>;
	letter-spacing: <?php echo esc_attr( $logo_lspace ); ?>;
}

/* Site Logo */

.amp-wp-header .amp-wp-site-icon {
	margin: 9px 8px 9px 0;
    float: left
}

/* Menu */
button.amp-menu-sidebar {
    display: block;
    float: right;
    height: 50px;
    background: rgba(0,0,0,0.0);
   	font-family:  <?php echo wp_kses_post( $menu_icon_font ); ?>;
	color: <?php echo sanitize_hex_color( $menu_icon_color ); ?>;
	font-weight: <?php echo esc_attr( $menu_icon_weight ); ?>;
	letter-spacing: <?php echo esc_attr( $menu_icon_lspace ); ?>;
    border: none;
    padding:0;
    font-size: 15px;
    cursor: pointer;
}
.amp-icon-button {
    position: relative;
    width: 28px;
}
.amp-icon-button:before {
    content: "";
    position: absolute;
    top: 15px;
    right: 0;
    width: 28px;
    height: 4px;
    border-top: 12px double <?php echo sanitize_hex_color( $menu_icon_icon_color ); ?>;
    border-bottom: 4px solid <?php echo sanitize_hex_color( $menu_icon_icon_color ); ?>;
}
amp-sidebar {
	background-color: <?php echo sanitize_hex_color( $menu_background_color ); ?>;
	width:250px;
}
.close-amp-sidebar {
    font-size: 12px;
    font-family: sans-serif;
    background: rgba(0,0,0,.25);
    letter-spacing: 1px;
    padding: 12px;
    line-height: 8px;
    font-weight: bold;
    margin: 12px 12px 2px;
    cursor: pointer;
    display: inline-block;
    color: #fff;
}
.amp-sidebar-navigation ul.menu {
    margin: 0 12px;
}
.amp-sidebar-navigation ul.menu li {
    list-style: none;
}
.amp-sidebar-navigation ul.menu li a {
    text-decoration: none;
    display: block;
    padding: 8px 10px;
    border-bottom: 1px solid rgba(255,255,255,.1);
    font-size: 15px;
    line-height: 20px;
    font-family:  <?php echo wp_kses_post( $menu_font ); ?>;
	color: <?php echo sanitize_hex_color( $menu_color ); ?>;
	font-weight: <?php echo esc_attr( $menu_weight ); ?>;
	letter-spacing: <?php echo esc_attr( $menu_lspace ); ?>;
}
.amp-sidebar-navigation ul.menu ul {
    padding-left: 20px;
    margin-bottom: 0;
}
/* main */

.main {
	color: <?php echo sanitize_hex_color( $body_color ); ?>;
	font-weight: 400;
	margin: 16px auto;
	max-width: <?php echo absint( $content_max_width ); ?>px;
	overflow-wrap: break-word;
	word-wrap: break-word;
	padding: 0 16px;
	box-sizing: border-box;
}

/* Article Header */

.amp-wp-article-header {
	align-items: center;
	align-content: stretch;
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
	margin: 16px 0 10px;
}

.amp-wp-title {
	display: block;
	width: 100%;
	margin:0;
}

/* Article Meta */
.amp-wp-article-meta {
	margin: 0px 0 10px 0;
}
.amp-wp-meta {
	color: <?php echo sanitize_hex_color( $muted_text_color ); ?>;
	display: inline-block;
	font-size: .875em;
	line-height: 1.5em;
	margin: 0;
	padding: 0;
}

.amp-wp-article-header .amp-wp-meta:last-of-type {
	text-align: right;
}

.amp-wp-article-header .amp-wp-meta:first-of-type {
	text-align: left;
}

.amp-wp-byline amp-img,
.amp-wp-byline .amp-wp-author {
	display: inline-block;
	vertical-align: middle;
}

.amp-wp-byline amp-img {
	border: 1px solid <?php echo sanitize_hex_color( $highlight_color ); ?>;
	border-radius: 50%;
	position: relative;
	margin-right: 6px;
}

.amp-wp-posted-on {
	text-align: right;
}

/* Featured image */

.amp-wp-article-featured-image {
	margin: 0 0 1em;
}
.amp-wp-article-featured-image amp-img {
	margin: 0 auto;
	display:block;
}
.amp-wp-article-featured-image.wp-caption .wp-caption-text {
	margin: 0 18px;
}

/* Article Content */
.amp-wp-page-content {
	padding: 6px 0 0;
}
.amp-wp-article-content ul,
.amp-wp-article-content ol {
	margin-left: 1em;
}

.amp-wp-article-content amp-img {
	margin: 0 auto;
}

.amp-wp-article-content amp-img.alignright {
	margin: 0 0 1em 16px;
}

.amp-wp-article-content amp-img.alignleft {
	margin: 0 16px 1em 0;
}


/* comments */

.amp-comments {
    margin-top: 16px;
}
.comment-text {
    position: relative;
    padding-left: 76px;
    min-height:60px;
}
.comment-text p {
	margin-bottom:0;
}
.comment-list .comment-metadata a {
    font-size: 12px;
    color: #777;
}
.comment-list .comment-author amp-img {
    margin-right:10px;
}
ul.comment-list li {
    list-style-type: none;
}
.comment {
    margin-top: 16px;
    border: 1px solid #ddd;
    padding: 1em;
}
.comment-list amp-img {
    float: left;
}
/* Captions */

.wp-caption {
	padding: 0;
}

.wp-caption.alignleft {
	margin-right: 16px;
}

.wp-caption.alignright {
	margin-left: 16px;
}

.wp-caption .wp-caption-text {
	border-bottom: 1px solid #eee;
	color: <?php echo sanitize_hex_color( $muted_text_color ); ?>;
	font-size: .875em;
	line-height: 1.5em;
	margin: 0;
	padding: .66em 10px .75em;
}

/* AMP Media */

amp-iframe,
amp-youtube,
amp-instagram,
amp-vine {
	background: <?php echo sanitize_hex_color( $border_color ); ?>;
	margin: 0 -16px 1.5em;
}

.amp-wp-article-content amp-carousel amp-img {
	border: none;
}

amp-carousel > amp-img > img {
	object-fit: contain;
}

.amp-wp-iframe-placeholder {
	background: <?php echo sanitize_hex_color( $border_color ); ?> url( <?php echo esc_url( $this->get( 'placeholder_image_url' ) ); ?> ) no-repeat center 40%;
	background-size: 48px 48px;
	min-height: 48px;
}

/* Article Footer Meta */

.amp-wp-article-footer .amp-wp-meta {
	display: block;
}

.amp-wp-tax-category,
.amp-wp-tax-tag {
	margin: 16px 0;
	display: block;
}

.amp-wp-comments-link, .mod_show_original {
	color: <?php echo sanitize_hex_color( $muted_text_color ); ?>;
	font-size: .875em;
	line-height: 1.5em;
	text-align: center;
	margin: 1.5em 0 1.5em;
}
.amp-wp-comments-link a, .mod_show_original a{
	border-style: solid;
	border-color: <?php echo sanitize_hex_color( $border_color ); ?>;
	border-width: 1px 1px 2px;
	background-color: transparent;
	color: <?php echo sanitize_hex_color( $highlight_color ); ?>;
	cursor: pointer;
	display: block;
	font-size: 14px;
	font-weight: 600;
	line-height: 18px;
	margin: 0 auto;
	max-width: 200px;
	padding: 11px 16px;
	text-decoration: none;
	width: 50%;
	-webkit-transition: background-color 0.2s ease;
			transition: background-color 0.2s ease;
}
/* Related Posts */
.amp-related-posts ul {
	list-style:none;
}
.amp-related-posts ul li {
	margin-bottom:16px;
}
a.amp-related-img-link {
    margin-right: 10px;
    display: block;
}
.amp-related-posts .amp-related-meta {
	display:block;
}

/* AMP Footer */

.amp-wp-footer {
	background: <?php echo sanitize_hex_color( $footer_background_color ); ?>;
	color: <?php echo sanitize_hex_color( $footer_color ); ?>;
	font-weight: <?php echo esc_attr( $footer_weight ); ?>;
	font-size: <?php echo esc_attr( $footer_size ); ?>;
	line-height: <?php echo esc_attr( $footer_line_height ); ?>;
}

.amp-wp-footer > div {
	max-width: 600px;
    margin: 0 auto;
	padding: 16px;
	position: relative;
	box-sizing: border-box;
}

.amp-wp-footer a {
	text-decoration: none;
	color: <?php echo sanitize_hex_color( $footer_color ); ?>;
}
.show_original {
    display: block;
    text-align: center;
}
.show_original a {
    display: inline-block;
    margin-top: 10px;
    padding:4px 8px;
    border: 1px solid <?php echo sanitize_hex_color( $footer_color ); ?>;
}
.back-to-top {
	top: 16px;
	position: absolute;
	right: 16px;
}
@media (max-width: 544px) {
	.wp-caption.alignleft, .amp-wp-article-content amp-img.alignleft {
		float:none;
		margin-right:auto;
		margin-left:auto;
	}
}
