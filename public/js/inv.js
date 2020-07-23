
$(document).ready(function () {
    var cnt = 0;

    $('.container-fluid').find('a').click(function () {
        window.open('https://en.wikipedia.org' + $(this).attr('href'));
        return false;
    })

    $('#bodyContent').find('li').each(function () {
        //console.log($(this).html());
        var str = explode(":", $(this).html());
        var str2 = explode(" in ", str[1]);
        $(this).after('<div id="clsExt' + cnt + '" clsss="clsExtracted" style="font-weight:bold;"><span class="preClsEra"></span> => \n\
<span class="preClsName"></span> => <span class="preClsCountry"></span> => <span class="preClsDtl"></span>\n\
        <span class="clsDtl"></span>\n\
<div class="clsName"></div>\n\
<div class="clsCountry"></div>\n\
<div class="clsFacts"></div>\n\
<div class="clsImgLink"></div></div>');
        $('#clsExt' + cnt).append('<input class="clsAddRec" type="button" value="add" onclick="addInvention(' + cnt + ')">');
        $('#clsExt' + cnt).append('</br/></br/>');
        $('#clsExt' + cnt).find('.preClsEra').html(str[0]);
        $('#clsExt' + cnt).find('.preClsName').html(str2[0]);
        $('#clsExt' + cnt).find('.preClsCountry').html(str2[1]);
        //$('#clsExt'+cnt).find('.clsDtl').html(str2[1]);
        $('#clsExt' + cnt).find('.clsName').html('Name <textarea class="txtName">' + str2[0] + '</textarea>');
        $('#clsExt' + cnt).find('.clsCountry').html('Country <textarea class="txtCountry">' + str2[1] + '</textarea>');
        $('#clsExt' + cnt).find('.clsFacts').html('Facts <textarea class="txtFact"></textarea>');
        $('#clsExt' + cnt).find('.clsImgLink').html('Image Link <textarea class="txtImgLink"></textarea>');
        cnt++;
        console.log(str[0] + " " + str[1]);
    })
});

function addInvention(cnt) {
    var fdata = {
        name: $('#clsExt' + cnt).find('.txtName').val(),
        year: $('#clsExt' + cnt).find('.txtCountry').val(),
        facts : $('#clsExt' + cnt).find('.txtFact').val(),
        images : $('#clsExt' + cnt).find('.txtImgLink').val()
    }
    $.ajax({
        url: '/ajaxinv/add-invention',
        type: 'POST',
        data: fdata,
        dataType: 'html',
        async: true,
        error: function () {
        },
        success: function (resp) {
            
        }
    });
}