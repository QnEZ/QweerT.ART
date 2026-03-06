<?php
/**
 * QweerT Punk Zine — WooCommerce cart.php
 * Cart page template.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<main id="primary" class="content-area">
    <div class="container">

        <div class="page-hero">
            <div class="section-stamp"><?php esc_html_e( 'YOUR HAUL', 'qweert-punk-zine' ); ?></div>
            <h1 class="page-hero__title"><?php esc_html_e( 'CART', 'qweert-punk-zine' ); ?></h1>
        </div>

        <?php wc_print_notices(); ?>

        <?php do_action( 'woocommerce_before_cart' ); ?>

        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                <thead>
                    <tr>
                        <th class="product-remove"></th>
                        <th class="product-thumbnail"></th>
                        <th class="product-name"><?php esc_html_e( 'PRODUCT', 'qweert-punk-zine' ); ?></th>
                        <th class="product-price"><?php esc_html_e( 'PRICE', 'qweert-punk-zine' ); ?></th>
                        <th class="product-quantity"><?php esc_html_e( 'QTY', 'qweert-punk-zine' ); ?></th>
                        <th class="product-subtotal"><?php esc_html_e( 'TOTAL', 'qweert-punk-zine' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                    ?>
                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                        <td class="product-remove">
                            <?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" style="color:#ff2d78;font-size:1.2rem;font-weight:700;">✕</a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), esc_attr__( 'Remove this item', 'qweert-punk-zine' ), esc_attr( $product_id ), esc_attr( $_product->get_sku() ) ), $cart_item_key ); ?>
                        </td>
                        <td class="product-thumbnail">
                            <?php $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 60, 60 ) ), $cart_item, $cart_item_key );
                            if ( $product_permalink ) echo '<a href="' . esc_url( $product_permalink ) . '">' . $thumbnail . '</a>';
                            else echo $thumbnail; ?>
                        </td>
                        <td class="product-name">
                            <?php if ( $product_permalink ) echo '<a href="' . esc_url( $product_permalink ) . '" style="color:var(--color-text);font-family:\'Space Mono\',monospace;font-size:0.85rem;">' . wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '</a>';
                            else echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
                            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
                            echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                        </td>
                        <td class="product-price">
                            <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                        </td>
                        <td class="product-quantity">
                            <?php if ( $_product->is_sold_individually() ) {
                                echo apply_filters( 'woocommerce_cart_item_quantity', '1', $cart_item_key, $cart_item );
                            } else {
                                echo apply_filters( 'woocommerce_cart_item_quantity', woocommerce_quantity_input( array( 'input_name' => "cart[{$cart_item_key}][qty]", 'input_value' => $cart_item['quantity'], 'max_value' => $_product->get_max_purchase_quantity(), 'min_value' => '0', 'product_name' => $_product->get_name() ), $_product, false ), $cart_item_key, $cart_item );
                            } ?>
                        </td>
                        <td class="product-subtotal">
                            <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                        </td>
                    </tr>
                    <?php endif; endforeach; ?>

                    <?php do_action( 'woocommerce_cart_contents' ); ?>

                    <tr>
                        <td colspan="6" class="actions">
                            <?php if ( wc_coupons_enabled() ) : ?>
                                <div class="coupon" style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                                    <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'qweert-punk-zine' ); ?></label>
                                    <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'qweert-punk-zine' ); ?>" style="background:var(--color-bg-card);border:2px solid var(--color-border);color:var(--color-text);font-family:'Space Mono',monospace;padding:0.5rem 0.75rem;border-radius:0;" />
                                    <button type="submit" class="button btn-punk" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'qweert-punk-zine' ); ?>"><?php esc_html_e( 'APPLY', 'qweert-punk-zine' ); ?></button>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="button btn-punk-outline" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'qweert-punk-zine' ); ?>"><?php esc_html_e( 'UPDATE CART', 'qweert-punk-zine' ); ?></button>
                            <?php do_action( 'woocommerce_cart_actions' ); ?>
                            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                        </td>
                    </tr>

                    <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                </tbody>
            </table>

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>

        <div class="cart-collaterals" style="display:flex;justify-content:flex-end;margin-top:2rem;">
            <?php do_action( 'woocommerce_cart_collaterals' ); ?>
        </div>

        <?php do_action( 'woocommerce_after_cart' ); ?>

    </div>
</main>

<?php get_footer(); ?>
