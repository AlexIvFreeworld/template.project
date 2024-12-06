$(document).ready(function(){
    var last_specialization = $('.staff__filter-select .dropdown-select .dropdown-select__list-link--current.empty').length ? '' : $('.staff__filter-select .dropdown-select .dropdown-select__list-item a').closest('.dropdown-select').find('.dropdown-select__title span').text();
    $('.staff__filter-select .dropdown-select .dropdown-select__list-item a').on('click', function (e) {
        e.preventDefault();

        var $select = $(this).closest('.dropdown-select');

        // set current item
        $select.find('.dropdown-select__list-link--current').removeClass('dropdown-select__list-link--current');
        $(this).addClass('dropdown-select__list-link--current');

        // change title
        var $title = $select.find('.dropdown-select__title');
        var specialization = $(this).text().trim();
        $title.find('span').text(specialization);

        // empty filter
        if ($(this).hasClass('empty')){
            specialization = '';
        }

        // close
        $title.trigger('click');

        if (specialization != last_specialization) {
            last_specialization = specialization;

            // url
            var href = $(this).attr('href');

            $.ajax({
                url: href,
                type: 'POST',
            }).done(function (html) {
                var ob = BX.processHTML(html);
                $('.staff__ajax_items')[0].innerHTML = ob.HTML;
                BX.ajax.processScripts(ob.SCRIPT);
            });
        }
    });
});
