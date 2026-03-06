<?php
/**
 * QweerT Punk Zine — front-page.php
 * The homepage template: Hero → Marquee → Shop Preview → About → Events
 */
get_header();

// Customizer values
$hero_bg      = qweert_get_option( 'qweert_hero_bg', get_template_directory_uri() . '/assets/images/hero-bg.webp' );
$hero_line1   = qweert_get_option( 'qweert_hero_line1', 'CELEBRATING' );
$hero_line2   = qweert_get_option( 'qweert_hero_line2', 'QUEER ART' );
$hero_line3   = qweert_get_option( 'qweert_hero_line3', '& INCLUSIVITY' );
$hero_desc    = qweert_get_option( 'qweert_hero_desc', "QweerT.Art is a vibrant and inclusive brand celebrating LGBTQIA2s+ identities. From <span style=\"color:#F5A9B8\">Ollie the Transgender Octopus</span> to <span style=\"color:#5BCEFA\">Alex the Non-Binary Axolotl</span> — our stickers, pins, and art showcase the beauty of the queer and neurodivergent experience." );
$hero_tagline = qweert_get_option( 'qweert_hero_tagline', 'Art that fights back ✊' );
$about_bg     = qweert_get_option( 'qweert_about_bg', '' );
$shop_title   = qweert_get_option( 'qweert_shop_title', 'SHOP THE RESISTANCE' );
$marquee_raw  = qweert_get_option( 'qweert_marquee_slogans', "PROTECT TRANS KIDS | WE WON'T BE ERASED | NEURODIVERGENT AF | TRANS RIGHTS ARE HUMAN RIGHTS | BE TRUE FLY FREE | QUEER ART FIGHTS BACK" );
$slogans      = array_map( 'trim', explode( '|', $marquee_raw ) );
$instagram    = qweert_get_option( 'qweert_instagram_url', 'https://www.instagram.com/qweert.art/' );
$facebook     = qweert_get_option( 'qweert_facebook_url', 'https://www.facebook.com/QweerT.ART' );
?>

<!-- ============================================================
     HERO SECTION
     ============================================================ -->
<section class="hero-section scanlines" id="hero">
    <div class="hero-bg" style="background-image:url('<?php echo esc_url( $hero_bg ); ?>');"></div>
    <div class="hero-overlay"></div>
    <div class="trans-stripe-left"></div>

    <div class="hero-content">
        <div class="hero-content__inner">
            <div class="section-stamp"><?php esc_html_e( 'LGBTQIA2S+ ART & ACTIVISM', 'qweert-punk-zine' ); ?></div>

            <h1 class="hero-title hero-title--white"><?php echo esc_html( $hero_line1 ); ?></h1>
            <h1 class="hero-title hero-title--pink"><?php echo esc_html( $hero_line2 ); ?></h1>
            <h1 class="hero-title hero-title--blue"><?php echo esc_html( $hero_line3 ); ?></h1>

            <p class="hero-description"><?php echo wp_kses_post( $hero_desc ); ?></p>

            <div class="hero-ctas">
                <?php if ( function_exists( 'wc_get_page_id' ) ) : ?>
                    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn-punk">
                        <?php esc_html_e( 'SHOP NOW!', 'qweert-punk-zine' ); ?>
                    </a>
                <?php endif; ?>
                <a href="#about" class="btn-punk-outline">
                    <?php esc_html_e( 'OUR STORY', 'qweert-punk-zine' ); ?>
                </a>
            </div>

            <?php if ( $hero_tagline ) : ?>
                <p class="hero-tagline"><?php echo esc_html( $hero_tagline ); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Torn bottom edge -->
    <div class="hero-torn-edge">
        <svg viewBox="0 0 1440 40" preserveAspectRatio="none">
            <path d="M0,0 L0,20 Q60,40 120,15 Q180,0 240,25 Q300,40 360,10 Q420,0 480,30 Q540,40 600,12 Q660,0 720,28 Q780,40 840,8 Q900,0 960,32 Q1020,40 1080,14 Q1140,0 1200,26 Q1260,40 1320,10 Q1380,0 1440,22 L1440,40 L0,40 Z" fill="#0d0d0d"/>
        </svg>
    </div>
</section>

<!-- ============================================================
     MARQUEE SECTION
     ============================================================ -->
