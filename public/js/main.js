
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({'html':true});

    $(".add-favorite").on("click", function(event){
        $.ajax({
            url:        '/exchange/favorite',
            type:       'POST',
            dataType:   'json',
            async:      true,

            success: function(data, status) {
                alert('success');
            },
            error : function(xhr, textStatus, errorThrown) {
                alert('Ajax request failed.');
            }
        });
    });

});

function addFavorite(id, element)
{
    $.ajax({
        url:        "/exchange/favorite/"+id,
        type:       'POST',
        dataType:   'json',
        async:      true,

        success: function(data, status) {
            $(element).removeAttr('onclick').attr('onclick', 'unFavorite('+id+', this); return false;');
            $(element).find('span').removeClass('glyphicon-star-empty').addClass('glyphicon-star');
        },
        error : function(xhr, textStatus, errorThrown) {
            alert('Ajax request with id '+id+' failed.');
        }
    });
}

function unFavorite(id, element)
{
    $.ajax({
        url:        "/exchange/unfavorite/"+id,
        type:       'POST',
        dataType:   'json',
        async:      true,

        success: function(data, status) {
            $(element).removeAttr('onclick').attr('onclick', 'addFavorite('+id+', this); return false;');
            $(element).find('span').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
        },
        error : function(xhr, textStatus, errorThrown) {
            alert('Ajax request with id '+id+' failed.');
        }
    });
}

function hideFromAnalysis(id, element)
{
    $.ajax({
        url:        "/exchange/hide-analysis/"+id,
        type:       'POST',
        dataType:   'json',
        async:      true,

        success: function(data, status) {
            $(element).closest('tr').addClass('hidden');
        },
        error : function(xhr, textStatus, errorThrown) {
            alert('Ajax request failed.');
        }
    });
}

function showAllAnalisys() {
    $('#analysis-stock').find('tr.hidden').removeClass('hidden');
}