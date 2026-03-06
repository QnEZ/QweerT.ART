<?php
/**
 * QweerT Punk Zine — WooCommerce taxonomy-product_cat.php
 * Product category archive — reuses the same shop layout.
 */
get_header();
?>

<main id="primary" class="content-area qweert-shop-page">

    <!-- ── Category Hero ──────────────────────────────────── -->
    <?php
    $current_cat = get_queried_object();
    $cat_name    = $current_cat ? $current_cat->name : '';
    $cat_desc    = $current_cat ? $current_cat->description : '';
    ?>
    <div class="qweert-shop-hero">
        <div class="qweert-shop-hero__noise" aria-hidden="true"></div>
        <div class="qweert-shop-hero__stripe" aria-hidden="true"></div>
        <div class="container qweert-shop-hero__inner">
            <div class="section-stamp"><?php esc_html_e( 'CATEGORY', 'qweert-punk-zine' ); ?></div>
            <h1 class="qweert-shop-hero__title">
                <span class="neon-pink"><?php echo esc_html( strtoupper( $cat_name ) ); ?></span>
            </h1>
            <?php if ( $cat_desc ) : ?>
                <p class="qweert-shop-hero__sub"><?php echo wp_kses_post( $cat_desc ); ?></p>
            <?php else : ?>
                <p class="qweert-shop-hero__sub"><?php esc_html_e( 'Stickers, pins & art that fight back. Every purchase supports queer art and activism.', 'qweert-punk-zine' ); ?></p>
            <?php endif; ?>
        </div>
        <div class="qweert-shop-hero__torn" aria-hidden="true">
            <svg viewBox="0 0 1440 32" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,0 L0,16 Q80,32 160,10 Q240,0 320,20 Q400,32 480,8 Q560,0 640,22 Q720,32 800,6 Q880,0 960,24 Q1040,32 1120,10 Q1200,0 1280,18 Q1360,32 1440,12 L1440,32 L0,32 Z" fill="#0d0d0d"/>
            </svg>
        </div>
    </div>

    <div class="container">

        <?php do_action( 'woocommerce_archive_description' ); ?>

        <!-- ── Category Filter Pills ──────────────────────── -->
        <?php
        $product_cats = get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'exclude'    => get_option( 'default_product_cat' ),
        ) );
        $shop_url = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
        ?>
        <?php if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) : ?>
        <div class="qweert-cat-filters">
            <a href="<?php echo esc_url( $shop_url ); ?>" class="qweert-cat-pill">
                <?php esc_html_e( 'ALL', 'qweert-punk-zine' ); ?>
            </a>
            <?php foreach ( $product_cats as $cat ) : ?>
                <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"
                   class="qweert-cat-pill <?php echo ( $current_cat && $current_cat->term_id === $cat->term_id ) ? 'qweert-cat-pill--active' : ''; ?>">
                    <?php echo esc_html( strtoupper( $cat->name ) ); ?>
                    <span class="qweert-cat-pill__count"><?php echo esc_html( $cat->count ); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- ── Shop Toolbar ───────────────────────────────── -->
        <?php if ( woocommerce_product_loop() ) : ?>
        <div class="qweert-shop-toolbar">
            <div class="qweert-shop-toolbar__left">
                <?php woocommerce_result_count(); ?>
            </div>
            <div class="qweert-shop-toolbar__right">
                <?php woocommerce_catalog_ordering(); ?>
            </div>
        </div>

        <?php woocommerce_product_loop_start(); ?>
            <?php if ( wc_get_loop_prop( 'total' ) ) : ?>
                <?php while ( have_posts() ) : ?>
                    <?php the_post(); ?>
                    <?php wc_get_template_part( 'content', 'product' ); ?>
                <?php endwhile; ?>
            <?php endif; ?>
        <?php woocommerce_product_loop_end(); ?>

        <?php do_action( 'woocommerce_after_shop_loop' ); ?>

        <?php else : ?>
            <?php do_action( 'woocommerce_no_products_found' ); ?>
        <?php endif; ?>

    </div>

</main>

<?php get_footer(); ?>
