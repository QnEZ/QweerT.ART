<?php
/**
 * QweerT Punk Zine — page.php
 * Template for static WordPress pages.
 */
get_header();
?>

<main id="primary" class="content-area">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>

            <div class="page-hero">
                <div class="section-stamp"><?php esc_html_e( 'PAGE', 'qweert-punk-zine' ); ?></div>
                <h1 class="page-hero__title"><?php the_title(); ?></h1>
            </div>

            <div class="entry-content" style="padding:3rem 0;max-width:800px;">
                <?php the_content(); ?>
                <?php wp_link_pages(); ?>
            </div>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
