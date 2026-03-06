<?php
/**
 * QweerT Punk Zine — WooCommerce loop/loop-start.php
 * Opens the product loop with our punk-grid class.
 *
 * @see https://woocommerce.github.io/code-reference/
 */

defined( 'ABSPATH' ) || exit;

$columns = wc_get_loop_prop( 'columns' );
?>
<ul class="products columns-<?php echo esc_attr( $columns ); ?> qweert-product-grid">
