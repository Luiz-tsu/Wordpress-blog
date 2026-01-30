/**
 * File navigation.js
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus, including keyboard accessibility.
 */
(function () {
    const siteNavigation = document.getElementById('site-navigation');

    if (!siteNavigation) return;

    const button = siteNavigation.getElementsByTagName('button')[0];
    if (typeof button === 'undefined') return;

    const menu = siteNavigation.getElementsByTagName('ul')[0];
    if (typeof menu === 'undefined') {
        button.style.display = 'none';
        return;
    }

    menu.setAttribute('aria-expanded', 'false');
    if (!menu.classList.contains('nav-menu')) {
        menu.classList.add('nav-menu');
    }

    /** -------------------------------------------------------
     * 🔒 Scroll lock helpers
     * ------------------------------------------------------- */
    function bsd_lockScroll(lock) {
        if (lock) {
            document.body.style.overflow = 'hidden';
            document.body.style.touchAction = 'none';
        } else {
            document.body.style.overflow = '';
            document.body.style.touchAction = '';
        }
    }

    /** -------------------------------------------------------
     * 🧭 Menu toggle (mobile)
     * ------------------------------------------------------- */
    button.addEventListener('click', function () {
        const isOpen = siteNavigation.classList.toggle('toggled');
        button.setAttribute('aria-expanded', isOpen);

        // Lock scroll when open
        bsd_lockScroll(isOpen);

        // Focus first focusable link when menu opens
        if (isOpen) {
            const firstFocusable = menu.querySelector('a, button, input, [tabindex]:not([tabindex="-1"])');
            if (firstFocusable) firstFocusable.focus();
        }
    });

    /** -------------------------------------------------------
     * ⌨️ Focus trap (keep focus inside menu when open)
     * ------------------------------------------------------- */
    document.addEventListener('keydown', function (e) {
        if (!siteNavigation.classList.contains('toggled')) return;
        if (e.key !== 'Tab') return;

        const focusableEls = siteNavigation.querySelectorAll(
            'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        const focusable = Array.prototype.slice.call(focusableEls);
        const firstEl = focusable[0];
        const lastEl = focusable[focusable.length - 1];

        if (e.shiftKey) {
            if (document.activeElement === firstEl) {
                e.preventDefault();
                lastEl.focus();
            }
        } else {
            if (document.activeElement === lastEl) {
                e.preventDefault();
                firstEl.focus();
            }
        }
    });

    /** -------------------------------------------------------
     * 📱 Submenu toggle (works at all nested levels)
     * ------------------------------------------------------- */
    function bsd_isMobileMenuContext() {
        return siteNavigation.classList.contains('toggled') || window.matchMedia('(max-width: 1024px)').matches;
    }

    const submenuIcons = menu.querySelectorAll('a > img.submenu-icon');
    submenuIcons.forEach(icon => {
        icon.addEventListener('click', function (e) {
            if (!bsd_isMobileMenuContext()) return;
            e.preventDefault();
            e.stopPropagation();

            const link = this.closest('a');
            const parentLi = link.closest('li.menu-item-has-children');
            if (!parentLi) return;

            const submenu = link.nextElementSibling && link.nextElementSibling.classList.contains('sub-menu')
                ? link.nextElementSibling
                : parentLi.querySelector('ul.sub-menu');

            if (!submenu) return;

            const isOpening = !submenu.classList.contains('toggled');

            // Close siblings at same level
            const siblings = parentLi.parentElement ? parentLi.parentElement.children : [];
            Array.from(siblings).forEach(sib => {
                if (sib !== parentLi && sib.classList.contains('menu-item-has-children')) {
                    const sibSub = sib.querySelector(':scope > ul.sub-menu.toggled');
                    const sibLink = sib.querySelector(':scope > a[aria-expanded]');
                    if (sibSub) sibSub.classList.remove('toggled');
                    sib.classList.remove('toggled');
                    if (sibLink) sibLink.setAttribute('aria-expanded', 'false');
                }
            });

            // Toggle current submenu
            submenu.classList.toggle('toggled', isOpening);
            parentLi.classList.toggle('toggled', isOpening);
            link.setAttribute('aria-expanded', isOpening ? 'true' : 'false');

            // Close nested submenus when collapsing
            if (!isOpening) {
                const nestedSubs = submenu.querySelectorAll('.sub-menu.toggled');
                nestedSubs.forEach(sub => {
                    sub.classList.remove('toggled');
                    const subLink = sub.previousElementSibling;
                    if (subLink && subLink.tagName === 'A') {
                        subLink.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    });

    /** -------------------------------------------------------
     * 🧭 Keyboard hover support for desktop
     * ------------------------------------------------------- */
    const links = menu.getElementsByTagName('a');
    for (const link of links) {
        link.addEventListener('focus', bsd_toggleFocus, true);
        link.addEventListener('blur', bsd_toggleFocus, true);
    }

    function bsd_toggleFocus() {
        let self = this;
        while (!self.classList.contains('nav-menu')) {
            if (self.tagName.toLowerCase() === 'li') {
                self.classList.toggle('focus');
            }
            self = self.parentNode;
        }
    }
})();
