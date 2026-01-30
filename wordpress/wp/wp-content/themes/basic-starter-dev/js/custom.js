/**
 * Custom JavaScript for Basic Starter Dev Theme
 */
document.addEventListener("DOMContentLoaded", function () {

    // Back to Top Button Functionality
    const backToTopBtn = document.querySelector('.back-to-top');    

    if (backToTopBtn) {
        // Show/hide button based on scroll position
        function bsd_toggleBackToTop() {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        }

        // Smooth scroll to top when button is clicked
        backToTopBtn.addEventListener('click', function(e) {
            e.preventDefault();           

            // Smooth scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });           

            // Focus management for accessibility
            setTimeout(function() {
                document.querySelector('#page').focus();
            }, 1000);
        });

        // Listen for scroll events
        window.addEventListener('scroll', bsd_toggleBackToTop);        

        // Initial check on page load
        bsd_toggleBackToTop();
    }

    // Sticky Header (only if sticky is enabled)
    const siteHeader = document.getElementById('masthead');

    if (siteHeader && siteHeader.classList.contains('sticky-enabled')) {
        const toggleStickyHeader = () => {
            if (window.pageYOffset > 10) {
                siteHeader.classList.add('is-sticky');
            } else {
                siteHeader.classList.remove('is-sticky');
            }
        };

        toggleStickyHeader();
        window.addEventListener('scroll', toggleStickyHeader);
    }

});
