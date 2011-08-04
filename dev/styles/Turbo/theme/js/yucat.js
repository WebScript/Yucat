$(function() {
    
    $('form').submit(function(val) { 
        var elements = $(this.elements).serialize();
        console.log(this.action);
        $.get(this.action, elements, function(msg) {
            var response = $.parseJSON(msg);
            
            $.each(response, function(id, v) {
                if(id == 'redirect') {
                    console.log(v);
                    //window.location = v;
                } else if(id == 'alert') {
                    alert(v);
                }
                changeStats($(':input[name=' + id + ']'), v);
            });
        });
       return false;
    });
    
    
    $(':input').bind('keyup click', function(trg) {
        var name = trg.target.name;
        var input = $(this);
        
        if(trg.target.type !== 'submit') {
            $.get(this.action, name + '=' + $(this).val(), function(msg) {
                changeStats(input, $($.parseJSON(msg)).attr(name));
            });
        }
    });
    
    
    function changeStats(input, object) {
        if(object.status == 'error') {
            input.removeClass('input-ok');
            input.addClass('input-error');

            if(object.message) {
                //alert(response.message);
            }
        } else if(object.status == 'ok') {
            input.removeClass('input-error');
            input.addClass('input-ok');
        }
    }
    
    
});