<?php
/**
 * QweerT Punk Zine — 404.php
 * The 404 error page.
 */
get_header();
?>

<main id="primary" class="content-area">
    <div class="container" style="min-height:60vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:4rem 1rem;">
        <div>
            <div class="section-stamp" style="margin:0 auto 1rem;"><?php esc_html_e( 'ERROR', 'qweert-punk-zine' ); ?></div>
            <h1 style="font-family:'Bebas Neue',sans-serif;font-size:clamp(6rem,20vw,14rem);letter-spacing:0.03em;line-height:1;color:#ff2d78;text-shadow:0 0 40px rgba(255,45,120,0.4);">404</h1>
            <h2 style="font-family:'Bebas Neue',sans-serif;font-size:clamp(1.5rem,4vw,3rem);color:#f0ede8;letter-spacing:0.05em;margin-bottom:1rem;">
                <?php esc_html_e( 'PAGE NOT FOUND', 'qweert-punk-zine' ); ?>
            </h2>
            <p style="font-family:'Permanent Marker',cursive;font-size:1.2rem;color:#f5e642;transform:rotate(-1.5deg);display:inline-block;margin-bottom:2rem;">
                <?php esc_html_e( "This page doesn't exist... yet ✊", 'qweert-punk-zine' ); ?>
            </p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-punk"><?php esc_html_e( '← GO HOME', 'qweert-punk-zine' ); ?></a>
                <?php if ( function_exists( 'wc_get_page_id' ) ) : ?>
                    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn-punk-outline"><?php esc_html_e( 'SHOP INSTEAD', 'qweert-punk-zine' ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
