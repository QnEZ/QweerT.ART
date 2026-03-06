<?php
/**
 * QweerT Punk Zine — searchform.php
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div style="display:flex;gap:0;border:3px solid #000;box-shadow:4px 4px 0 #000;">
        <input type="search"
               class="search-field"
               placeholder="<?php esc_attr_e( 'SEARCH...', 'qweert-punk-zine' ); ?>"
               value="<?php echo get_search_query(); ?>"
               name="s"
               style="background:var(--color-bg-card);border:none;color:var(--color-text);font-family:'Space Mono',monospace;font-size:0.9rem;padding:0.5rem 0.75rem;flex:1;outline:none;" />
        <button type="submit"
                class="search-submit btn-punk"
                style="border:none;border-left:3px solid #000;border-radius:0;padding:0.5rem 1rem;">
            <?php esc_html_e( 'GO', 'qweert-punk-zine' ); ?>
        </button>
    </div>
</form>
