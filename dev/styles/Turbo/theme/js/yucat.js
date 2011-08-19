$(function() { 
    $('#loading').hide();
    

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
        $.post(this.action + 'Send', $(this.elements).serialize(), function(msg) {
            $.each($.parseJSON(msg), function(id, v) {
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
        var ths = $(this);
        
        if(trg.target.type !== 'submit') {
            var action = $(this).closest("form").attr('action');
            
            if(action) {
                $.post(action + 'Check', name + '=' + $(this).val(), function(msg) {
                    if($.parseJSON(msg).alert) {
                        $('#dialog').html($.parseJSON(msg).alert);
                        $('#dialog').dialog('open');
                    } else changeStats(ths, $($.parseJSON(msg)).attr(name));
                });
            }
        }
    });
    
    
    function changeStats(input, object) {
        if(object.status == 'error') {
            input.removeClass('input-ok');
            input.addClass('input-error');

            if(object.message) {
                input.closest("li").find('#AJAXMessage').html(object.message);
            }
        } else if(object.status == 'ok') {
            input.removeClass('input-error');
            input.addClass('input-ok');
            input.closest("li").find('#AJAXMessage').html('');
        } else {
        }
    }
});


    function changePage(page) {
        $('#loading').show();
        $.post(page, function(msg) {console.log(msg);
            if(typeof(msg) == 'JSON') {
                //console.log($.parseJSON(msg));
               // window.location = $.parseJSON(msg).redirect;
            } else {
                $('div.ajaxContent').html(msg);
                loadComponents();
           }
            $('#loading').hide();
        });
    }
    
    
    
    
    function refreshHeadMenu() {
      /*  $('#tabs1').tabify();
        $('#tabs2').tabify();
        $('#tabs3').tabify();
        $('#t-twitter-status').limit('140','#t-twitter-limit');*/
    }
    
    function loadComponents() {
        $('#tabs-news').tabify();
        $('.elastic').elastic();
        $('select').uniform();
        $('input:checkbox').uniform();
        $('input:radio').uniform();
        $('input:file').uniform();
        $('.datepicker').datepicker();


        //$('#w-tabs-settings').tabify();
        //$('#w-tabs-pm').tabify();
        //$('#w-tabs-twitter').tabify();
        //$('#c-tabs').tabify();
        //$('#main-tabs').tabify();
        $('.tiptip-top').tipTip({maxWidth:"auto",edgeOffset:1,delay:100,fadeIn:200,fadeOut:200,defaultPosition:"top"});
        $('.tiptip-right').tipTip({maxWidth:"auto",edgeOffset:1,delay:100,fadeIn:200,fadeOut:200,defaultPosition:"right"});
        $('.tiptip-bottom').tipTip({maxWidth:"auto",edgeOffset:1,delay:100,fadeIn:200,fadeOut:200,defaultPosition:"bottom"});
        $('.tiptip-left').tipTip({maxWidth:"auto",edgeOffset:1,delay:100,fadeIn:200,fadeOut:200,defaultPosition:"left"});

    }