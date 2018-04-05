$(function () {
    $(".submitButton").on('click', function () {
        var url = $(this).attr('data-url');
        var content = $("textarea#comment_article_content").val();
        var idArticle = $(this).attr('id-article');
        console.log(content);
        var data = { content : content,
            idArticle : idArticle
        };
        console.log(data);
        $.ajax({
            url : url,
            type: 'POST',
            data : data
            ,
            success: function (response) {
                console.log('modification effectuée avec succès '+ response);
            },
            error: function () {
                console.log('erreur lors de la requête')
            }
        }).done(function () {
            var $containerComments = $('#comments');
            var template = $containerComments.attr('comment-prototype')
                .replace(/__nameUser__/g, 'Jérôme Butel')
                .replace(/__date__/g, '30/03/2018')
                .replace(/__hour__/g, '10h30')
                .replace(/__comment__/g, content);
            $containerComments.append(template);
        });
    });

    $(".deleteButton").on("click", function () {
        var el = $(this).closest('.comment');
        var url = $(this).attr('data-url');
        $.ajax({
            url: url,
            method: 'DELETE'
        }).done(function () {
            el.fadeOut().remove();
        });
    });
});