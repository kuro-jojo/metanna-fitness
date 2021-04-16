/*!
 * Start Bootstrap - SB Admin 2 v4.1.3 (https://startbootstrap.com/theme/sb-admin-2)
 * Copyright 2013-2020 Start Bootstrap
 * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin-2/blob/master/LICENSE)
 */

export default ! function() {
    $(document).ready(function() {
        $(document).on("click", "#sidebarToggle, #sidebarToggleTop", function(e) {

            $("body").toggleClass("sidebar-toggled"), $(".sidebar").toggleClass("toggled"), $(".sidebar").hasClass("toggled") && $(".sidebar .collapse").collapse("hide")
        }), $(window).resize(function() {
            $(window).width() < 768 && $(".sidebar .collapse").collapse("hide"), $(window).width() < 480 && !$(".sidebar").hasClass("toggled") && ($("body").addClass("sidebar-toggled"), $(".sidebar").addClass("toggled"), $(".sidebar .collapse").collapse("hide"))
        }), $("body.fixed-nav .sidebar").on("mousewheel DOMMouseScroll wheel", function(e) {
            if (768 < $(window).width()) {
                var o = e.originalEvent,
                    l = o.wheelDelta || -o.detail;
                this.scrollTop += 30 * (l < 0 ? 1 : -1), e.preventDefault()
            }
        }), $(document).on("scroll", function() {
            100 < $(this).scrollTop() ? $(".scroll-to-top").fadeIn() : $(".scroll-to-top").fadeOut()
        }), $(document).on("click", "a.scroll-to-top", function(e) {
            var o = $(this);
            $("html, body").stop().animate({
                scrollTop: $(o.attr("href")).offset().top
            }, 1e3, "easeInOutExpo"), e.preventDefault()
        })
    });
}(jQuery);