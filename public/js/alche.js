var reactions = [];
var reactionCnt = 0;
var otherReactions = [];
var selectedItem = [];

$(function () {

    $(".getIt").click(function () {
        reset();
    });

    $('.tblAlche').find('td').click(function () {
        if (!$(this).hasClass('clsLvl1')) {
            if ($(this).hasClass('alert-info')) {
                $(this).removeClass('alert-info');
            } else {
                $(this).addClass('alert-info');
            }
        }
    })

    $('.tblAlche').find('td').dblclick(function () {
        var thisTd = $(this);
        $(this).html('<input type="text" value="" class="input input-small"/>');
        $(this).find('input').blur(function () {
            $(thisTd).html($(this).val());
        })
    })

    $(".pickThem").click(function () {

        selectedItem = [];
        var i = 0;
        $('.tblAlche').find('.alert-info').each(function () {
            if ($(this).hasClass('alert-info')) {
                selectedItem[i] = $(this).text();
                i++;
            }
        })
        selectedItem = shuffle(selectedItem);

    })

    $(".getOtherRec").click(function () {

        $('.tblOtherReactions tbody').html('');

        // Get reactions
        var j = 0;
        var k = 0;
        otherReactions = [];
        var l = 0;

        for (j = 0; j < selectedItem.length; j++) {
            var elem1 = selectedItem[j];
            var elem2 = '';
            for (k = 0; k < selectedItem.length; k++) {
                if (j != k) {

                    elem2 = selectedItem[k];
                    var fdata = {
                        elem1: elem1,
                        elem2: elem2
                    }
                    $.ajax({
                        url: '/ajax/check-reactions',
                        type: 'POST',
                        data: fdata,
                        dataType: 'json',
                        async: true,
                        error: function () {
                        },
                        success: function (resp) {
                            if (resp.found == 'true') {
                                otherReactions[l] = {product: resp.product, elem1: resp.elem1, elem2: resp.elem2};
                                l++;
                                var tr = '<tr>';
                                tr += '<td><input type="checkbox" class="inp-rec"/></td>';
                                tr += '<td>' + resp.elem1 + '</td>';
                                tr += '<td>' + resp.elem2 + '</td>';
                                tr += '<td>' + resp.product + '</td>';
                                $('.tblOtherReactions tbody').append(tr);
                            }
                        }
                    });

                }
            }
        }
    })

});

function reset() {
    reactions = [];
    reactionCnt = 0;
    otherReactions = [];
    selectedItem = [];

    $('.tblAlche').find('td').each(function () {
        if ($(this).hasClass('clsLvl1')) {

        } else {
            $(this).text('');
        }
    });
    $('.tblReactions').find('tbody').html('');
    $('.tblOtherReactions').find('tbody').html('');
    $('.tblReacSelective').find('tbody').html('');

    getReaction('clsLvl1');
}

function getReaction(tdClass) {
    var element = '';
    if (tdClass == 'clsLvl1') {
        element = $('.element').val();
    } else {
        element = $('.' + tdClass).text();
    }
    var fdata = {
        element: element,
        forproduct: $('.forproduct').val()
    }

    $.ajax({
        url: '/ajax/get-reactions',
        type: 'POST',
        data: fdata,
        dataType: 'json',
        async: true,
        error: function () {
        },
        success: function (resp) {
            var nxtCls1 = $('.' + tdClass).attr('nxtCls1');
            var nxtCls2 = $('.' + tdClass).attr('nxtCls2');
            resp.elem1 = $.trim(resp.elem1);
            resp.elem2 = $.trim(resp.elem2);
            $('.' + nxtCls1).text(resp.elem1);
            $('.' + nxtCls2).text(resp.elem2);

            if (resp.elem1 !== null && resp.elem2 !== null && element !== null &&
                    resp.elem1 !== "" && resp.elem2 !== "" && element !== "" &&
                    resp.elem1 !== " " && resp.elem2 !== " " && element !== " ") {
                reactions[reactionCnt] = {product: element, elem1: resp.elem1, elem2: resp.elem2}
                reactionCnt++;
            }
            var nextClass = $('.' + tdClass).attr('nextClass');
            var nextClass = $('.' + tdClass).attr('nextClass');
            if (nextClass !== '') {
                getReaction(nextClass);
            } else {
                $('.tblReactions tbody').html('');
                // console.log(reactions);
                // Iterate reactions
                for (var key in reactions) {
                    if (reactions.hasOwnProperty(key)) {
                        var tr = '<tr>';
                        tr += '<td><input type="checkbox" class="inp-rec"/></td>';
                        tr += '<td>' + reactions[key]['elem1'] + '</td>';
                        tr += '<td>' + reactions[key]['elem2'] + '</td>';
                        tr += '<td>' + reactions[key]['product'] + '</td></tr>';
                        $('.tblReactions tbody').append(tr);
                    }
                }
            }
        }
    });
}