<div class="marquee-section">
    <!-- Row 1: pink, black text, left-to-right -->
    <div class="marquee-row marquee-row--pink">
        <div class="marquee-track">
            <?php foreach ( array_merge( $slogans, $slogans ) as $slogan ) : ?>
                <span class="marquee-item"><?php echo esc_html( $slogan ); ?></span>
                <span class="marquee-item">★</span>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Row 2: dark, blue text, right-to-left -->
    <div class="marquee-row marquee-row--dark">
        <div class="marquee-track marquee-track--reverse">
            <?php
            $products_row = array( 'OLLIE THE OCTOPUS', 'ALEX THE AXOLOTL', 'FAIRY JARS', 'ACRYLIC PINS', 'STICKERS', 'LUNAIRFAIRE NJ' );
            foreach ( array_merge( $products_row, $products_row ) as $item ) : ?>
                <span class="marquee-item"><?php echo esc_html( $item ); ?></span>
                <span class="marquee-item">◆</span>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ============================================================
     SHOP PREVIEW SECTION
     ============================================================ -->
<?php if ( function_exists( 'wc_get_page_id' ) ) : ?>
<section class="shop-section" id="shop">
    <div class="shop-section__halftone" aria-hidden="true"></div>
    <div class="container" style="position:relative;z-index:1;">

        <div class="shop-section__header">
            <div>
                <div class="section-stamp"><?php esc_html_e( 'THE COLLECTION', 'qweert-punk-zine' ); ?></div>
                <h2 class="shop-section__title">
                    <?php
                    // Split title at last space to colour the last word pink
                    $parts = explode( ' ', trim( $shop_title ) );
                    $last  = array_pop( $parts );
                    echo esc_html( implode( ' ', $parts ) ) . ' <span class="neon-pink">' . esc_html( $last ) . '</span>';
                    ?>
                </h2>
                <p class="shop-section__description"><?php esc_html_e( 'Stickers, pins & art that fight back. Every purchase supports queer art and activism.', 'qweert-punk-zine' ); ?></p>
            </div>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn-punk-outline" style="font-size:1rem;padding:0.5rem 1.5rem;align-self:flex-end;">
                <?php esc_html_e( 'VIEW ALL PRODUCTS →', 'qweert-punk-zine' ); ?>
            </a>
        </div>

        <?php
        // Display latest 6 products
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        $products_query = new WP_Query( $args );
        if ( $products_query->have_posts() ) :
        ?>
        <ul class="products-grid">
            <?php
            $rotations = array( '-2deg', '1.5deg', '-1deg', '2deg', '-1.5deg', '1deg' );
            $i = 0;
            while ( $products_query->have_posts() ) :
                $products_query->the_post();
                global $product;
                $rotation = $rotations[ $i % count( $rotations ) ];
                $i++;
            ?>
            <li class="sticker-card" style="transform:rotate(<?php echo esc_attr( $rotation ); ?>);">
                <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div style="border-bottom:3px solid #000;overflow:hidden;height:180px;">
                            <?php the_post_thumbnail( 'qweert-product-card', array( 'style' => 'width:100%;height:180px;object-fit:cover;' ) ); ?>
                        </div>
                    <?php else : ?>
                        <div style="height:140px;border-bottom:3px solid #000;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:3rem;">🎨</div>
                    <?php endif; ?>

                    <?php
                    $cats = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'names' ) );
                    if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) :
                    ?>
                        <div class="product-type-label"><?php echo esc_html( $cats[0] ); ?></div>
                    <?php endif; ?>

                    <h3 class="woocommerce-loop-product__title" style="font-family:'Space Mono',monospace;font-size:0.8rem;font-weight:700;color:#000;padding:0.25rem 0.75rem;line-height:1.4;"><?php the_title(); ?></h3>

                    <div style="padding:0 0.75rem 0.5rem;display:flex;justify-content:space-between;align-items:center;">
                        <?php if ( $product ) : ?>
                            <span style="font-family:'Bebas Neue',sans-serif;font-size:1.2rem;color:#ff2d78;"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                        <?php endif; ?>
                        <span style="font-family:'Bebas Neue',sans-serif;font-size:0.75rem;letter-spacing:0.1em;color:#ff2d78;">ADD TO CART →</span>
                    </div>
                </a>
            </li>
            <?php endwhile; wp_reset_postdata(); ?>
        </ul>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>

<!-- ============================================================
     ABOUT SECTION
     ============================================================ -->
