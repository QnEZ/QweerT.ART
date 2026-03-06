<?php
/**
 * QweerT Punk Zine — functions.php
 * Theme setup, WooCommerce support, menus, widgets, and asset loading.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'QWEERT_VERSION', '1.2.0' );
define( 'QWEERT_DIR', get_template_directory() );
define( 'QWEERT_URI', get_template_directory_uri() );

/* ============================================================
   THEME SETUP
   ============================================================ */
function qweert_setup() {
    // Make theme available for translation
    load_theme_textdomain( 'qweert-punk-zine', QWEERT_DIR . '/languages' );

    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 600, 600, true );
    add_image_size( 'qweert-product-card', 400, 400, true );
    add_image_size( 'qweert-hero', 1920, 900, true );

    // Register nav menus
    register_nav_menus( array(
        'primary'  => __( 'Primary Menu', 'qweert-punk-zine' ),
        'footer'   => __( 'Footer Menu', 'qweert-punk-zine' ),
        'social'   => __( 'Social Links Menu', 'qweert-punk-zine' ),
    ) );

    // Switch default core markup to output valid HTML5
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
    ) );

    // Custom background
    add_theme_support( 'custom-background', array(
        'default-color' => '0d0d0d',
    ) );

    // Custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Gutenberg wide/full alignment
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );

    // ── WooCommerce Support ──────────────────────────────────
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 600,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 10,
            'default_columns' => 3,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ),
    ) );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'qweert_setup' );

/* ============================================================
   ENQUEUE SCRIPTS & STYLES
   ============================================================ */
function qweert_scripts() {
    // Google Fonts: Bebas Neue, Space Mono, Permanent Marker
    wp_enqueue_style(
        'qweert-google-fonts',
        'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Permanent+Marker&display=swap',
        array(),
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'qweert-style',
        get_stylesheet_uri(),
        array( 'qweert-google-fonts' ),
        QWEERT_VERSION
    );

    // Theme JavaScript
    wp_enqueue_script(
        'qweert-main',
        QWEERT_URI . '/assets/js/main.js',
        array( 'jquery' ),
        QWEERT_VERSION,
        true
    );

    // Localise script with useful data
    wp_localize_script( 'qweert-main', 'qweertData', array(
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'qweert-nonce' ),
        'cartUrl'  => wc_get_cart_url(),
        'shopUrl'  => get_permalink( wc_get_page_id( 'shop' ) ),
        'siteUrl'  => home_url(),
    ) );

    // Comments script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'qweert_scripts' );

/* ============================================================
   WIDGETS
   ============================================================ */
function qweert_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Shop Sidebar', 'qweert-punk-zine' ),
        'id'            => 'shop-sidebar',
        'description'   => __( 'Widgets in this area will be shown on the shop and product pages.', 'qweert-punk-zine' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title section-stamp">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Footer Column 1', 'qweert-punk-zine' ),
        'id'            => 'footer-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="site-footer__col-title site-footer__col-title--pink">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'qweert_widgets_init' );

/* ============================================================
   WOOCOMMERCE CUSTOMISATIONS
   ============================================================ */

// Remove default WooCommerce wrappers (we use our own)
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'qweert_woo_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content',  'qweert_woo_wrapper_end', 10 );

function qweert_woo_wrapper_start() {
    echo '<div class="container"><div class="woo-content">';
}

function qweert_woo_wrapper_end() {
    echo '</div></div>';
}

// Remove WooCommerce breadcrumbs (optional — comment out to restore)
// remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// Customise breadcrumb styling
add_filter( 'woocommerce_breadcrumb_defaults', function( $defaults ) {
    $defaults['delimiter']   = ' <span style="color:#ff2d78">›</span> ';
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" style="font-family:\'Space Mono\',monospace;font-size:0.8rem;color:#666;padding:1rem 0;">';
    $defaults['wrap_after']  = '</nav>';
    return $defaults;
} );

