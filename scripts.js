$(function () {
    $('.test-data').find('div:first').show();
    $('.pagination a').on('click', function () {
        if ($(this).attr('class') == 'nav-active') return false;
        let link = $(this).attr('href');
        let preActive = $('.pagination > a.nav-active').attr('href');
        $('.pagination > a.nav-active').removeClass('nav-active');
        $(this).addClass('nav-active');
        $(preActive).fadeOut(100, function () {
            $(link).fadeIn();
        });
        return false;
    });

    $('#btn').click(function(){
        var test = +$('#test-id').text();
        var res = {'test':test};

        $('.question').each(function(){
            var id = $(this).data('id');
            res[id] = $('input[name=question-' + id + ']:checked').val();
        });

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: res,
            success: function(html){
                console.log(html);
            },
            error: function(){
                alert('Error!');
            }
        });
    });

});