<section class="about-section" id="about">
    <?php if ( $about_bg ) : ?>
        <div class="about-section__bg" style="background-image:url('<?php echo esc_url( $about_bg ); ?>');"></div>
    <?php endif; ?>
    <div class="about-section__overlay"></div>
    <div class="trans-stripe-right"></div>

    <div class="container about-section__content">
        <div class="about-section__grid">

            <!-- Left: text -->
            <div>
                <div class="section-stamp"><?php esc_html_e( 'OUR STORY', 'qweert-punk-zine' ); ?></div>
                <h2 class="about-section__title">
                    <?php esc_html_e( 'ART BORN FROM', 'qweert-punk-zine' ); ?>
                    <span class="neon-blue"><?php esc_html_e( 'PRIDE', 'qweert-punk-zine' ); ?></span>
                    <br>&amp; <?php esc_html_e( 'RESISTANCE', 'qweert-punk-zine' ); ?>
                </h2>

                <?php
                // Show the About page content if it exists, otherwise fallback text
                $about_page = get_page_by_path( 'about' );
                if ( $about_page ) {
                    echo '<div class="about-section__text">' . wp_kses_post( apply_filters( 'the_content', $about_page->post_content ) ) . '</div>';
                } else {
                    echo '<p class="about-section__text">' . esc_html__( 'QweerT.Art is a vibrant and inclusive brand that celebrates LGBTQIA2s+ identities and themes. From Ollie the Transgender Octopus to Alex the Non-Binary Axolotl, our stickers, apparel, and themed fairy jars showcase the beauty and diversity of the queer and neurodivergent experience.', 'qweert-punk-zine' ) . '</p>';
                    echo '<p class="about-section__text">' . esc_html__( 'QweerT.Art has been vending with LunarFaire in NJ since its first year. What started as PuzzlingMoments Photography has grown into a full art brand dedicated to amplifying queer voices and neurodivergent experiences.', 'qweert-punk-zine' ) . '</p>';
                }
                ?>

                <div class="about-section__actions">
                    <?php if ( $instagram ) : ?>
                        <a href="<?php echo esc_url( $instagram ); ?>" class="btn-punk-blue" target="_blank" rel="noopener noreferrer" style="font-size:0.9rem;padding:0.5rem 1.2rem;">
                            @QWEERT.ART ON INSTAGRAM
                        </a>
                    <?php endif; ?>
                    <?php if ( $facebook ) : ?>
                        <a href="<?php echo esc_url( $facebook ); ?>" class="btn-punk-outline" target="_blank" rel="noopener noreferrer" style="font-size:0.9rem;padding:0.5rem 1.2rem;">
                            FACEBOOK
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: character cards -->
            <div class="character-cards">
                <?php
                $characters = array(
                    array( 'name' => 'Ollie the Octopus',   'emoji' => '🐙', 'desc' => 'Transgender pride icon. Ollie was born from chalk art at Sussex Pride and has been fighting for trans kids ever since.', 'rotation' => '-2deg' ),
                    array( 'name' => 'Alex the Axolotl',    'emoji' => '🦎', 'desc' => 'Non-binary and proud. Alex represents the beauty of existing outside the binary.', 'rotation' => '2deg' ),
                    array( 'name' => 'Neurodivergent AF',   'emoji' => '🧠', 'desc' => 'Rainbow brain with skull. Celebrating neurodivergent minds in all their chaotic, beautiful glory.', 'rotation' => '1.5deg' ),
                    array( 'name' => 'Fairy Jar Scenes',    'emoji' => '✨', 'desc' => 'Magical diffused solar fairy jars — available in person at LunarFaire and other shows.', 'rotation' => '-1deg' ),
                );
                foreach ( $characters as $char ) : ?>
                    <div class="character-card" style="transform:rotate(<?php echo esc_attr( $char['rotation'] ); ?>);">
                        <div class="character-card__emoji"><?php echo esc_html( $char['emoji'] ); ?></div>
                        <h4 class="character-card__name"><?php echo esc_html( $char['name'] ); ?></h4>
                        <p class="character-card__desc"><?php echo esc_html( $char['desc'] ); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     EVENTS SECTION
     ============================================================ -->
