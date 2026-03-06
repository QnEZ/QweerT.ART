<?php
/**
 * QweerT Punk Zine — footer.php
 */
$instagram = qweert_get_option( 'qweert_instagram_url', 'https://www.instagram.com/qweert.art/' );
$facebook  = qweert_get_option( 'qweert_facebook_url', 'https://www.facebook.com/QweerT.ART' );
$email     = qweert_get_option( 'qweert_contact_email', 'piper@qweert.art' );
$tagline   = qweert_get_option( 'qweert_footer_tagline', 'Queer art fights back ✊' );
?>

    <footer class="site-footer" id="colophon">
        <div class="site-footer__trans-stripe"></div>

        <div class="site-footer__main">
            <div class="container">
                <div class="site-footer__grid">

                    <!-- Brand column -->
                    <div>
                        <div class="site-logo__keys" aria-hidden="true" style="display:flex;gap:3px;margin-bottom:0.75rem;">
                            <?php
                            $letters = array( 'Q', 'W', 'E', 'E', 'R', 'T' );
                            foreach ( $letters as $i => $letter ) :
                                $class = ( $i === 3 ) ? 'site-logo__key site-logo__key--trans' : 'site-logo__key';
                            ?>
                                <div class="<?php echo esc_attr( $class ); ?>"><?php echo ( $i !== 3 ) ? esc_html( $letter ) : ''; ?></div>
                            <?php endforeach; ?>
                        </div>
                        <h3 class="site-footer__brand-title"><?php bloginfo( 'name' ); ?></h3>
                        <p class="site-footer__brand-text"><?php bloginfo( 'description' ); ?></p>
                    </div>

                    <!-- Quick links -->
                    <div>
                        <h4 class="site-footer__col-title site-footer__col-title--pink"><?php esc_html_e( 'QUICK LINKS', 'qweert-punk-zine' ); ?></h4>
                        <?php if ( has_nav_menu( 'footer' ) ) : ?>
                            <?php wp_nav_menu( array(
                                'theme_location' => 'footer',
                                'container'      => false,
                                'menu_class'     => 'site-footer__links',
                                'depth'          => 1,
                                'link_before'    => '→ ',
                            ) ); ?>
                        <?php else : ?>
                            <ul class="site-footer__links">
                                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">→ <?php esc_html_e( 'Home', 'qweert-punk-zine' ); ?></a></li>
                                <?php if ( function_exists( 'wc_get_page_id' ) ) : ?>
                                    <li><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">→ <?php esc_html_e( 'Shop', 'qweert-punk-zine' ); ?></a></li>
                                <?php endif; ?>
                                <?php
                                $privacy = get_privacy_policy_url();
                                if ( $privacy ) : ?>
                                    <li><a href="<?php echo esc_url( $privacy ); ?>">→ <?php esc_html_e( 'Privacy Policy', 'qweert-punk-zine' ); ?></a></li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <!-- Social / contact -->
                    <div>
                        <h4 class="site-footer__col-title site-footer__col-title--blue"><?php esc_html_e( 'FOLLOW THE RESISTANCE', 'qweert-punk-zine' ); ?></h4>

                        <?php if ( $instagram ) : ?>
                            <a href="<?php echo esc_url( $instagram ); ?>" class="site-footer__social-link" target="_blank" rel="noopener noreferrer">
                                <div class="site-footer__social-icon site-footer__social-icon--pink">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </div>
                                <span class="site-footer__social-label">@QweerT.ART</span>
                            </a>
                        <?php endif; ?>

                        <?php if ( $facebook ) : ?>
                            <a href="<?php echo esc_url( $facebook ); ?>" class="site-footer__social-link" target="_blank" rel="noopener noreferrer">
                                <div class="site-footer__social-icon site-footer__social-icon--blue">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </div>
                                <span class="site-footer__social-label">@QweerT.ART</span>
                            </a>
                        <?php endif; ?>

                        <?php if ( $email ) : ?>
                            <a href="mailto:<?php echo esc_attr( $email ); ?>" class="site-footer__social-link">
                                <div class="site-footer__social-icon site-footer__social-icon--yellow" style="font-family:'Space Mono',monospace;font-size:0.9rem;">@</div>
                                <span class="site-footer__social-label"><?php echo esc_html( $email ); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>

                </div><!-- .site-footer__grid -->

                <!-- Bottom bar -->
                <div class="site-footer__bottom">
                    <p class="site-footer__copyright">
                        &copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> &mdash;
                        <?php esc_html_e( 'All rights reserved. Made with', 'qweert-punk-zine' ); ?>
                        🏳️‍⚧️
                        <?php esc_html_e( 'and defiance.', 'qweert-punk-zine' ); ?>
                    </p>
                    <?php if ( $tagline ) : ?>
                        <span class="site-footer__tagline"><?php echo esc_html( $tagline ); ?></span>
                    <?php endif; ?>
                </div>

            </div><!-- .container -->
        </div><!-- .site-footer__main -->
    </footer><!-- #colophon -->

</div><!-- #page .site-wrapper -->

<?php wp_footer(); ?>
</body>
</html>
