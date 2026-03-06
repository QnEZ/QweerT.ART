<?php
/**
 * QweerT Punk Zine — WooCommerce loop/orderby.php
 * Punk-styled sort dropdown for the shop page.
 */

defined( 'ABSPATH' ) || exit;

$catalog_orderby_options = apply_filters(
    'woocommerce_catalog_orderby',
    array(
        'menu_order' => __( 'Default sorting', 'woocommerce' ),
        'popularity' => __( 'Sort by popularity', 'woocommerce' ),
        'rating'     => __( 'Sort by average rating', 'woocommerce' ),
        'date'       => __( 'Sort by latest', 'woocommerce' ),
        'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
        'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
    )
);

$orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );

if ( wc_get_loop_prop( 'is_shortcode' ) ) {
    return;
}
?>
<form class="woocommerce-ordering" method="get">
    <div class="qweert-sort-wrapper">
        <span class="qweert-sort-label">SORT:</span>
        <select name="orderby" class="orderby qweert-sort-select" aria-label="<?php esc_attr_e( 'Shop order', 'woocommerce' ); ?>">
            <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
                <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
            <?php endforeach; ?>
        </select>
        <?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
    </div>
</form>