<section class="events-section" id="events">
    <div class="container">
        <div class="events-section__grid">

            <!-- Left: events list -->
            <div>
                <div class="section-stamp"><?php esc_html_e( 'FIND US IRL', 'qweert-punk-zine' ); ?></div>
                <h2 class="events-section__title">
                    <?php esc_html_e( 'EVENTS &', 'qweert-punk-zine' ); ?>
                    <span class="neon-yellow"><?php esc_html_e( 'FAIRES', 'qweert-punk-zine' ); ?></span>
                </h2>
                <p style="font-size:0.85rem;color:#888;line-height:1.7;margin-bottom:2rem;">
                    <?php esc_html_e( 'Find us at LunarFaire in New Jersey and other similar shows where we bring our unique and empowering art to life.', 'qweert-punk-zine' ); ?>
                </p>

                <?php
                // Show events from a custom post type if it exists, otherwise show placeholder
                if ( post_type_exists( 'event' ) ) :
                    $events = new WP_Query( array(
                        'post_type'      => 'event',
                        'posts_per_page' => 5,
                        'post_status'    => 'publish',
                        'orderby'        => 'meta_value',
                        'meta_key'       => 'event_date',
                        'order'          => 'DESC',
                    ) );
                    while ( $events->have_posts() ) : $events->the_post();
                        $event_date   = get_post_meta( get_the_ID(), 'event_date', true );
                        $event_loc    = get_post_meta( get_the_ID(), 'event_location', true );
                        $event_status = get_post_meta( get_the_ID(), 'event_status', true ) ?: 'UPCOMING';
                        $status_class = ( strtolower( $event_status ) === 'past' ) ? 'event-card__status--past' : 'event-card__status--upcoming';
                ?>
                    <a href="<?php the_permalink(); ?>" class="event-card">
                        <div class="event-card__meta">
                            <span class="event-card__status <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( strtoupper( $event_status ) ); ?></span>
                            <?php if ( $event_date ) : ?>
                                <span class="event-card__date">📅 <?php echo esc_html( $event_date ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="event-card__name"><?php the_title(); ?></div>
                        <?php if ( $event_loc ) : ?>
                            <div class="event-card__location">📍 <?php echo esc_html( $event_loc ); ?></div>
                        <?php endif; ?>
                    </a>
                <?php endwhile; wp_reset_postdata();
                else :
                    // Fallback static events
                    $static_events = array(
                        array( 'name' => 'LunarFaire Elements Faire', 'date' => 'MAY 25, 2025', 'location' => 'Vasa Park, NJ', 'status' => 'UPCOMING' ),
                        array( 'name' => "LunarFaire Season Opener! Beltane Celebration", 'date' => 'APR 27, 2025', 'location' => 'Freehold, NJ', 'status' => 'PAST' ),
                    );
                    foreach ( $static_events as $ev ) :
                        $status_class = ( $ev['status'] === 'PAST' ) ? 'event-card__status--past' : 'event-card__status--upcoming';
                ?>
                    <div class="event-card">
                        <div class="event-card__meta">
                            <span class="event-card__status <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( $ev['status'] ); ?></span>
                            <span class="event-card__date">📅 <?php echo esc_html( $ev['date'] ); ?></span>
                        </div>
                        <div class="event-card__name"><?php echo esc_html( $ev['name'] ); ?></div>
                        <div class="event-card__location">📍 <?php echo esc_html( $ev['location'] ); ?></div>
                    </div>
                <?php endforeach; endif; ?>

            </div>

            <!-- Right: CTA panel -->
            <div class="events-cta">
                <div class="events-cta__corner" aria-hidden="true"></div>
                <p class="events-cta__tagline"><?php esc_html_e( "Don't miss us! 📍", 'qweert-punk-zine' ); ?></p>
                <h3 class="events-cta__title"><?php esc_html_e( 'WANT TO SEE US IN PERSON?', 'qweert-punk-zine' ); ?></h3>
                <p class="events-cta__text">
                    <?php esc_html_e( 'Find us at LunarFaire and other queer-friendly faires and markets across New Jersey. Come say hi, pick up some art, and support queer creators directly!', 'qweert-punk-zine' ); ?>
                </p>
                <div class="events-cta__actions">
                    <?php if ( function_exists( 'wc_get_page_id' ) ) : ?>
                        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn-punk" style="text-align:center;">
                            <?php esc_html_e( 'SHOP ONLINE INSTEAD', 'qweert-punk-zine' ); ?>
                        </a>
                    <?php endif; ?>
                    <?php if ( $instagram ) : ?>
                        <a href="<?php echo esc_url( $instagram ); ?>" class="btn-punk-outline" target="_blank" rel="noopener noreferrer" style="text-align:center;">
                            <?php esc_html_e( 'FOLLOW FOR UPDATES', 'qweert-punk-zine' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<?php get_footer(); ?>
