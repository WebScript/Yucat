$(function() {
    $(':input').keyup(function(trg) {
        var name = trg.target.name;
        var input = $(this);
        
        $.get('Auth/Login/check', name + '=' + $(this).val(), function(msg) {
            var response = $.parseJSON(msg);
            
            if(response.status == 'error'){
                input.removeClass('input-ok');
                input.addClass('input-error');
                
                if(response.message) {
                    //alert(response.message);
                }
            } else if(response.status == 'ok') {
                input.removeClass('input-error');
                input.addClass('input-ok');
            }
        });
    });
});