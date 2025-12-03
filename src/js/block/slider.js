$(() => {
    $(".connection-slider").slick({
        dots: false,
        slidesToShow: 4,
        slidesToScroll: 1,
        prevArrow: $('.prev-index'),
        nextArrow: $('.next-index'),
        draggable: false,
        variableWidth: true,
        responsive: [

            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    draggable: true,
                }
            },

            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    draggable: true,
                }
            }
        ],

    });


    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav'
    });

    $('.slider-nav').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: false,
        focusOnSelect: true,
        arrows: true,
        variableWidth: true,
        allowTouchMove: true,
        swipe: true,
        responsive: [

            {
                breakpoint: 1415,
                settings: {
                    slidesToShow: 6,
                    slidesToScroll: 1,
                    draggable: true,
                }
            },

            {
                breakpoint: 1180,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    draggable: true,
                }
            },

            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    draggable: true,
                }
            },

            {
                breakpoint: 462,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    draggable: true,
                }
            },

            {
                breakpoint: 368,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    draggable: true,
                }
            }
        ],

    });


    $(".document-slider").slick({
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: $('.prev-index2'),
        nextArrow: $('.next-index2'),
        draggable: false
    });

});