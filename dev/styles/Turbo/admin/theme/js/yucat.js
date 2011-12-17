$(function() { 
    $('#loading').hide();
    
    var last;

    $('#dialog').dialog({
            autoOpen: false,
            width: 400,
            buttons: {
                   /* "LOL??": function() {
                            $(this).dialog("close"); 
                    }, */
                    "Close": function() { 
                            $(this).dialog("close"); 
                    } 
            }
    });
    
    
    $('form').live('submit', function() {
        $.post(this.action + 'Send', $(this.elements).serialize(), function(msg) {
            $.each($.parseJSON(msg), function(id, v) { 
                if(id == 'redirect') {
                    window.location = v;
                } else if(id == 'dialogName') { 
                    $('div[role=dialog] #ui-dialog-title-dialog').html(v);
                } else if(id == 'dialogValue') {
                    $('#dialog').html(v);
                    $('#dialog').dialog('open');
                } else {
                    changeStats($(':input[name=' + id + ']'), v);
                }
            });
        });
        return false;
    });
    
    
    $(':input').live('keyup click', function(trg) {
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
});


    function changePage(page) {
        $('#loading').show();
        last = page;
        var resp = $.getJSON(page, function(response) {
            window.location = response.redirect;
            $('#loading').hide();
        });
        
        resp.error(function(msg) { 
            $('div.ajaxContent').html(msg.responseText);
            loadComponents();
            $('#loading').hide();
        });    
    }
    
    
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
        } else if(object.reload) {
            changePage(last);
        }
    }
    
    
    
    function sendGetParams(form) {
        $('#loading').show();
        $.get(form[0].action, $(form[0].elements).serialize(), function(msg){
            $('div.ajaxContent').html(msg);
            loadComponents();
            $('#loading').hide();
        });
        return false;
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