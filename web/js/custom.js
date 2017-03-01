var public_path = '';
function fillRate(val)
{
    $('input[name="Comments[rate]"]').val(val);
}
function getRandom()
{
    var url = 'randomAjax';
    var ids = '';
    $('.random-salon').each(function(){ids += ' ' + $(this).data("id");});
    $.ajax({
        url: url,
        type: 'get',
        data: {
            ids: ids,
        },
        success: function (data) {
            var newRow = $.parseHTML(data)[1];
            console.log(newRow);
            $('#random_salons_wrapper').animate({left: "-500", opacity: 0, position: "absolute"},600,'swing',function(){
                $('#random_salons_wrapper').remove();
                var c = newRow.style;
                c.left = "500px";
                c.opacity = "0";
                $('#random_wrapper').append(newRow);
                $('#random_salons_wrapper').animate({left: "0", opacity: 1},600,'linear',function(){});
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}
function headSearch(key)
{
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var result_block = $('#head-search-results');
    var form = $('#form_head-search');

    if (key != '') {
        result_block.show();
        result_block.html('Идёт загрузка...');
        var url = form.attr('action');
        console.log(key);
        $.ajax({
            url: url,
            type: 'post',
            data: {
                key: key,
                _csrf: csrfToken,
            },
            success: function (response) {
                var res_html = '<ul class="list-group">';
                if (response.results.length == 0) res_html = 'Результатов нет.'
                $.each(response.results, function (index, el) {
                    var parsedFile = $.parseJSON(el.filename);
                    if (parsedFile !== null && parsedFile[1] != '') var file = '/files/' + el.alias + '/' + $.parseJSON(el.filename)[1];
                    else var file = '/images/nofoto.jpg';
                    res_html += '<li class="list-group-item"><a href="' + window.public_path + '/salon/' + el.alias
                            + '"><img src="' + file
                            + '"/><span class="salon-short">' + el.title + '</span></a></li>';
                });
                result_block.html(res_html+'</ul>');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }

    else {
        result_block.hide();
        result_block.html('Идёт загрузка...');
    }


}

$(document).on('click', '.comments-rate label', function () {
    console.log(1);
    console.log($(this).prev('input').val());
    $('input[name="Comments[rate]"]').attr('value', $(this).prev('input').val());
});
$(document).on('click', '.category-buttons .btn-primary', function (){
    $('.category-buttons').addClass('btn-raised');
    $(this).removeClass('btn-raised');
});

$(document).on('ready', function () {

    $("#new_comment form").on("beforeSubmit", function(e) {
        e.preventDefault();
        e.stopPropagation();
        var form = $(this);
        var wrap =  $("#comment_form_wrapper");
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            dataType: 'json',
            data: form.serialize(),
            success: function (data) {
                if (data) {
                    wrap.addClass('panel-success');
                    wrap.find('.panel-heading').html('<h4>Спасибо за ваш отзыв. Он будет опубликован после прохождения модерации!</h4>');
                    wrap.find(":input").attr('disabled', true);
                    form.find('.btn').attr('disabled', true);
                    form.find('.btn').text('Отправлено!');
                }
                return false;
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
                return false;
            }
        });
        return false;
});

    $('#comments-created_at').datetimepicker({
        format:'Y-m-d H:i:00',
    });
    $('#comments-rate').rating(function(vote, event){
        window.fillRate(vote);
    });

    $('.fancybox').fancybox();

    $( function() {
        $( "#sortable_img" ).sortable({
            revert: true,
            update: function (event) {
                var allfiles = '';
                $.each(event.target.children, function (i, e){
                    allfiles += $(e).data('file') + ' ';
                });
                $('#salon-filename').val(allfiles);
            }
        });
        $( "ul, li" ).disableSelection();
    } );
});

$(document).on('blur', '#salon-address', function() {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var adr = $(this).val();
    var url = $(this).data('action');
    console.log(adr, url);
    if (adr != '')
    {
        $.ajax({
            url: url,
            type: 'post',
            data: {
                address: adr,
                _csrf: csrfToken
            },
            success: function (response) {
                var coords = $.parseJSON(response.results);
                if (coords.lat == '') {
                    $('#salon-coordinates-lat').val('К сожалению, ничего не найдено:(');
                    $('#salon-coordinates-lon').val('К сожалению, ничего не найдено:(');
                }
                else {
                    $('#salon-coordinates-lat').val(coords.lat);
                    $('#salon-coordinates-lon').val(coords.lon);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#salon-coordinates-lat').val('К сожалению, произошла внутренняя ошибка:(');
                $('#salon-coordinates-lon').val('К сожалению, произошла внутренняя ошибка:(');
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }
})