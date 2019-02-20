/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
(function ($) {
    function stickyBox() {
        var elm = $('.stickyFltr');
        if (!elm.length) {
            return;
        }
        var stickyTop = elm.offset().top;
        $(window).scroll(function () {
            var windowTop = $(window).scrollTop();
            if (stickyTop < windowTop) {
                $('.stickyFltr').addClass('fixed');
            } else {
                $('.stickyFltr').removeClass('fixed');
            }
        });
    }
    $(document).ready(function () {
           $('.owl-carousel').owlCarousel({
            loop: false,
            margin: 0,
            nav: true,
            autoWidth: true,
            mouseDrag:false,
            touchDrag:false,
            pullDrag:false
                    /*responsive:{
                     0:{
                     items:1
                     },
                     600:{
                     items:3
                     },
                     1000:{
                     items:5
                     }
                     } */
            });
        
        $(".filters-bar").keypress(function (e) {
            if ((e.keyCode == 13)) {
                e.preventDefault();
                $(this).submit();
            }
        });
        if ($(window).width() > 1200) {
            stickyBox();
        }

    });
})(jQuery);
