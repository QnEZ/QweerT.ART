<?php
/**
 * QweerT Punk Zine — Admin Settings & Update Page
 *
 * Registers an "Appearance → QweerT Theme" admin page that shows:
 *   - Current installed version vs latest GitHub release
 *   - "Check for updates now" button (AJAX)
 *   - Optional GitHub Personal Access Token field (for private repos / rate limits)
 *   - Links to GitHub repo and release notes
 *
 * @package QweerT_Punk_Zine
 * @since   1.2.0
 */

defined( 'ABSPATH' ) || exit;

/* ------------------------------------------------------------------ */
/* Register the menu page                                               */
/* ------------------------------------------------------------------ */

add_action( 'admin_menu', function () {
    add_theme_page(
        __( 'QweerT Theme', 'qweert-punk-zine' ),
        __( 'QweerT Theme', 'qweert-punk-zine' ),
        'edit_theme_options',
        'qweert-theme-settings',
        'qweert_render_admin_page'
    );
} );

/* ------------------------------------------------------------------ */
/* Register settings                                                    */
/* ------------------------------------------------------------------ */

add_action( 'admin_init', function () {
    register_setting(
        'qweert_theme_options',
        'qweert_github_token',
        [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ]
    );
} );

/* ------------------------------------------------------------------ */
/* Enqueue admin styles + scripts only on our page                      */
/* ------------------------------------------------------------------ */

