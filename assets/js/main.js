/**
 * QweerT Punk Zine — main.js
 * Mobile menu, scroll animations, glitch effect, cart fragments.
 */
(function ($) {
    'use strict';

    /* ── Mobile Menu Toggle ─────────────────────────────── */
    var $toggle = $('#menu-toggle');
    var $nav    = $('#site-navigation');
    var $iconMenu  = $('#icon-menu');
    var $iconClose = $('#icon-close');

    $toggle.on('click', function () {
        var isOpen = $nav.hasClass('is-open');
        $nav.toggleClass('is-open');
        $toggle.attr('aria-expanded', !isOpen);
        $iconMenu.toggle(isOpen);
        $iconClose.toggle(!isOpen);
    });

    /* ── Smooth scroll for anchor links ─────────────────── */
    $('a[href^="#"]').on('click', function (e) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: target.offset().top - 80 }, 400);
        }
    });

    /* ── Sticker card entrance animation ────────────────── */
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = entry.target.dataset.rotate || 'none';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.sticker-card, .character-card, .event-card').forEach(function (el) {
            el.style.opacity = '0';
            el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            observer.observe(el);
        });
    }

    /* ── WooCommerce: Update cart count on fragment refresh ─ */
    $(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function () {
        var count = $('.cart-count').first().text();
        $('.cart-count').text(count);
    });

    /* ── Glitch effect on hero title ────────────────────── */
    var $heroTitles = $('.hero-title');
    if ($heroTitles.length) {
        setInterval(function () {
            if (Math.random() > 0.85) {
                $heroTitles.addClass('glitch-active');
                setTimeout(function () {
                    $heroTitles.removeClass('glitch-active');
                }, 150);
            }
        }, 3000);
    }

    /* ── Add to cart feedback ────────────────────────────── */
    $(document.body).on('added_to_cart', function () {
        var $btn = $('.single_add_to_cart_button');
        var original = $btn.text();
        $btn.text('ADDED! ✊').css('background', '#55cfff');
        setTimeout(function () {
            $btn.text(original).css('background', '');
        }, 1500);
    });

}(jQuery));
