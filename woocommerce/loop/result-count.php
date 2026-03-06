<?php
/**
 * QweerT Punk Zine — WooCommerce loop/result-count.php
 * Punk-styled result count.
 */

defined( 'ABSPATH' ) || exit;

if ( ! woocommerce_products_will_display() ) {
    return;
}
?>
<p class="woocommerce-result-count qweert-result-count">
    <?php
    if ( 1 === (int) $total ) {
        esc_html_e( 'Showing the single result', 'woocommerce' );
    } elseif ( $total <= $per_page || -1 === $per_page ) {
        /* translators: %d: total results */
        printf( esc_html__( 'SHOWING ALL %d RESULTS', 'qweert-punk-zine' ), $total );
    } else {
        $first = ( $per_page * $current_page ) - $per_page + 1;
        $last  = min( $total, $per_page * $current_page );
        /* translators: 1: first result 2: last result 3: total results */
        printf( esc_html__( '%1$d&ndash;%2$d OF %3$d RESULTS', 'qweert-punk-zine' ), $first, $last, $total );
    }
    ?>
</p>
