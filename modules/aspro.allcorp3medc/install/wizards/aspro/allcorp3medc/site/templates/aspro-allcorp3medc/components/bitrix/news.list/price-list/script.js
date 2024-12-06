$(document).on('click', '.price-items .accordion-head .price-head [data-event=jqm]', function(e){
    if ($(this).closest('.item-accordion-wrapper').hasClass('opened')) {
        $(this).closest('.accordion-head').addClass('accordion-open').removeClass('accordion-close');
    }
    else {
        $(this).closest('.accordion-head').addClass('accordion-close').removeClass('accordion-open');
    }
});

$(document).on('click', '.price-items .accordion-head .price-head .price-title a', function (e) {
    e.stopPropagation();

    const href = e.target.href;
    if (href) {
        window.location = href;
    }

    if ($(this).closest('.item-accordion-wrapper').hasClass('opened')) {
        $(this).closest('.accordion-head').addClass('accordion-open').removeClass('accordion-close');
    }
    else {
        $(this).closest('.accordion-head').addClass('accordion-close').removeClass('accordion-open');
    }
});