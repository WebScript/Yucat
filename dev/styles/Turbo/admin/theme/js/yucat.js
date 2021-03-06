$(function() { 
    $('#loading').hide(); 
    
    $('form').live('submit', function() {
        $('#loading').show();
        $.post(this.action + '/send', $(this.elements).serialize(), function(msg) {
            manageJSON($.parseJSON(msg));
            $('#loading').hide();
        });
        return false;
    });
    
    
    $(':input').live('keyup click', function(trg) {
        var name = trg.currentTarget.name;

        if(trg.target.type !== 'submit') {
            var action = $(this).closest("form").attr('action');
            
            if(action) {
                $.post(action + '/check', name + '=' + $(this).val(), function(msg) {
                    manageJSON($.parseJSON(msg));
                });
            }
        }
    });
});


    function changePage(page) {
        $('#loading').show();
        $.getJSON(page, function(response) {
            manageJSON(response);
            $('#loading').hide();
        });   
    }
   
    
    function manageJSON(input) {
        var out = 1;
        $.each(input, function(id, val) {
            switch(id) {
                case 'redirectHead' :
                    window.location = val;
                    break;
                case 'redirect' :
                    changePage(val);
                    break;
                case 'dialogBase' :
                    apprise(val, {'animate':true});
                    break;
                case 'dialogError' :
                    $('#AjaxDialogError').fadeTo('fast', 0, function() {
                        $('#AjaxDialogError').html('<div class="msg-error"><h4>Error message</h4>' + val + '</div>');
                        $('#AjaxDialogError').fadeTo('slow', 1);
                    });
                    break;
                case 'dialogSuccess' :
                    $('#AjaxDialogError').fadeTo('fast', 0, function() {
                        $('#AjaxDialogError').html('<div class="msg-ok"><h4>Success message</h4>' + val + '</div>');
                        $('#AjaxDialogError').fadeTo('slow', 1);
                    });
                    break;
                case 'setContent' :
                    $('div.ajaxContent').html(val);
                    loadComponents();
                    break;
                case 'form' :
                    $.each(val, function(id2, object) {
                        var input = $(':input[name=' + id2 + ']');
                        
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
                        } else if(object.changeValue) {
                            input.html(object.changeValue);
                        }
                    });
                    break;
                default :  
                    out = 0;
                    break;
            }
        });
        
        if(window.afterLoad) {
            afterLoad();
        }
        return out;
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