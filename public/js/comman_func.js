function explode (delimiter, string, limit) {
    var emptyArray = {
        0: ''
    };
 
    // third argument is not required
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {
        return null;
    }
 
    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;
    }
 
    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
        return emptyArray;
    }
 
    if (delimiter === true) {
        delimiter = '1';
    }
 
    if (!limit) {
        return string.toString().split(delimiter.toString());
    }
    // support for limit argument
    var splitted = string.toString().split(delimiter.toString());
    var partA = splitted.splice(0, limit - 1);
    var partB = splitted.join(delimiter.toString());
    partA.push(partB);
    return partA;
}

function in_array (needle, haystack, argStrict) {
    var key = '',
    strict = !! argStrict;

    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }

    return false;
}

jQuery.fn.center = function (msg, fadeOut, left, top) {
    if(left === 'undefined'&& top=== 'undefined'){
        top = ($(window).height() - this.height()) / 2+$(window).scrollTop();
        left = ($(window).width() - this.width()) / 2+$(window).scrollLeft()
    }
    
    this.css({
        "position":"absolute"
    });
    this.css("top", top + "px");
    this.css("left", left + "px");
    $("#tlpMsg").html(msg);
    if(fadeOut){
        $(this).fadeIn('slow', function() {
            var id = (this).id;
            setTimeout(function() {   //calls click event after a certain time
                //$("#"+id).fadeOut(1000);
                }, 4000);
        });
    }
    else {
        $(this).fadeIn('slow', function() {
            
            });
    }
    return this;
}

jQuery.fn.centr = function (msg) {
    this.css({
        "position":"absolute"
    });
    this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
    this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
    $("#tlpMsg").text(msg);
    $(this).show();
    return this;
}

function sizeof (mixed_var, mode) {
    return this.count(mixed_var, mode);
} 
    
function calcTime(shr, smin, sampm, ehr, emin, eampm){
    var oneHour = 60*60*1000; 
    var oneMin = 60*1000; 
    var firstDate, mineDate, secondDate, timeArr = new Array();
    var indvhr, indvmin; 
    if(sampm=='PM' && parseInt(shr) != 12)
        shr = eval(parseInt(shr)+ 12);
    if(eampm=='PM' && parseInt(ehr) != 12)
        ehr = eval(parseInt(ehr)+ 12);
    firstDate = new Date(2012,01,01,shr,smin,00);
    secondDate = new Date(2012,01,01,ehr,emin,00);
    if(smin>emin)
        mineDate = new Date(2012,01,01,parseInt(shr)+1,emin,00);
    else
        mineDate = new Date(2012,01,01,shr,emin,00);
    indvmin = parseInt(Math.abs((firstDate.getTime() - mineDate.getTime())/(oneMin)));
    if(indvmin == 60)
        indvmin = 0;
       
    if(sampm == "AM" && parseInt(shr) == 12){
        firstDate = new Date(2012,01,01,0,smin,00);
    }
    if(eampm == "AM" && parseInt(ehr) == 12){
        secondDate = new Date(2012,01,01,0,emin,00);
    }
    if((sampm == "PM" && eampm == "PM" && parseInt(shr)>parseInt(ehr) && parseInt(shr) != 12)||(sampm == "AM" && eampm == "AM" && parseInt(shr)>parseInt(ehr) && parseInt(shr) != 12)){
        secondDate = new Date(2012,01,02,ehr,emin,00);
    }
    if((sampm == "AM" && eampm == "AM" && parseInt(shr)<parseInt(ehr) && parseInt(ehr) == 12)){
        secondDate = new Date(2012,01,02,0,emin,00);
    }
    if(sampm == "AM" && eampm == "PM" && parseInt(ehr) != 12){
        secondDate = new Date(2012,01,01,ehr,emin,00);
    }
    if(sampm == "PM" && eampm == "AM" && parseInt(ehr) != 12){
        secondDate = new Date(2012,01,02,ehr,emin,00);
    }
    if(sampm == "PM" && eampm == "AM" && parseInt(ehr) == 12){
        secondDate = new Date(2012,01,02,0,emin,00);
    }
    indvhr = parseInt(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneHour)));
    timeArr[0] = indvhr;
    timeArr[1] = indvmin;
    return timeArr;
}

