<?php
/**
 * QweerT Punk Zine — WooCommerce content-product.php
 * Product loop item — sticker-card design.
 *
 * Design: White card, thick black border, slight rotation, neon pink price,
 * full-width pink Add to Cart button, category badge top-left.
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

// Alternating slight rotations for the sticker-scatter effect
static $qweert_card_index = 0;
$rotations = array( '-1.8deg', '1.2deg', '-0.8deg', '1.8deg', '-1.2deg', '0.6deg', '-0.6deg', '1.5deg' );
$rotation  = $rotations[ $qweert_card_index % count( $rotations ) ];
$qweert_card_index++;

// Category
$cats     = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'names' ) );
$cat_name = ( ! empty( $cats ) && ! is_wp_error( $cats ) ) ? $cats[0] : '';

// Sale?
$is_on_sale = $product->is_on_sale();
?>
<li <?php wc_product_class( 'qweert-sticker-card', $product ); ?>
    data-rotate="<?php echo esc_attr( $rotation ); ?>"
    style="--card-rotate:<?php echo esc_attr( $rotation ); ?>;">

    <?php if ( $is_on_sale ) : ?>
        <span class="qweert-sale-badge">SALE!</span>
    <?php endif; ?>

    <?php if ( $cat_name ) : ?>
        <div class="qweert-card-cat"><?php echo esc_html( strtoupper( $cat_name ) ); ?></div>
    <?php endif; ?>

    <a href="<?php echo esc_url( get_permalink() ); ?>" class="qweert-card-link" aria-label="<?php the_title_attribute(); ?>">
        <div class="qweert-card-image">
            <?php
            if ( has_post_thumbnail() ) {
                the_post_thumbnail( 'woocommerce_thumbnail', array(
                    'class' => 'qweert-card-img',
                    'alt'   => get_the_title(),
                ) );
            } else {
                echo '<div class="qweert-card-img-placeholder"><span>🎨</span></div>';
            }
            ?>
        </div>

        <div class="qweert-card-body">
            <h2 class="qweert-card-title"><?php the_title(); ?></h2>

            <div class="qweert-card-price">
                <?php echo wp_kses_post( $product->get_price_html() ); ?>
            </div>
        </div>
    </a>

    <div class="qweert-card-footer">
        <?php
        // Add to cart button
        $add_to_cart_url  = esc_url( $product->add_to_cart_url() );
        $add_to_cart_text = esc_html( $product->add_to_cart_text() );
        $product_id       = $product->get_id();
        $product_type     = $product->get_type();

        if ( $product->is_purchasable() && $product->is_in_stock() ) :
        ?>
            <a href="<?php echo $add_to_cart_url; ?>"
               data-quantity="1"
               class="button add_to_cart_button ajax_add_to_cart qweert-add-to-cart"
               data-product_id="<?php echo esc_attr( $product_id ); ?>"
               data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
               aria-label="<?php echo esc_attr( sprintf( __( 'Add "%s" to your cart', 'woocommerce' ), get_the_title() ) ); ?>"
               rel="nofollow">
                <?php echo $add_to_cart_text; ?>
            </a>
        <?php elseif ( ! $product->is_in_stock() ) : ?>
            <span class="qweert-out-of-stock"><?php esc_html_e( 'OUT OF STOCK', 'qweert-punk-zine' ); ?></span>
        <?php else : ?>
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="qweert-add-to-cart qweert-add-to-cart--view">
                <?php esc_html_e( 'VIEW PRODUCT', 'qweert-punk-zine' ); ?>
            </a>
        <?php endif; ?>
    </div>

</li>
