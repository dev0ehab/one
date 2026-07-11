$(window).on('load', function () {
    $('.loader').fadeOut(1000);
    $('body').addClass('o-auto');
});

$(document).ready(function () {

    $(".close-open-nav").on("click", function (e) {
        e.stopPropagation();
        $(this).toggleClass("active");
        if ($(this).hasClass("active")) {
            $(".nav_bar").addClass("active");
        } else {
            $(".nav_bar").removeClass("active");
        }
    });

    $("body").on("click", function () {
        $(".close-open-nav.active").click();
    });

    $(".nav_bar, .intro-header__actions").on("click", function (e) {
        e.stopPropagation();
    });

    $(".nav_bar a").on("click", function () {
        $(".close-open-nav").removeClass("active");
        $(".nav_bar").removeClass("active");
    });

    // Reliable language dropdown toggle (fallback for Bootstrap conflicts)
    $(".intro-lang .dropdown-toggle").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $parent = $(this).closest(".intro-lang");
        var $menu = $parent.find(".dropdown-menu");
        $(".intro-lang .dropdown-menu").not($menu).removeClass("show").hide();
        $menu.toggleClass("show");
        if ($menu.hasClass("show")) {
            $menu.show();
        } else {
            $menu.hide();
        }
    });

    $(".intro-lang .dropdown-menu").on("click", function (e) {
        e.stopPropagation();
    });

    $(document).on("click", function () {
        $(".intro-lang .dropdown-menu").removeClass("show").hide();
    });

    if ($('.owl-index').length) {
        $('.owl-index').owlCarousel({
            loop: true,
            margin: 0,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
            responsive: {
                0: { items: 1 },
                600: { items: 1 },
                1000: { items: 1 }
            }
        });

        var $nav = $(".owl-index .owl-nav");
        var $dots = $(".owl-index .owl-dots");
        if ($nav.length && $dots.length) {
            $nav.width($dots.outerWidth(true) + ($nav.children("div").outerWidth(true) * 2));
        }
    }

    if ($('.owl-brands').length) {
        $('.owl-brands').owlCarousel({
            loop: true,
            margin: 24,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 2500,
            responsive: {
                0: { items: 2 },
                600: { items: 4 },
                1000: { items: 6 }
            }
        });
    }

    function setBodyTopPadding() {
        var $header = $("header.intro-header");
        if (!$header.length) {
            $header = $("header");
        }
        $(".body-content").css({ marginTop: $header.outerHeight(true) });
    }

    setBodyTopPadding();

    $(window).on("resize", setBodyTopPadding);

    $(window).on("scroll", function () {
        var scrollTop = $(window).scrollTop();
        $(".body-content > div[id]").each(function () {
            var $sec = $(this);
            var id = $sec.attr("id");
            if (!id) {
                return;
            }
            var top = $sec.offset().top;
            if (scrollTop >= top - 80) {
                $(".nav_bar a[data-scroll='" + id + "']").addClass("active").parent().siblings().children().removeClass("active");
            }
        });
    });

    // 3D tilt effect for main landing blocks
    var tiltSelector = ".intro-service-card, .intro-how__item, .accordion .card, .intro-contact__panel, .intro-hero-slide__media";
    $(tiltSelector).each(function () {
        var $card = $(this);
        $card.on("mousemove", function (e) {
            if (window.matchMedia("(max-width: 991px)").matches) {
                return;
            }
            var rect = this.getBoundingClientRect();
            var x = e.clientX - rect.left;
            var y = e.clientY - rect.top;
            var centerX = rect.width / 2;
            var centerY = rect.height / 2;
            var rotateY = ((x - centerX) / centerX) * 6;
            var rotateX = -((y - centerY) / centerY) * 6;
            $card.css("transform", "perspective(1000px) rotateX(" + rotateX + "deg) rotateY(" + rotateY + "deg) translateZ(8px)");
            document.documentElement.style.setProperty("--s3d-card-rx", (rotateX * 0.45).toFixed(2) + "deg");
            document.documentElement.style.setProperty("--s3d-card-ry", (rotateY * 0.45).toFixed(2) + "deg");
            document.documentElement.style.setProperty("--s3d-card-z", "14px");
        });

        $card.on("mouseleave", function () {
            $card.css("transform", "");
            document.documentElement.style.setProperty("--s3d-card-rx", "0deg");
            document.documentElement.style.setProperty("--s3d-card-ry", "0deg");
            document.documentElement.style.setProperty("--s3d-card-z", "0px");
        });
    });

    // Professional 3D scroll depth (desktop only)
    var isDesktop3d = function () {
        return !window.matchMedia("(max-width: 991px)").matches;
    };

    var clamp = function (value, min, max) {
        return Math.max(min, Math.min(max, value));
    };

    var getProgress = function (el, viewportH) {
        var rect = el.getBoundingClientRect();
        var start = viewportH;
        var end = -rect.height;
        var raw = (start - rect.top) / (start - end);
        return clamp(raw, 0, 1);
    };

    var sectionsMap = [
        { selector: ".intro-hero", key: "hero", y: 40, z: 72, rx: 7 },
        { selector: ".intro-about", key: "about", y: 34, z: 58, rx: 5.6 },
        { selector: ".bacg_section", key: "services", y: 30, z: 52, rx: 5.2 },
        { selector: ".bacg_section2", key: "faq", y: 28, z: 46, rx: 4.6 },
        { selector: ".intro-partners", key: "partners", y: 24, z: 40, rx: 4.1 },
        { selector: ".intro-contact", key: "contact", y: 22, z: 36, rx: 3.8 }
    ];

    sectionsMap.forEach(function (cfg) {
        var node = document.querySelector(cfg.selector);
        if (node) {
            node.classList.add("s3d-section");
        }
    });

    var currentVars = {};

    var smoothSetVar = function (name, target) {
        var prev = currentVars[name];
        if (typeof prev === "undefined") {
            prev = target;
        }
        var next = prev + (target - prev) * 0.16;
        currentVars[name] = next;
        return next;
    };

    var applyDepthVars = function () {
        if (!isDesktop3d()) {
            document.body.classList.remove("scroll-3d-active");
            document.body.classList.remove("scroll-3d-mounted");
            return;
        }

        document.body.classList.add("scroll-3d-active");
        document.body.classList.add("scroll-3d-mounted");

        var viewportH = window.innerHeight || document.documentElement.clientHeight;

        sectionsMap.forEach(function (cfg) {
            var el = document.querySelector(cfg.selector);
            if (!el) {
                return;
            }
            var p = getProgress(el, viewportH); // 0..1
            var centered = (p - 0.5) * 2; // -1..1
            var y = centered * cfg.y;
            var z = (1 - Math.abs(centered)) * cfg.z;
            var rx = -centered * cfg.rx;

            var vy = smoothSetVar("s3d-" + cfg.key + "-y", y);
            var vz = smoothSetVar("s3d-" + cfg.key + "-z", z);
            var vrx = smoothSetVar("s3d-" + cfg.key + "-rx", rx);

            document.documentElement.style.setProperty("--s3d-" + cfg.key + "-y", vy.toFixed(2) + "px");
            document.documentElement.style.setProperty("--s3d-" + cfg.key + "-z", vz.toFixed(2) + "px");
            document.documentElement.style.setProperty("--s3d-" + cfg.key + "-rx", vrx.toFixed(2) + "deg");

            if (Math.abs(centered) < 0.9) {
                el.classList.add("is-visible");
                document.documentElement.style.setProperty("--s3d-sweep-x", "120%");
            } else {
                el.classList.remove("is-visible");
            }
        });

        var scrollRatio = clamp(window.scrollY / (document.body.scrollHeight - viewportH || 1), 0, 1);
        var lx = 18 + (scrollRatio * 64);
        var ly = 16 + Math.sin(scrollRatio * Math.PI * 2) * 8;
        document.documentElement.style.setProperty("--s3d-light-x", lx.toFixed(2) + "%");
        document.documentElement.style.setProperty("--s3d-light-y", ly.toFixed(2) + "%");
    };

    var rafPending = false;
    var onScroll3d = function () {
        if (rafPending) {
            return;
        }
        rafPending = true;
        window.requestAnimationFrame(function () {
            applyDepthVars();
            rafPending = false;
        });
    };

    applyDepthVars();
    $(window).on("scroll resize", onScroll3d);

    // Hero cinematic mouse parallax (desktop only)
    $(".intro-hero").on("mousemove", function (e) {
        if (!isDesktop3d()) {
            return;
        }
        var rect = this.getBoundingClientRect();
        var mx = ((e.clientX - rect.left) / rect.width - 0.5) * 2; // -1..1
        var my = ((e.clientY - rect.top) / rect.height - 0.5) * 2; // -1..1
        var lx = ((e.clientX - rect.left) / rect.width) * 100;
        var ly = ((e.clientY - rect.top) / rect.height) * 100;
        document.documentElement.style.setProperty("--s3d-hero-text-y", (-my * 10).toFixed(2) + "px");
        document.documentElement.style.setProperty("--s3d-hero-text-z", (18 + Math.abs(mx) * 8).toFixed(2) + "px");
        document.documentElement.style.setProperty("--s3d-hero-media-y", (my * 12).toFixed(2) + "px");
        document.documentElement.style.setProperty("--s3d-hero-media-z", (24 + Math.abs(my) * 12).toFixed(2) + "px");
        document.documentElement.style.setProperty("--s3d-hero-media-ry", (mx * 6).toFixed(2) + "deg");
        document.documentElement.style.setProperty("--s3d-light-x", lx.toFixed(2) + "%");
        document.documentElement.style.setProperty("--s3d-light-y", ly.toFixed(2) + "%");
    }).on("mouseleave", function () {
        document.documentElement.style.setProperty("--s3d-hero-text-y", "0px");
        document.documentElement.style.setProperty("--s3d-hero-text-z", "0px");
        document.documentElement.style.setProperty("--s3d-hero-media-y", "0px");
        document.documentElement.style.setProperty("--s3d-hero-media-z", "0px");
        document.documentElement.style.setProperty("--s3d-hero-media-ry", "0deg");
        document.documentElement.style.setProperty("--s3d-light-x", "50%");
        document.documentElement.style.setProperty("--s3d-light-y", "20%");
    });
});
