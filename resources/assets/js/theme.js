
window.selectTheme = function (theme) {
    let link = theme
        ? `http://127.0.0.1:8000/front_web/scss/bootstrap-${theme}.css` : 'http://127.0.0.1:8000/front_web/scss/bootstrap.css'
    document.querySelector('#theme').setAttribute('href', link)
    console.log(link)
}

window.initColorsSidePanel = function () {
    $('.section-sidepanel-handle').on('click', function() {
        let spWidth = $('.section-sidepanel').width() + 2;
        let spMarginLeft = parseInt($('.section-sidepanel').css('margin-left'),10);
        let w = (spMarginLeft >= 0 ) ? spWidth * - 1 : 0;
        let cw = (w < 0) ? -w : spWidth-22;
        console.log(w)
        $('.section-sidepanel').animate({marginLeft:w});
        $('.section-sidepanel-handle').animate({},  function() {});
    });

    $('.section-sidepanel-content-item').on('click', function() {
        let default_color = $('#default-color-theme').val();
        let current_color = $('#current-color-theme').val();
        let selected = $(this).data('ct');
        selectTheme(selected)
        $.get({
            url: '/theme',
            data: {
                theme: selected
            }
        })
        /*$('link[href="'+self.cssLink(current_color)+'"]').attr('href', self.cssLink(selected));
        application.load('/set-color-theme/'+selected, '', function (result) {});
        $('#current-color-theme').val(selected);
        $('.section-dark-mode-switch').find('input[type=checkbox]').prop('checked', false);*/
    })
}