// Change number of products per row
add_filter( 'loop_shop_columns', function() { return 3; } );

// Change number of products per page
add_filter( 'loop_shop_per_page', function() { return 12; }, 20 );

// Add punk-style product type label above product title
add_action( 'woocommerce_before_shop_loop_item_title', 'qweert_product_type_label', 5 );
function qweert_product_type_label() {
    global $product;
    $cats = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'names' ) );
    if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
        echo '<div class="product-type-label">' . esc_html( $cats[0] ) . '</div>';
    }
}

// Wrap product image in sticker-card div
add_action( 'woocommerce_before_shop_loop_item', function() {
    echo '<div class="product-card-inner">';
}, 5 );

add_action( 'woocommerce_after_shop_loop_item', function() {
    echo '</div>';
}, 20 );

/* ============================================================
   CART COUNT IN NAV
   ============================================================ */
function qweert_cart_count() {
    if ( function_exists( 'WC' ) ) {
        $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        return $count;
    }
    return 0;
}

// AJAX cart fragment update
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {
    ob_start();
    $count = WC()->cart->get_cart_contents_count();
    echo '<span class="cart-count" id="cart-count">' . esc_html( $count ) . '</span>';
    $fragments['#cart-count'] = ob_get_clean();
    return $fragments;
} );

/* ============================================================
   CUSTOM EXCERPT LENGTH
   ============================================================ */
add_filter( 'excerpt_length', function() { return 20; } );
add_filter( 'excerpt_more', function() {
    return ' <a href="' . get_permalink() . '" class="btn-punk" style="font-size:0.8rem;padding:0.3rem 0.8rem;">READ MORE</a>';
} );

/* ============================================================
   BODY CLASSES
   ============================================================ */
add_filter( 'body_class', function( $classes ) {
    if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
        $classes[] = 'is-woocommerce';
    }
    return $classes;
} );

/* ============================================================
   CUSTOMIZER OPTIONS
   ============================================================ */
