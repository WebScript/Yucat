$('ul#widget-menu li a.w-link').click(function(){
    var widget=$(this).next();
    
    if($(this).hasClass('active')==true) {
        widget.hide();
        $(this).removeClass('active');
        if(!jQuery.browser.msie||(jQuery.browser.msie&&jQuery.browser.version==9.0)) $('#panel-outer').fadeTo('50',1)
    } else {
        $('ul#widget-menu li a.w-link').removeClass('active');
        $(this).addClass('active');
        $('.widget').hide();
        setTimeout(function() {
            widget.fadeIn(50)
        },50);
        if(!jQuery.browser.msie||(jQuery.browser.msie&&jQuery.browser.version==9.0)) $('#panel-outer').fadeTo('50',0.5)
    } 
    return false
});

function close_widgets() {
    $('ul#widget-menu li a.w-link').removeClass('active');
    $('.widget').hide();
    if(!jQuery.browser.msie||(jQuery.browser.msie&&jQuery.browser.version==9.0)) $('#panel-outer').fadeTo('50',1);
    return false
}

$(document).click(function(e) {
    close_widgets()
});

$('#widgets').click(function(e) {
    e.stopPropagation()
});

$('.widget').click(function(e) {
    e.stopPropagation()
});


$(function() {
    var element=$('.enable-close');
    element.css('cursor','pointer');
    element.click(function() {
        $(this).hide()
    })
});

/*
$(function() {
    $('.image-gallery li').mouseenter(function() {
        $(this).find('div').stop(true,true).fadeIn('500')
    });
    
    $('.image-gallery li').mouseleave(function() {
        $(this).find('div').stop(true,true).hide()
    });
    
    $('.file-gallery li').mouseenter(function() {
        $(this).find('div').stop(true,true).fadeIn('500')
    });
    
    $('.file-gallery li').mouseleave(function() {
        $(this).find('div').stop(true,true).hide()
    })
});*/
/*
function update_twitter() {
    var mt=$('#twitter-status-update');
    var tweet=$('textarea#t-twitter-status').val();
    if(tweet) {
        mt.fadeOut(100,function() {
            mt.html('<div class="msg-loading">Updating..</div>');
            mt.fadeIn(100,function() {
                mt.load("inc/twitter.fn.php",{tweet:""+tweet+""})
            })
        })
    }
    return false
}

function get_tweets() {
    var ts=$('#twitter-updates');
    var count=5;
    ts.fadeOut(100,function() {
        ts.html('<div class="msg-loading mar-none">Loading updates..</div>');
        ts.fadeIn(100,function(){
            ts.load("inc/twitter.fn.php",{get_tweets:count})
        })
    });
    return false
}
*/
$(function() {
    
    
    
    
    /*
    $('#t-btn').click(function() {
       // update_twitter()
    });
    
    $('#load-twitter-updates').click(function() {
        //get_tweets()
    });*/
    
    //var availableTags=["ActionScript","AppleScript","Asp","BASIC","C","C++","Clojure","COBOL","ColdFusion","Erlang","Fortran","Groovy","Haskell","Java","JavaScript","Lisp","Perl","PHP","Python","Ruby","Scala","Scheme"];
    //var availableUsernames=["John","Mike","Lisa","Emma","Chloe","turboadminer","turbomoder","admin","George"];
    
    //$('#search-keyword').autocomplete({source:availableTags});
    //$('#search-user').autocomplete({source:availableUsernames});
    //$('#searchbig-users').autocomplete({source:availableUsernames});



    
    /*
    var date=new Date();
    var d=date.getDate();
    var m=date.getMonth();
    var y=date.getFullYear();
    
    $('#fullcalendar').fullCalendar({
        editable:true,
        header:{
            left:'prev,next today',
            center:'title',
            right:'month,agendaWeek,agendaDay'
        },
        events:[{
                title:'All Day Event',
                start:new Date(y,m,1)
            },
            {
                title:'Long Event',
                start:new Date(y,m,d-5),
                end:new Date(y,m,d-2)
            },
            {
                id:999,
                title:'Repeating Event',
                start:new Date(y,m,d-3,16,0),
                allDay:false
            },
            {
                id:999,
                title:'Repeating Event',
                start:new Date(y,m,d+4,16,0),
                allDay:false
            },
            {
                title:'Meeting',
                start:new Date(y,m,d,10,30),
                allDay:false
            },
            {
                title:'Write article',
                start:new Date(y,m,d,12,0),
                end:new Date(y,m,d,14,0),
                allDay:false
            },
            {
                title:'Work on template',
                start:new Date(y,m,d+1,19,0),
                end:new Date(y,m,d+1,22,30),
                allDay:false
            },
            {
                title:'Click for Google',
                start:new Date(y,m,28),
                end:new Date(y,m,29),url:'http://google.com/'
            }]
    });
    
    $('.wysiwyg').wysiwyg({
        controls:
            {
            strikeThrough:{visible:true},
            underline:{visible:true},
            separator00:{visible:true},
            justifyLeft:{visible:true},
            justifyCenter:{visible:true},
            justifyRight:{visible:true},
            justifyFull:{visible:true},
            separator01:{visible:true},
            indent:{visible:true},
            outdent:{visible:true},
            separator02:{visible:true},
            subscript:{visible:true},
            superscript:{visible:true},
            separator03:{visible:true},
            undo:{visible:true},
            redo:{visible:true},
            separator04:{visible:true},
            insertOrderedList:{visible:true},
            insertUnorderedList:{visible:true},
            insertHorizontalRule:{visible:true},
            h4mozilla:{
                visible:true&&$.browser.mozilla,
                className:'h4',
                command:'heading',
                arguments:['h4'],
                tags:['h4'],
                tooltip:"Header 4"
            },
            h5mozilla:{
                visible:true&&$.browser.mozilla,
                className:'h5',
                command:'heading',
                arguments:['h5'],
                tags:['h5'],
                tooltip:"Header 5"
            },
            h6mozilla:{
                visible:true&&$.browser.mozilla,
                className:'h6',
                command:'heading',
                arguments:['h6'],
                tags:['h6'],
                tooltip:"Header 6"
            },
            h4:{
                visible:true&&!($.browser.mozilla),
                className:'h4',
                command:'formatBlock',
                arguments:['<H4>'],
                tags:['h4'],
                tooltip:"Header 4"
            },
            h5:{
                visible:true&&!($.browser.mozilla),
                className:'h5',
                command:'formatBlock',
                arguments:['<H5>'],
                tags:['h5'],
                tooltip:"Header 5"
            },
            h6:{
                visible:true&&!($.browser.mozilla),
                className:'h6',
                command:'formatBlock',
                arguments:['<H6>'],
                tags:['h6'],
                tooltip:"Header 6"
            },
            separator07:{visible:true},
            cut:{visible:true},
            copy:{visible:true},
            paste:{visible:true}
        }
    })*/
});