function isNumber(event) {
    var charCode = event.which;
    if (charCode <= 13) 
        return true; 

    var keyChar = String.fromCharCode(charCode);
    return /[0-9.]/.test(keyChar); 
}

// Function to highlight div
function elemHighLight(element, baseColor, alertColor){
    if(element.length == 1) {
        setTimeout(function(){
            $(element).children().css({
                'background-color': baseColor
            })
        },500);
        setTimeout(function(){
            $(element).children().css({
                'background-color': alertColor
            })
        },800);
        setTimeout(function(){
            $(element).children().css({
                'background-color': baseColor
            })
        },1300);
        setTimeout(function(){
            $(element).children().css({
                'background-color': alertColor
            })
        },1600);
        setTimeout(function(){
            $(element).children().css({
                'background-color': baseColor
            })
        },2100); 
    }
}

function genModalSkeleton(header){
    var modalSkeleton = $("<div />", {
        "id":"ModalShell",
        "class":"modal fade"
    });
    
    var modalHeader = $("<div />", {
        "id":"ModalShellHeader",
        "class":"modal-header"
    }).html('<a class="close" data-dismiss="modal">x</a><h3>'+header+'</h3>');
    
    var modalBody = $("<div />", {
        "id":"ModalShellBody",
        "class":"modal-body"
    }).html('Loading');
    
    modalSkeleton.append(modalHeader).append(modalBody);
    
    return modalSkeleton;
}

function genModalSkel2(modalId, header){
    var modalSkeleton = $("<div />", {
        "id":modalId,
        "class":"modal fade"
    });
    
    var modalHeader = $("<div />", {
        "id":"ModalShellHeader",
        "class":"modal-header"
    }).html('<a class="close" data-dismiss="modal">x</a><h3>'+header+'</h3>');
    
    var modalBody = $("<div />", {
        "id":"ModalShellBody",
        "class":"modal-body"
    }).html('Loading');
    
    modalSkeleton.append(modalHeader).append(modalBody);
    
    return modalSkeleton;
}

/**
 * Function to get URL param value
 * 
 * @author Mujaffar Sanadi      Created on 17 July 2014
 */
function getPrmByName( name,href )
{
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( href );
    if( results === null )
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}

/**
 * Function to generated Tooltip skeleton
 * 
 * @author  Mujaffar Sanadi     Created on 26 Aug 2014
 */
function genTooltipSkeleton(header){
    var modalSkeleton = $("<div />", {
        "id":"ModalShell",
        "class":"modal fade"
    });
    
    var modalHeader = $("<div />", {
        "id":"ModalShellHeader",
        "class":"modal-header"
    }).html('<a class="close" data-dismiss="modal">x</a><h3>'+header+'</h3>');
    
    var modalBody = $("<div />", {
        "id":"ModalShellBody",
        "class":"modal-body"
    }).html('Loading');
    
    modalSkeleton.append(modalHeader).append(modalBody);
    
    return modalSkeleton;
}

/**
 * Function to convert single digit date value to 2 digit
 * 
 * @author  Mujaffar Sanadi     Created on 19 Sep 2014
 */
function convertInTwoDigit(value){
    for(var i=0;i<10;i++){
        if(i == value){
            return "0"+value;
        }
    }
    return value;
}

/**
 * Function to highlight bg color to grasp attention
 * 
 * @author  Mujaffar Sanadi     Created on 19 Sep 2014
 */
function highlightBg(elemId){
    if($("#"+elemId).length == 1) { 
        $("#"+elemId).css({
            'background-color': 'white',
            'color':'#555'
        })
        setTimeout(function(){
            $("#"+elemId).css({
                'background-color': 'red',
                'color':'#fff'
            })
        },800);
        setTimeout(function(){
            $("#"+elemId).css({
                'background-color': 'white',
                'color':'#555'
            })
        },1300);
        setTimeout(function(){
            $("#"+elemId).css({
                'background-color': 'red',
                'color':'#fff'
            })
        },1600);
        setTimeout(function(){
            $("#"+elemId).css({
                'background-color': 'white',
                'color':'#555'
            })
        },2100);
    }
}