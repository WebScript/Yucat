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

        $.post(this.action + '/send', elements, function(msg) {
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
                $.post(action + 'check', name + '=' + $(this).val(), function(msg) {
                    if($.parseJSON(msg).alert) {
                        $('#dialog').html($.parseJSON(msg).alert);
                        $('#dialog').dialog('open');
                    } else changeStats(input, $($.parseJSON(msg)).attr(name));
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
        $.post(page, function(msg) {
            $('div.ajaxContent').html(msg);
            loadComponents();
            //$('div.ajaxContent').prepend($('div.jsPrepend'));
            //$('div.ajaxContent').append('<script src="/styles/Turbo/theme/js/yucat.js"></script>');
            //$('div.ajaxContent').append('<script src="/styles/turbo/theme/js/page.js"></script>');
            
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