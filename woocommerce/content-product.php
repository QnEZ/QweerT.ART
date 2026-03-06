<?php
/**
 * QweerT Punk Zine — WooCommerce content-product.php
 * Product loop item (shop grid card).
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

// Rotation classes for sticker effect
static $qweert_card_index = 0;
$rotations = array( '-2deg', '1.5deg', '-1deg', '2deg', '-1.5deg', '1deg' );
$rotation  = $rotations[ $qweert_card_index % count( $rotations ) ];
$qweert_card_index++;
?>

<li <?php wc_product_class( 'sticker-card', $product ); ?> style="transform:rotate(<?php echo esc_attr( $rotation ); ?>);">
    <a href="<?php echo esc_url( get_permalink() ); ?>" style="text-decoration:none;color:inherit;display:block;">

        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item_title
         * Outputs: sale flash, product thumbnail
         */
        do_action( 'woocommerce_before_shop_loop_item_title' );
        ?>

        <?php
        // Product type / category label
        $cats = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'names' ) );
        if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) :
        ?>
            <div class="product-type-label"><?php echo esc_html( $cats[0] ); ?></div>
        <?php endif; ?>

        <h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>

        <?php
        /**
         * Hook: woocommerce_after_shop_loop_item_title
         * Outputs: rating, price
         */
        do_action( 'woocommerce_after_shop_loop_item_title' );
        ?>

        <div style="padding:0 0.75rem 0.75rem;display:flex;justify-content:space-between;align-items:center;">
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item
             * Outputs: add to cart button
             */
            do_action( 'woocommerce_after_shop_loop_item' );
            ?>
        </div>

    </a>
</li>
