$(function (){
    $('.test-data').find('div:first').show();
    $('.pagination a').on('click',function () {
        console.log($(this).attr('href'));
        return false;
    });
});

