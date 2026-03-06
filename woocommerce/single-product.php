<?php
/**
 * QweerT Punk Zine — WooCommerce single-product.php
 * Single product page.
 */
get_header();
?>

<main id="primary" class="content-area">
    <div class="container">

        <!-- Breadcrumb -->
        <?php woocommerce_breadcrumb(); ?>

        <?php while ( have_posts() ) : ?>
            <?php the_post(); ?>
            <?php wc_get_template_part( 'content', 'single-product' ); ?>
        <?php endwhile; ?>

    </div>
</main>

<?php get_footer(); ?>
