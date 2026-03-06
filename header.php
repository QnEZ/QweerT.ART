<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'qweert-punk-zine' ); ?></a>

<div class="site-wrapper" id="page">

    <header class="site-header" id="masthead">

        <!-- Utility bar -->
        <div class="site-header__utility-bar">
            <span class="site-header__utility-email">
                <?php
                $email = qweert_get_option( 'qweert_contact_email', 'piper@qweert.art' );
                echo esc_html( $email );
                ?>
            </span>
            <div class="site-header__utility-links">
                <?php
                $instagram = qweert_get_option( 'qweert_instagram_url', 'https://www.instagram.com/qweert.art/' );
                if ( $instagram ) : ?>
                    <a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        Instagram
                    </a>
                <?php endif; ?>

                <?php if ( function_exists( 'WC' ) ) : ?>
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        <?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?> Items
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main nav bar -->
        <div class="site-header__main">

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <div class="site-logo__keys" aria-hidden="true">
                        <?php
                        $letters = array( 'Q', 'W', 'E', 'E', 'R', 'T' );
                        foreach ( $letters as $i => $letter ) :
                            $class = ( $i === 3 ) ? 'site-logo__key site-logo__key--trans' : 'site-logo__key';
                        ?>
                            <div class="<?php echo esc_attr( $class ); ?>"><?php echo ( $i !== 3 ) ? esc_html( $letter ) : ''; ?></div>
                        <?php endforeach; ?>
                    </div>
                    <span class="site-logo__text"><?php bloginfo( 'name' ); ?></span>
                <?php endif; ?>
            </a>

            <!-- Primary navigation -->
            <nav class="main-navigation" id="site-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'qweert-punk-zine' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => 'qweert_fallback_menu',
                ) );
                ?>

                <?php if ( function_exists( 'WC' ) ) : ?>
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="nav-cart-link" aria-label="<?php esc_attr_e( 'Cart', 'qweert-punk-zine' ); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        <span class="cart-count" id="cart-count"><?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn-punk" style="font-size:0.9rem;padding:0.4rem 1rem;">
                    <?php esc_html_e( 'SHOP NOW!', 'qweert-punk-zine' ); ?>
                </a>
            </nav>

            <!-- Mobile toggle -->
            <button class="menu-toggle" id="menu-toggle" aria-controls="site-navigation" aria-expanded="false">
                <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'qweert-punk-zine' ); ?></span>
                <svg id="icon-menu" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                <svg id="icon-close" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>

        </div><!-- .site-header__main -->
    </header><!-- #masthead -->

<?php
/**
 * Fallback menu when no menu is assigned
 */
function qweert_fallback_menu() {
    echo '<ul id="primary-menu">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'HOME', 'qweert-punk-zine' ) . '</a></li>';
    if ( function_exists( 'wc_get_page_id' ) ) {
        echo '<li><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'SHOP', 'qweert-punk-zine' ) . '</a></li>';
    }
    echo '</ul>';
}
