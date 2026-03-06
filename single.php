<?php
/**
 * QweerT Punk Zine — single.php
 * Template for single blog posts.
 */
get_header();
?>

<main id="primary" class="content-area">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>

            <div class="page-hero">
                <div class="section-stamp"><?php echo esc_html( get_the_date() ); ?></div>
                <h1 class="page-hero__title"><?php the_title(); ?></h1>
                <p class="page-hero__subtitle">
                    <?php printf( esc_html__( 'By %s', 'qweert-punk-zine' ), '<strong>' . esc_html( get_the_author() ) . '</strong>' ); ?>
                    <?php the_tags( ' &mdash; ', ', ', '' ); ?>
                </p>
            </div>

            <?php if ( has_post_thumbnail() ) : ?>
                <div style="margin:2rem 0;border:3px solid #000;box-shadow:6px 6px 0 #000;">
                    <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;max-height:500px;object-fit:cover;display:block;' ) ); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content" style="padding:2rem 0 4rem;max-width:800px;">
                <?php the_content(); ?>
                <?php wp_link_pages(); ?>
            </div>

            <nav class="post-navigation" style="padding:2rem 0;border-top:2px solid #2a2a2a;display:flex;justify-content:space-between;gap:1rem;font-family:'Bebas Neue',sans-serif;font-size:1rem;letter-spacing:0.1em;">
                <?php previous_post_link( '<div>← %link</div>', '%title' ); ?>
                <?php next_post_link( '<div>%link →</div>', '%title' ); ?>
            </nav>

            <?php if ( comments_open() || get_comments_number() ) : ?>
                <?php comments_template(); ?>
            <?php endif; ?>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