add_action( 'customize_register', 'qweert_customizer' );
function qweert_customizer( $wp_customize ) {

    // ── Section: QweerT Theme Options ───────────────────────
    $wp_customize->add_section( 'qweert_options', array(
        'title'    => __( 'QweerT Theme Options', 'qweert-punk-zine' ),
        'priority' => 30,
    ) );

    // Hero background image
    $wp_customize->add_setting( 'qweert_hero_bg', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'qweert_hero_bg', array(
        'label'   => __( 'Hero Background Image', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
    ) ) );

    // Hero headline line 1
    $wp_customize->add_setting( 'qweert_hero_line1', array(
        'default'           => 'CELEBRATING',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'qweert_hero_line1', array(
        'label'   => __( 'Hero Headline — Line 1', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
        'type'    => 'text',
    ) );

    // Hero headline line 2
    $wp_customize->add_setting( 'qweert_hero_line2', array(
        'default'           => 'QUEER ART',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'qweert_hero_line2', array(
        'label'   => __( 'Hero Headline — Line 2 (pink)', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
        'type'    => 'text',
    ) );

    // Hero headline line 3
    $wp_customize->add_setting( 'qweert_hero_line3', array(
        'default'           => '& INCLUSIVITY',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'qweert_hero_line3', array(
        'label'   => __( 'Hero Headline — Line 3 (blue)', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
        'type'    => 'text',
    ) );

    // Hero description
    $wp_customize->add_setting( 'qweert_hero_desc', array(
        'default'           => "QweerT.Art is a vibrant and inclusive brand celebrating LGBTQIA2s+ identities. From Ollie the Transgender Octopus to Alex the Non-Binary Axolotl — our stickers, pins, and art showcase the beauty of the queer and neurodivergent experience.",
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'qweert_hero_desc', array(
        'label'   => __( 'Hero Description', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
        'type'    => 'textarea',
    ) );

    // Hero tagline
    $wp_customize->add_setting( 'qweert_hero_tagline', array(
        'default'           => 'Art that fights back ✊',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'qweert_hero_tagline', array(
        'label'   => __( 'Hero Handwritten Tagline', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
        'type'    => 'text',
    ) );

    // Marquee slogans
    $wp_customize->add_setting( 'qweert_marquee_slogans', array(
        'default'           => "PROTECT TRANS KIDS | WE WON'T BE ERASED | NEURODIVERGENT AF | TRANS RIGHTS ARE HUMAN RIGHTS | BE TRUE FLY FREE | QUEER ART FIGHTS BACK",
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'qweert_marquee_slogans', array(
        'label'       => __( 'Marquee Slogans (pipe | separated)', 'qweert-punk-zine' ),
        'section'     => 'qweert_options',
        'type'        => 'textarea',
        'description' => __( 'Separate each slogan with a pipe character: |', 'qweert-punk-zine' ),
    ) );

    // About section background
    $wp_customize->add_setting( 'qweert_about_bg', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'qweert_about_bg', array(
        'label'   => __( 'About Section Background Image', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
    ) ) );

    // Contact email
    $wp_customize->add_setting( 'qweert_contact_email', array(
        'default'           => 'piper@qweert.art',
        'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'qweert_contact_email', array(
        'label'   => __( 'Contact Email (shown in header)', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
        'type'    => 'email',
    ) );

    // Instagram URL
    $wp_customize->add_setting( 'qweert_instagram_url', array(
        'default'           => 'https://www.instagram.com/qweert.art/',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'qweert_instagram_url', array(
        'label'   => __( 'Instagram URL', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
        'type'    => 'url',
    ) );

    // Facebook URL
    $wp_customize->add_setting( 'qweert_facebook_url', array(
        'default'           => 'https://www.facebook.com/QweerT.ART',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'qweert_facebook_url', array(
        'label'   => __( 'Facebook URL', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
        'type'    => 'url',
    ) );

    // Footer tagline
    $wp_customize->add_setting( 'qweert_footer_tagline', array(
        'default'           => 'Queer art fights back ✊',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'qweert_footer_tagline', array(
        'label'   => __( 'Footer Handwritten Tagline', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
        'type'    => 'text',
    ) );

    // Shop section title
    $wp_customize->add_setting( 'qweert_shop_title', array(
        'default'           => 'SHOP THE RESISTANCE',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'qweert_shop_title', array(
        'label'   => __( 'Homepage Shop Section Title', 'qweert-punk-zine' ),
        'section' => 'qweert_options',
        'type'    => 'text',
    ) );
}

/* ============================================================
   HELPER: Get customizer value with fallback
   ============================================================ */
function qweert_get_option( $key, $default = '' ) {
    return get_theme_mod( $key, $default );
}

/* ============================================================
   GITHUB UPDATER
   Hooks into WordPress's native update system so that update
   notifications appear in Appearance -> Themes automatically.
   Admin page: Appearance -> QweerT Theme
   ============================================================ */
require_once QWEERT_DIR . '/inc/class-github-updater.php';
require_once QWEERT_DIR . '/inc/admin-page.php';

/**
 * Singleton accessor for the updater instance.
 * Only instantiated in the admin context.
 *
 * @return QweerT_GitHub_Updater|null
 */
function qweert_get_updater_instance() {
    static $instance = null;
    if ( null === $instance && is_admin() ) {
        $theme   = wp_get_theme();
        $version = $theme->get( 'Version' );
        $token   = get_option( 'qweert_github_token', '' );
        $instance = new QweerT_GitHub_Updater(
            'QnEZ',             // GitHub user / org
            'QweerT.ART',       // GitHub repo name
            'qweert-punk-zine', // Theme slug (folder name)
            $version,
            $token
        );
    }
    return $instance;
}

// Initialise the updater early in admin so the update transient filter
// is registered before WordPress checks for updates.
add_action( 'admin_init', function () {
    qweert_get_updater_instance();
}, 1 );