function checkPuzzle() {
    selectedItem = shuffle(selectedItem);
    reactions = shuffle(reactions);
    otherReactions = shuffle(otherReactions);

    for (i = 0; i < otherReactions.length; i++) {
        reactions[reactions.length] = otherReactions[i];
    }

    reactions = shuffle(reactions);

    // Plot them on page
    for (i = 0; i < reactions.length; i++) {
        var elem1 = reactions[i]['elem1'];
        var elem2 = reactions[i]['elem2'];
        var product = reactions[i]['product'];

        var apdClass = '';
        // Check matching tr element and reactions
        $('.tblReacSelective tbody').find('tr').each(function () {
            var e1 = $(this).find('.e1').text();
            var e2 = $(this).find('.e2').text();
            var prd = $(this).find('.prd').text();

            if ((e1 == elem1 || e1 == elem2) && (e2 == elem1 || e2 == elem2) && (prd == product)) {
                reactions[i]['elem1'] = '';
                reactions[i]['elem2'] = '';
                reactions[i]['product'] = '';
                elem1 = elem2 = product = '';
            } else if ((e1 == elem1 || e1 == elem2) && (e2 == elem1 || e2 == elem2)) {
                $(this).find('.e1').addClass('alert').addClass('alert-danger');
                $(this).find('.e2').addClass('alert').addClass('alert-danger');
                $(this).find('.prd').addClass('alert').addClass('alert-danger');
                apdClass = 'alert alert-danger';
            }
        });
        var tr;
        tr = '<tr>';
        tr += '<td><input type="checkbox" class="inp-rec"/></td>';
        tr += '<td class="e1 ' + apdClass + '">' + elem1 + '</td>';
        tr += '<td class="e2 ' + apdClass + '">' + elem2 + '</td>';
        tr += '<td class="prd ' + apdClass + '">' + product + '</td></tr>';
        if (elem1 != '' && elem2 != '' && product != '') {
            $('.tblReacSelective tbody').append(tr);
        }
    }
    $('#divReac').hide();
    $('#divReacSelective').show();
}

function genPuzzle() {
    selectedItem = shuffle(selectedItem);
    reactions = shuffle(reactions);
//    otherReactions = shuffle(otherReactions);
//
//    for (i = 0; i < otherReactions.length; i++) {
//        reactions[reactions.length] = otherReactions[i];
//    }
//    reactions = shuffle(reactions);

    // Encrypt it
    var elem1 = "";
    var elem2 = "";
    var product = "";
    for (i = 0; i < reactions.length; i++) {

        reactions[i]['elem1'] = md5(reactions[i]['elem1']);
        reactions[i]['elem2'] = md5(reactions[i]['elem2']);
        reactions[i]['product'] = window.btoa(reactions[i]['product']);
    }

    var objElements = {};
    for (i = 0; i < selectedItem.length; i++) {
        objElements[i] = {elem: selectedItem[i]};
    }

    var pzlJson = [];
    pzlJson = {
        target: $('.element').val(),
        elements: objElements,
        reactions: reactions,
        starGoal: {"one": 30, "two": 20, "three": 10}
    }
    //console.log(reactions);
    $('.clsPzl').text(JSON.stringify(pzlJson));

    $('.tblReacSelective').find('tr').each(function () {
        var input = $(this).find('.inp-rec');
        if ($(input).is(":checked")) {
            elem1 = $(input).parent().parent().find('.e1').text();
            elem2 = $(input).parent().parent().find('.e2').text();
            product = $(input).parent().parent().find('.prd').text();

            console.log(product + " " + window.btoa(product));
        }
    })

    SelectText('clsPzl');
}

function shuffle(sourceArray) {
    for (var i = 0; i < sourceArray.length - 1; i++) {
        var j = i + Math.floor(Math.random() * (sourceArray.length - i));

        var temp = sourceArray[j];
        sourceArray[j] = sourceArray[i];
        sourceArray[i] = temp;
    }
    return sourceArray;
}

function shuffleObj(sourceArray) {
    for (var key in sourceArray) {
        if (reactions.hasOwnProperty(key)) {

        }
    }
    return sourceArray;
}

function SelectText(element) {
    var doc = document
            , text = doc.getElementById(element)
            , range, selection
            ;
    if (doc.body.createTextRange) {
        range = document.body.createTextRange();
        range.moveToElementText(text);
        range.select();
    } else if (window.getSelection) {
        selection = window.getSelection();
        range = document.createRange();
        range.selectNodeContents(text);
        selection.removeAllRanges();
        selection.addRange(range);
    }
}