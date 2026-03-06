<?php
/**
 * QweerT Punk Zine — GitHub Theme Updater
 *
 * Hooks into WordPress's native theme-update transient so that update
 * notifications appear in Appearance → Themes exactly like any other theme.
 * Checks the GitHub Releases API for a newer tag and, when one is found,
 * provides the zip download URL so WordPress can install it automatically.
 *
 * Usage: instantiate once from functions.php (admin-only).
 *
 * @package QweerT_Punk_Zine
 * @since   1.2.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'QweerT_GitHub_Updater' ) ) :

class QweerT_GitHub_Updater {

    /**
     * GitHub repository owner (username or org).
     * @var string
     */
    private string $github_user;

    /**
     * GitHub repository name.
     * @var string
     */
    private string $github_repo;

    /**
     * WordPress theme slug (folder name).
     * @var string
     */
    private string $theme_slug;

    /**
     * Current installed version (from style.css).
     * @var string
     */
    private string $current_version;

    /**
     * Optional GitHub Personal Access Token for private repos / higher rate limits.
     * Store via: Appearance → Theme Options → GitHub Token.
     * @var string
     */
    private string $access_token;

    /**
     * Transient key used to cache the remote release data.
     * @var string
     */
    private string $transient_key;

    /**
     * How long (in seconds) to cache the remote version check.
     * Default: 12 hours.
     * @var int
     */
    private int $cache_ttl;

    /**
     * Constructor.
     *
     * @param string $github_user     GitHub username / org.
     * @param string $github_repo     GitHub repository name.
     * @param string $theme_slug      Theme folder name (slug).
     * @param string $current_version Installed version string.
     * @param string $access_token    Optional GitHub PAT.
     * @param int    $cache_ttl       Cache TTL in seconds (default 43200 = 12 h).
     */
    public function __construct(
        string $github_user,
        string $github_repo,
        string $theme_slug,
        string $current_version,
        string $access_token = '',
        int    $cache_ttl    = 43200
    ) {
        $this->github_user     = $github_user;
        $this->github_repo     = $github_repo;
        $this->theme_slug      = $theme_slug;
        $this->current_version = $current_version;
        $this->access_token    = $access_token;
        $this->cache_ttl       = $cache_ttl;
        $this->transient_key   = 'qweert_github_release_' . md5( $github_user . '/' . $github_repo );

        $this->register_hooks();
    }

    /* ------------------------------------------------------------------ */
    /* Hook registration                                                    */
    /* ------------------------------------------------------------------ */

    private function register_hooks(): void {
        // Inject our data into the WordPress theme-update transient.
        add_filter( 'pre_set_site_transient_update_themes', [ $this, 'check_for_update' ] );

        // Provide theme info for the "View version x.x details" thickbox.
        add_filter( 'themes_api', [ $this, 'theme_api_info' ], 10, 3 );

        // After a successful update, clear our cached release data.
        add_action( 'upgrader_process_complete', [ $this, 'purge_cache' ], 10, 2 );

        // Admin notice when an update is available (shown on Themes page).
        add_action( 'admin_notices', [ $this, 'admin_update_notice' ] );

        // AJAX: manual "Check for updates now" button.
        add_action( 'wp_ajax_qweert_check_updates', [ $this, 'ajax_force_check' ] );
    }

    /* ------------------------------------------------------------------ */
    /* Remote release fetching                                              */
    /* ------------------------------------------------------------------ */

    /**
     * Fetch the latest release from the GitHub Releases API.
     * Results are cached in a transient to avoid hammering the API.
     *
     * @param bool $force_refresh Bypass cache and fetch fresh data.
     * @return array|false Release data array or false on failure.
     */
    public function get_remote_release( bool $force_refresh = false ): array|false {
        if ( ! $force_refresh ) {
            $cached = get_transient( $this->transient_key );
            if ( false !== $cached ) {
                return $cached;
            }
        }

        $api_url = sprintf(
            'https://api.github.com/repos/%s/%s/releases/latest',
            rawurlencode( $this->github_user ),
            rawurlencode( $this->github_repo )
        );

        $args = [
            'timeout'    => 10,
            'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url(),
            'headers'    => [ 'Accept' => 'application/vnd.github+json' ],
        ];

        if ( ! empty( $this->access_token ) ) {
            $args['headers']['Authorization'] = 'Bearer ' . $this->access_token;
        }

        $response = wp_remote_get( $api_url, $args );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            // Cache a short negative result so we don't spam the API on errors.
            set_transient( $this->transient_key, false, 300 );
            return false;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( empty( $body['tag_name'] ) ) {
            set_transient( $this->transient_key, false, 300 );
            return false;
        }

        // Normalise the tag: strip leading 'v' so "v1.2.0" → "1.2.0".
        $body['version'] = ltrim( $body['tag_name'], 'vV' );

        // Find the first .zip asset, or fall back to the auto-generated zipball.
        $body['zip_url'] = $body['zipball_url'] ?? '';
        if ( ! empty( $body['assets'] ) ) {
            foreach ( $body['assets'] as $asset ) {
                if ( isset( $asset['content_type'] ) && str_contains( $asset['content_type'], 'zip' ) ) {
                    $body['zip_url'] = $asset['browser_download_url'];
                    break;
                }
                if ( isset( $asset['name'] ) && str_ends_with( strtolower( $asset['name'] ), '.zip' ) ) {
                    $body['zip_url'] = $asset['browser_download_url'];
                    break;
                }
            }
        }

        set_transient( $this->transient_key, $body, $this->cache_ttl );
        return $body;
    }

    /* ------------------------------------------------------------------ */
    /* WordPress update transient filter                                    */
    /* ------------------------------------------------------------------ */

    /**
     * Inject update data into WordPress's update_themes transient.
     *
     * @param object $transient The transient value.
     * @return object Modified transient.
     */
    public function check_for_update( object $transient ): object {
        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        $release = $this->get_remote_release();

        if ( ! $release || empty( $release['version'] ) ) {
            return $transient;
        }

        if ( version_compare( $release['version'], $this->current_version, '>' ) ) {
            $transient->response[ $this->theme_slug ] = [
                'theme'       => $this->theme_slug,
                'new_version' => $release['version'],
                'url'         => $release['html_url'] ?? sprintf(
                    'https://github.com/%s/%s/releases',
                    $this->github_user,
                    $this->github_repo
                ),
                'package'     => $release['zip_url'],
                'requires'    => '6.0',
                'requires_php'=> '8.0',
            ];
        } else {
            // Explicitly mark as up to date so WP doesn't show stale data.
            unset( $transient->response[ $this->theme_slug ] );
        }

        return $transient;
    }

    /* ------------------------------------------------------------------ */
    /* Themes API info (thickbox "View details")                            */
    /* ------------------------------------------------------------------ */

    /**
     * Provide theme info for the "View version x.x details" thickbox popup.
     *
     * @param false|object|array $result The result object or array.
     * @param string             $action The type of information being requested.
     * @param object             $args   Theme API arguments.
     * @return false|object Modified result.
     */
    public function theme_api_info( false|object|array $result, string $action, object $args ): false|object {
        if ( 'theme_information' !== $action ) {
            return $result;
        }
        if ( ! isset( $args->slug ) || $args->slug !== $this->theme_slug ) {
            return $result;
        }

        $release = $this->get_remote_release();
        if ( ! $release ) {
            return $result;
        }

        $info = new stdClass();
        $info->name          = 'QweerT Punk Zine';
        $info->slug          = $this->theme_slug;
        $info->version       = $release['version'];
        $info->author        = '<a href="https://qweert.art">QweerT.ART / Piper</a>';
        $info->homepage      = sprintf( 'https://github.com/%s/%s', $this->github_user, $this->github_repo );
        $info->requires      = '6.0';
        $info->requires_php  = '8.0';
        $info->download_link = $release['zip_url'];
        $info->sections      = [
            'description' => '<p>An edgy Queer Punk Zine theme for QweerT.ART. Dark near-black background, neon hot pink and electric blue (trans flag colors), Bebas Neue display font, sticker-card WooCommerce products, dual scrolling activist marquee, and thick-outline punk aesthetic.</p>',
            'changelog'   => ! empty( $release['body'] )
                ? '<pre>' . esc_html( $release['body'] ) . '</pre>'
                : '<p>See <a href="' . esc_url( $release['html_url'] ?? '#' ) . '">GitHub Releases</a> for full changelog.</p>',
        ];

        return $info;
    }

    /* ------------------------------------------------------------------ */
    /* Cache management                                                     */
    /* ------------------------------------------------------------------ */

    /**
     * Purge the cached release data after a theme update completes.
     *
     * @param \WP_Upgrader $upgrader Upgrader instance.
     * @param array        $options  Upgrade options.
     */
    public function purge_cache( \WP_Upgrader $upgrader, array $options ): void {
        if ( 'update' === $options['action'] && 'theme' === $options['type'] ) {
            delete_transient( $this->transient_key );
        }
    }

    /**
     * Force-clear the cache (e.g. from admin UI).
     */
    public function clear_cache(): void {
        delete_transient( $this->transient_key );
    }

    /* ------------------------------------------------------------------ */
    /* Admin notice                                                         */
    /* ------------------------------------------------------------------ */

    /**
     * Show a styled admin notice on the Themes page when an update is available.
     */
    public function admin_update_notice(): void {
        $screen = get_current_screen();
        if ( ! $screen || 'themes' !== $screen->id ) {
            return;
        }

        $release = $this->get_remote_release();
        if ( ! $release || empty( $release['version'] ) ) {
            return;
        }
        if ( ! version_compare( $release['version'], $this->current_version, '>' ) ) {
            return;
        }

        $release_url = esc_url( $release['html_url'] ?? sprintf(
            'https://github.com/%s/%s/releases',
            $this->github_user,
            $this->github_repo
        ) );

        printf(
            '<div class="notice notice-warning is-dismissible qweert-update-notice">
                <p>
                    <strong>%s</strong> — %s
                    <a href="%s" target="_blank" rel="noopener noreferrer" style="margin-left:0.5em;">%s &rarr;</a>
                </p>
            </div>',
            esc_html__( 'QweerT Punk Zine update available!', 'qweert-punk-zine' ),
            sprintf(
                /* translators: 1: new version, 2: current version */
                esc_html__( 'Version %1$s is available (you have %2$s).', 'qweert-punk-zine' ),
                esc_html( $release['version'] ),
                esc_html( $this->current_version )
            ),
            $release_url,
            esc_html__( 'View release on GitHub', 'qweert-punk-zine' )
        );
    }

    /* ------------------------------------------------------------------ */
    /* AJAX: force check                                                    */
    /* ------------------------------------------------------------------ */

    /**
     * Handle the "Check for updates now" AJAX request from the admin page.
     */
    public function ajax_force_check(): void {
        check_ajax_referer( 'qweert_check_updates', 'nonce' );

        if ( ! current_user_can( 'update_themes' ) ) {
            wp_send_json_error( [ 'message' => __( 'You do not have permission to update themes.', 'qweert-punk-zine' ) ] );
        }

        $this->clear_cache();
        $release = $this->get_remote_release( true );

        if ( ! $release ) {
            wp_send_json_error( [ 'message' => __( 'Could not reach GitHub. Please try again later.', 'qweert-punk-zine' ) ] );
        }

        $is_update_available = version_compare( $release['version'], $this->current_version, '>' );

        wp_send_json_success( [
            'current_version'      => $this->current_version,
            'latest_version'       => $release['version'],
            'is_update_available'  => $is_update_available,
            'release_url'          => $release['html_url'] ?? '',
            'published_at'         => $release['published_at'] ?? '',
            'message'              => $is_update_available
                ? sprintf(
                    /* translators: 1: new version */
                    __( 'Update available: v%s', 'qweert-punk-zine' ),
                    $release['version']
                )
                : __( 'You are running the latest version.', 'qweert-punk-zine' ),
        ] );
    }

    /* ------------------------------------------------------------------ */
    /* Helpers                                                              */
    /* ------------------------------------------------------------------ */

    /**
     * Return the current installed version.
     * @return string
     */
    public function get_current_version(): string {
        return $this->current_version;
    }

    /**
     * Return the latest remote version (from cache or API).
     * @return string|false
     */
    public function get_latest_version(): string|false {
        $release = $this->get_remote_release();
        return $release ? $release['version'] : false;
    }
}

endif; // class_exists
