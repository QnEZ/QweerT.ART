<?php
/**
 * QweerT Punk Zine — search.php
 * Search results page.
 */
get_header();
?>

<main id="primary" class="content-area">
    <div class="container">

        <div class="page-hero">
            <div class="section-stamp"><?php esc_html_e( 'SEARCH', 'qweert-punk-zine' ); ?></div>
            <h1 class="page-hero__title">
                <?php printf( esc_html__( 'RESULTS FOR: %s', 'qweert-punk-zine' ), '<span class="neon-pink">' . esc_html( get_search_query() ) . '</span>' ); ?>
            </h1>
        </div>

        <?php if ( have_posts() ) : ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;padding:3rem 0;">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'sticker-card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" style="display:block;border-bottom:3px solid #000;">
                                <?php the_post_thumbnail( 'medium', array( 'style' => 'width:100%;height:180px;object-fit:cover;' ) ); ?>
                            </a>
                        <?php endif; ?>
                        <div style="padding:1rem;">
                            <div class="product-type-label" style="margin-bottom:0.5rem;"><?php echo get_post_type_object( get_post_type() )->labels->singular_name; ?></div>
                            <h2 style="font-family:'Bebas Neue',sans-serif;font-size:1.4rem;letter-spacing:0.05em;color:#000;margin-bottom:0.5rem;">
                                <a href="<?php the_permalink(); ?>" style="color:#000;text-decoration:none;"><?php the_title(); ?></a>
                            </h2>
                            <div style="font-family:'Space Mono',monospace;font-size:0.75rem;color:#444;line-height:1.6;">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <div style="padding:5rem 0;text-align:center;">
                <p style="font-family:'Bebas Neue',sans-serif;font-size:2rem;color:#888;"><?php esc_html_e( 'NO RESULTS FOUND', 'qweert-punk-zine' ); ?></p>
                <div style="max-width:400px;margin:2rem auto;">
                    <?php get_search_form(); ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
