function getComment(startComment) {
    $.ajax({
        url: urlAjaxCommentPost,
        type: "POST",
        data: {start: startComment, id: trickid},
        success: function (data, textStatus, jqXHR) {
            // var data = JSON.parse(data);
            console.log(data);
            for (let i = 0; i < data.length; i++) {
                var comment = data[i];
                var html = " <div class=\"card-body 0to5\">" +
                    " <div class=\"media d-block d-md-flex mt-3\">" +
                    '<img class="d-flex mb-3 mx-auto img-thumbnail" style="height: 100px;" src="/uploads/file/' + comment.picture + '" alt="' + comment.pseudo + '">' +
                    "<div class=\"media-body text-center text-md-left ml-md-3 ml-0\">" +
                    "<h5 class=\"mt-0 font-weight-bold\"> " + comment.pseudo + "" +
                    "</h5>" +
                    comment.content +
                    "</div>" +
                    "<p> " + comment.created_at.date + "</p>" +
                    "</div>" +
                    "</div>";

                $('.comment-ajax').append(html);

            }
            $('.loadMore').remove();
            var nbComment = $(".0to5").length;
            if (nbComment < compteur) {
                console.log(nbComment);
                $('.comment-ajax').append("<div class='loadMore text-center w-100' ><button class='btn btn-md btn-elegant loadMore_' >Voir + </button></div>")

                $('.loadMore_').click( function () {
                    getComment( nbComment )
                })
            }
        }

    })
}
getComment(0);