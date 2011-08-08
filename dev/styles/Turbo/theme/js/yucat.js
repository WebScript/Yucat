$(function() { 
    $('#loading').hide();
    $("#loading").ajaxStart(function(){
        $(this).show();
    });
    $("#loading").ajaxStop(function(){
        $(this).hide();
    });  
    
    
    $('#dialog').dialog({
            autoOpen: false,
            width: 600,
            buttons: {
                    "LOL??": function() { 
                            $(this).dialog("close"); 
                    }, 
                    "Sorry, I'm stupid...": function() { 
                            $(this).dialog("close"); 
                    } 
            }
    });
    
    $('form').submit(function(val) {
        var elements = $(this.elements).serialize();

        $.get(this.action + '/send', elements, function(msg) {
            var response = $.parseJSON(msg);

            $.each(response, function(id, v) {
                if(id == 'redirect') {
                    window.location = v;
                } else if(id == 'alert') {
                    $('#dialog').html(v);
                    $('#dialog').dialog('open');
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
            var action = $(this).closest("form").attr('action');
            
            if(action) {
                $.get(action + '/check', name + '=' + $(this).val(), function(msg) {
                    changeStats(input, $($.parseJSON(msg)).attr(name));
                });
            }
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

    function changePage(page) {
        $.get(page, function(msg) {
            $('div.ajaxContent').html(msg);
            $('div.ajaxContent').append('<script src="/styles/Turbo/theme/js/page.js"></script>');
        });
    }