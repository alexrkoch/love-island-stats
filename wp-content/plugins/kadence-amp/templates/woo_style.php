<?php
// Get the options
$kadence_amp = kadence_amp_options();


// Get template colors
$highlight_color              = $kadence_amp[ 'highlight_color' ];
$body_color            		= $kadence_amp[ 'body_font' ][ 'color' ];


?>

/* Product Header */
.amp-product-title {
	margin:0;
}
/* Product select */
.amp-atribute-select-block {
    padding: 0 0 5px 0;
    margin: 0 0 10px 0;
}
.amp-atribute-select-block select {
	width: 100%;
	max-width: 75%;
	background: #eee;
}
.kt-amp-add-to-cart {
    flex-grow: 2;
    text-align: right;
}
select {
    min-width: 120px;
    font-size: 14px;
}
p.price-description, .amp_var_price{
    font-weight: bold;
    font-size: 120%;
    margin-bottom: 0px;
}
.amp-price-contain {
	margin-bottom: 16px;
}
.amp-price-contain .stock {
    font-style: italic;
    font-size: 14px;
}
.amp_var_price{
	margin-right:10px;
}
.kt-amp-add-to-cart, .amp-rating {
    margin-bottom: 16px;
}
.amp-product-action {
    margin: 5px 0;
}
.amp-product-action .kt-amp-add-to-cart {
	margin-bottom:0;
}
.kt-amp-product-form {
    padding-bottom: 16px;
}

.amp-price-contain.amp-float-left {
    line-height: 44px;
    float: left;
}
.kt-amp-add-to-cart.amp-float-right {
    text-align: right;
    margin-bottom:20px;
}
.amp-product-meta {
    border-top: 1px solid rgba(0,0,0,.1);
    border-bottom: 1px solid rgba(0,0,0,.1);
    padding: 5px 0;
    margin-bottom: 16px;
}
.amp-product-meta small {
    font-size: 12px;
    font-style: italic;
    display: block;
}

/* tabs */

.ampTabContainer {
    padding-top: 20px;
    padding-bottom: 20px;
}
.tabContent {
    line-height: 1.5rem;
    display: none;
    width: 100%;
    order: 1;
    padding: 10px 0;
}
.tabButton {
    list-style: none;
    text-align: center;
    cursor: pointer;
    outline: none;
}
.tabButton[selected]::after {
    content: '';
    display: block;
    height: 4px;
    background: <?php echo sanitize_hex_color( $highlight_color ); ?>;
}
.tabButton[selected]+.tabContent {
    display: block;
}
.ampTabContainer {
    display: flex;
    flex-wrap: wrap;
}
.ampstart-headerbar-nav {
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    line-height: 30px;
}
.ampstart-headerbar-nav .ampstart-nav-item {
    padding: 0 15px 0 0;
    background: 0 0;
    opacity: .8;
}
amp-selector [option][selected] {
    outline: #fff;
    opacity: 1;
}
.ampstart-nav-item:active, .ampstart-nav-item:focus, .ampstart-nav-item:hover {
    opacity: 1;
}

/* Add to cart Content */

.amp_price_hide {
    display: none;
}
table.shop_attributes p {
    margin: 0;
}
.amp-product-quantity {
    border: 1px solid #eee;
}
.amp-product-quantity span {
    padding: .518em 0;
    background: #eee;
    display: block;
    float: left;
    text-align: center;
    min-width: 30px;
    line-height: 22px;
    margin: 0;
    font-weight:bold;
    box-sizing: border-box;
}
.amp-product-quantity span.amp-qty {
    background: white;
    min-width: 50px;
}
.amp-product-action .kt-amp-add-to-cart {
    position: relative;
}
.hide_out_of_stock {
	display:none;
}
.show_out_of_stock {
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,.9);
    font-size: 16px;
    font-weight: bold;
    padding: .518em 1.287em;
    box-sizing: border-box;
    z-index: 5;
}

/* STAR RATING */

.star-rating span:before {
    content: "★★★★★";
    top: 0;
    position: absolute;
    left: 0;
}
.star-rating span {
    overflow: hidden;
    float: left;
    top: 0;
    left: 0;
    position: absolute;
    padding-top: 16px;
}
.star-rating {
    color: <?php echo sanitize_hex_color( $highlight_color ); ?>;
    float: right;
    overflow: hidden;
    position: relative;
    height: 15px;
    line-height: 15px;
    font-size: 15px;
    width: 75px;
}
.star-rating:before {
    content: "☆☆☆☆☆";
    color: #dfdbdf;
    float: left;
    top: 0;
    left: 0;
    position: absolute;
}

.amp-rating .star-rating {
    float: none;
}


/ * Product Grid */ 
.amp-kt-products-grid {
	margin: 16px -5px;
}
.amp-product-item-title {
	color: <?php echo sanitize_hex_color( $body_color ); ?>;
	font-weight: bold;
	text-decoration: none;
	margin: 5px 0 10px;
}
table.shop_attributes {
	width: 100%;
}
.amp-kt-product-item {
	width:50%;
	float:left;
	text-align:center;
	padding: 0 5px;
	box-sizing: border-box;
}
.amp-kt-product-item:nth-child(3) {
	clear: left;
}
.amp-kt-product-item amp-img {
	margin: 0 auto;
	max-width: 100%;
}
.amp-kt-product-item .amp-rating {
	margin-bottom:10px;
}
.amp-kt-product-item .amp-rating .star-rating {
	float: none;
	width: 75px;
	margin: 0 auto;
}
a.amp-product-item-link {
	text-decoration: none;
}

/* Reviews */
.amp-reviews .reply {
	display: none;
}
.woocommerce-review__published-date, .woocommerce-review__dash {
    color: #777777;
    font-size: .875em;
    line-height: 1.5em;
}


