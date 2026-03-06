<?php
/**
 * QweerT Punk Zine — WooCommerce archive-product.php
 * The shop / product archive page.
 */
get_header();
?>

<main id="primary" class="content-area">
    <div class="container">

        <!-- Shop hero -->
        <div class="page-hero" style="padding-bottom:2rem;">
            <div class="section-stamp"><?php esc_html_e( 'THE COLLECTION', 'qweert-punk-zine' ); ?></div>
            <h1 class="page-hero__title">
                <?php esc_html_e( 'SHOP THE ', 'qweert-punk-zine' ); ?>
                <span class="neon-pink"><?php esc_html_e( 'RESISTANCE', 'qweert-punk-zine' ); ?></span>
            </h1>
            <p class="page-hero__subtitle"><?php esc_html_e( 'Stickers, pins & art that fight back. Every purchase supports queer art and activism.', 'qweert-punk-zine' ); ?></p>
        </div>

        <?php
        /**
         * Hook: woocommerce_before_main_content
         * Removed default wrapper, replaced with our own above.
         */
        do_action( 'woocommerce_archive_description' );
        ?>

        <!-- Toolbar: result count + ordering -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding:1rem 0;border-bottom:2px solid #2a2a2a;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
            <?php woocommerce_result_count(); ?>
            <?php woocommerce_catalog_ordering(); ?>
        </div>

        <?php if ( woocommerce_product_loop() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

            <?php if ( wc_get_loop_prop( 'total' ) ) : ?>
                <?php while ( have_posts() ) : ?>
                    <?php the_post(); ?>
                    <?php wc_get_template_part( 'content', 'product' ); ?>
                <?php endwhile; ?>
            <?php endif; ?>

            <?php woocommerce_product_loop_end(); ?>

            <?php
            /**
             * Hook: woocommerce_after_shop_loop
             * Pagination
             */
            do_action( 'woocommerce_after_shop_loop' );
            ?>

        <?php else : ?>
            <?php do_action( 'woocommerce_no_products_found' ); ?>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
