var divHolder = {};
divHolder['div1'] = {};
divHolder['div2'] = {};
divHolder['div3'] = {};
divHolder['div4'] = {};
divHolder['div5'] = {};
divHolder['div6'] = {};
divHolder['div7'] = {};
divHolder['div8'] = {};
divHolder['div9'] = {};
divHolder['div10'] = {};

var cnt = 1;

$(document).ready(function() {

    startFallDown(); 

})

function startFallDown(){
    for(cnt = 1; cnt < 35; cnt++){        
        var posx = (Math.random() * ($(document).width() - 200)).toFixed();
        $('.oldDiv'+cnt).css({
            'left': posx+'px'
        }) 
        $('.oldDiv'+cnt).attr('customattr',posx)
        
        var divideByThree = cnt % 3;
        if(divideByThree == 0){
            var rotateBy = Math.floor(Math.random() * 20) + 5
            var negativeDecider = Math.floor(Math.random() * 2) + 0

            if(negativeDecider == 1){
                rotateBy = 0 - rotateBy;
            }
            var color = '#'+ Math.round(0xffffff * Math.random()).toString(16);
            $('.oldDiv'+cnt).css({
                '-ms-transform': 'rotate('+rotateBy+'deg)', /* IE 9 */
                '-webkit-transform': 'rotate('+rotateBy+'deg)', /* Chrome, Safari, Opera */
                'transform': 'rotate('+rotateBy+'deg)',
                'background-color': color
            })
        }
        doSetTimeout(cnt)
    }
}

function startFallDownAgain(cnt){       
    var posx = (Math.random() * ($(document).width() - 200)).toFixed();
    $('.oldDiv'+cnt).css({
        'left': posx+'px'
    }) 
    $('.oldDiv'+cnt).attr('customattr',posx)
    var rotateDecider = Math.floor(Math.random() * 3) + 0    
            
    if(rotateDecider == 0){
        var rotateBy = Math.floor(Math.random() * 20) + 5
        var negativeDecider = Math.floor(Math.random() * 2) + 0
        
        if(negativeDecider == 1){
            rotateBy = 0 - rotateBy;
        }
        var color = '#'+ Math.round(0xffffff * Math.random()).toString(16);
        $('.oldDiv'+cnt).css({
            '-ms-transform': 'rotate('+rotateBy+'deg)', /* IE 9 */
            '-webkit-transform': 'rotate('+rotateBy+'deg)', /* Chrome, Safari, Opera */
            'transform': 'rotate('+rotateBy+'deg)',
            'background-color': color
        })
    }
    doSetTimeout(cnt)
}

function doSetTimeout(i) {
    
    var loopTime = Math.floor(Math.random() * 6000) + 3000
    var swingDecider = Math.floor(Math.random() * 3) + 0
    var swingType = new Array('swing', 'linear', 'easeOutBounce');

    setTimeout (function() {  
        var movableDiv = $('.oldDiv'+i);
        var mythis = $('#holder a');
        // get position of the element we clicked on
        var offset = mythis.offset();
    
        // get width/height of click element
        var h = mythis.outerHeight();
        var w = mythis.outerWidth();
    
        // get width/height of drop element
        var dh = movableDiv.outerHeight();
        var dw = movableDiv.outerWidth();
    
        // determine middle position
        var initLeft = offset.left + ((w/2) - (dw/2));                
               
        // animate drop
        movableDiv.css({
            top: $(window).scrollTop() - dh,
            opacity: 1,
            display: 'block'
        }).animate({
            top: offset.top - dh,
            opacity: 1
        }, loopTime, swingType[swingDecider], function() {
            $(movableDiv).removeAttr('style');
            
            startFallDownAgain(i);
        })       
    
    },1500);
    
    
}