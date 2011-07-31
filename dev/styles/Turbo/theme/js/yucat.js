$(function() {
    $(':input').keyup(function(trg) {
        var name = trg.target.name;
        var input = $(this);
        
        $.get('Auth/Login/check', name + '=' + $(this).val(), function(msg) {
            if(msg == 'error'){
                input.removeClass('input-ok');
                input.addClass('input-error');
            } else if(msg == 'ok') {
                input.removeClass('input-error');
                input.addClass('input-ok');
            }
        });
    });
});