add_action( 'admin_enqueue_scripts', function ( string $hook ) {
    if ( 'appearance_page_qweert-theme-settings' !== $hook ) {
        return;
    }
    // Inline styles — no extra file needed.
    $css = '
        .qweert-admin-wrap { max-width: 760px; }
        .qweert-admin-wrap h1 { font-size: 1.6rem; margin-bottom: 0.25rem; }
        .qweert-version-card {
            background: #1a1a2e;
            border: 2px solid #ff2d78;
            padding: 1.25rem 1.5rem;
            margin: 1.5rem 0;
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }
        .qweert-version-card .ver-block { line-height: 1.4; }
        .qweert-version-card .ver-label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; color: #888; }
        .qweert-version-card .ver-num   { font-size: 1.6rem; font-weight: 700; color: #ff2d78; font-family: monospace; }
        .qweert-version-card .ver-num.up-to-date { color: #55cfff; }
        .qweert-check-btn {
            background: #ff2d78;
            color: #000;
            border: 2px solid #000;
            padding: 0.45rem 1.1rem;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.15s;
            border-radius: 0;
            margin-left: auto;
        }
        .qweert-check-btn:hover { background: #000; color: #ff2d78; border-color: #ff2d78; }
        .qweert-check-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        #qweert-update-result { margin-top: 0.75rem; font-size: 0.9rem; min-height: 1.4em; }
        #qweert-update-result.success { color: #55cfff; }
        #qweert-update-result.error   { color: #ff2d78; }
        .qweert-token-section { margin-top: 1.5rem; }
        .qweert-token-section label { font-weight: 600; display: block; margin-bottom: 0.4rem; }
        .qweert-token-section input[type=text] { width: 100%; max-width: 420px; font-family: monospace; }
        .qweert-token-section .description { color: #888; font-size: 0.82rem; margin-top: 0.3rem; }
        .qweert-links { margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap; }
        .qweert-links a { color: #55cfff; text-decoration: none; font-size: 0.85rem; }
        .qweert-links a:hover { color: #ff2d78; }
        .qweert-divider { border: none; border-top: 1px solid #2a2a2a; margin: 1.5rem 0; }
    ';
    wp_add_inline_style( 'wp-admin', $css );

    // Inline JS for the AJAX check button.
    $js = '
    document.addEventListener("DOMContentLoaded", function () {
        var btn    = document.getElementById("qweert-check-btn");
        var result = document.getElementById("qweert-update-result");
        var latestEl = document.getElementById("qweert-latest-ver");
        if (!btn) return;

        btn.addEventListener("click", function () {
            btn.disabled = true;
            btn.textContent = "Checking\u2026";
            result.textContent = "";
            result.className = "";

            fetch(ajaxurl, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({
                    action: "qweert_check_updates",
                    nonce:  btn.dataset.nonce
                })
            })
            .then(r => r.json())
            .then(function (data) {
                btn.disabled = false;
                btn.textContent = "Check for Updates Now";
                if (data.success) {
                    latestEl.textContent = "v" + data.data.latest_version;
                    latestEl.className = data.data.is_update_available ? "ver-num" : "ver-num up-to-date";
                    result.className = "success";
                    if (data.data.is_update_available) {
                        result.innerHTML = "\u2728 " + data.data.message +
                            " &mdash; <a href=\"" + data.data.release_url + "\" target=\"_blank\" style=\"color:#f5e642\">View release &rarr;</a>" +
                            " | <a href=\"themes.php\" style=\"color:#f5e642\">Go to Themes to update &rarr;</a>";
                    } else {
                        result.textContent = "\u2714 " + data.data.message;
                    }
                } else {
                    result.className = "error";
                    result.textContent = "\u26a0 " + (data.data && data.data.message ? data.data.message : "Unknown error.");
                }
            })
            .catch(function () {
                btn.disabled = false;
                btn.textContent = "Check for Updates Now";
                result.className = "error";
                result.textContent = "\u26a0 Network error. Please try again.";
            });
        });
    });
    ';
    wp_add_inline_script( 'jquery', $js );
} );

/* ------------------------------------------------------------------ */
/* Render the admin page                                                 */
/* ------------------------------------------------------------------ */

function qweert_render_admin_page(): void {
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'qweert-punk-zine' ) );
    }

    // Handle form save.
    if ( isset( $_POST['qweert_save_settings'] ) && check_admin_referer( 'qweert_save_settings_action' ) ) {
        $token = sanitize_text_field( wp_unslash( $_POST['qweert_github_token'] ?? '' ) );
        update_option( 'qweert_github_token', $token );
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'qweert-punk-zine' ) . '</p></div>';
    }

    // Get theme data.
    $theme           = wp_get_theme();
    $current_version = $theme->get( 'Version' );
    $saved_token     = get_option( 'qweert_github_token', '' );
    $nonce           = wp_create_nonce( 'qweert_check_updates' );

    // Try to get cached latest version without forcing a fresh API call.
    $updater      = qweert_get_updater_instance();
    $latest_ver   = $updater ? $updater->get_latest_version() : false;
    $latest_label = $latest_ver ? 'v' . esc_html( $latest_ver ) : esc_html__( '(click to check)', 'qweert-punk-zine' );
    $up_to_date   = $latest_ver && ! version_compare( $latest_ver, $current_version, '>' );
    ?>
    <div class="wrap qweert-admin-wrap">

        <h1>🎨 <?php esc_html_e( 'QweerT Punk Zine — Theme Settings', 'qweert-punk-zine' ); ?></h1>
        <p><?php esc_html_e( 'Manage theme updates and configuration options.', 'qweert-punk-zine' ); ?></p>

        <hr class="qweert-divider">

        <!-- Version card -->
        <div class="qweert-version-card">
            <div class="ver-block">
                <div class="ver-label"><?php esc_html_e( 'Installed', 'qweert-punk-zine' ); ?></div>
                <div class="ver-num">v<?php echo esc_html( $current_version ); ?></div>
            </div>
            <div class="ver-block">
                <div class="ver-label"><?php esc_html_e( 'Latest on GitHub', 'qweert-punk-zine' ); ?></div>
                <div id="qweert-latest-ver" class="ver-num <?php echo $up_to_date ? 'up-to-date' : ''; ?>">
                    <?php echo esc_html( $latest_label ); ?>
                </div>
            </div>
            <button id="qweert-check-btn"
                    class="qweert-check-btn"
                    data-nonce="<?php echo esc_attr( $nonce ); ?>">
                <?php esc_html_e( 'Check for Updates Now', 'qweert-punk-zine' ); ?>
            </button>
        </div>
        <div id="qweert-update-result"></div>

        <?php if ( $latest_ver && version_compare( $latest_ver, $current_version, '>' ) ) : ?>
            <div class="notice notice-warning inline">
                <p>
                    <?php
                    printf(
                        /* translators: 1: new version */
                        esc_html__( 'A new version (%s) is available! Go to %s to install it.', 'qweert-punk-zine' ),
                        '<strong>v' . esc_html( $latest_ver ) . '</strong>',
                        '<a href="' . esc_url( admin_url( 'themes.php' ) ) . '">' . esc_html__( 'Appearance → Themes', 'qweert-punk-zine' ) . '</a>'
                    );
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <hr class="qweert-divider">

        <!-- Settings form -->
        <form method="post" action="">
            <?php wp_nonce_field( 'qweert_save_settings_action' ); ?>

            <div class="qweert-token-section">
                <h2><?php esc_html_e( 'GitHub Access Token (Optional)', 'qweert-punk-zine' ); ?></h2>
                <label for="qweert_github_token">
                    <?php esc_html_e( 'Personal Access Token', 'qweert-punk-zine' ); ?>
                </label>
                <input type="text"
                       id="qweert_github_token"
                       name="qweert_github_token"
                       value="<?php echo esc_attr( $saved_token ); ?>"
                       placeholder="ghp_xxxxxxxxxxxxxxxxxxxx"
                       class="regular-text">
                <p class="description">
                    <?php esc_html_e( 'Optional. Provide a GitHub Personal Access Token to increase the API rate limit (60 → 5,000 requests/hour) or to access a private repository. The token only needs the "public_repo" scope for a public repo.', 'qweert-punk-zine' ); ?>
                    <a href="https://github.com/settings/tokens/new?scopes=public_repo&description=QweerT+Theme+Updater"
                       target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e( 'Generate a token on GitHub →', 'qweert-punk-zine' ); ?>
                    </a>
                </p>
            </div>

            <p class="submit">
                <input type="submit"
                       name="qweert_save_settings"
                       class="button button-primary"
                       value="<?php esc_attr_e( 'Save Settings', 'qweert-punk-zine' ); ?>">
            </p>
        </form>

        <hr class="qweert-divider">

        <!-- Links -->
        <div class="qweert-links">
            <a href="https://github.com/QnEZ/QweerT.ART" target="_blank" rel="noopener noreferrer">
                📦 <?php esc_html_e( 'GitHub Repository', 'qweert-punk-zine' ); ?>
            </a>
            <a href="https://github.com/QnEZ/QweerT.ART/releases" target="_blank" rel="noopener noreferrer">
                🏷️ <?php esc_html_e( 'All Releases & Changelogs', 'qweert-punk-zine' ); ?>
            </a>
            <a href="https://qweert.art" target="_blank" rel="noopener noreferrer">
                🎨 <?php esc_html_e( 'QweerT.ART', 'qweert-punk-zine' ); ?>
            </a>
        </div>

        <p style="color:#666;font-size:0.8rem;margin-top:2rem;">
            <?php
            printf(
                /* translators: 1: repo path */
                esc_html__( 'Updates are fetched from the GitHub Releases API for %s. The result is cached for 12 hours.', 'qweert-punk-zine' ),
                '<code>QnEZ/QweerT.ART</code>'
            );
            ?>
        </p>

    </div>
    <?php
}
