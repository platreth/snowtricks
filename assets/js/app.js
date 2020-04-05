/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');


$( ".button" ).click(function() {
    $("html, body").animate({ scrollTop: $(document).height() }, 1000);
});

$( ".button2" ).click(function() {
    $("html, body").animate({ scrollTop: 0 }, 1000);
});

$('.loadMoreAjax').click(function () {
    $.get('/trickAjax/' + $('.card').length , function (data) {
        if (data.length < 4) {
            $('.loadMoreAjax').hide();
        }
        $.each(data, function (index, trick) {

            let html =   '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4"> <div class="card"> <div class="view overlay"> <div class="embed-responsive embed-responsive-16by9 rounded-top">';
            html += '<img class="embed-responsive-item" src="/uploads/picture/'+ trick.picture + '" >';
            html +='</div></div><div class="card-body">';

                html += '<a href="trick/'+ trick.slug +'">' +
                '<h4 class="card-title">' + trick.name + '</h4> <p class="card-text"> </p> </a>' +
                '<p class="card-text">' + trick.date_create  + ' </p>' +
                '<a href="/member/trick/edit/'+ trick.slug +'"><div class="edit"></div></a> <div class="delete" data-toggle="modal" data-target="#basicExampleModal_'+ trick.id+'"></div> </div> </div>'+
            '<div class="modal fade" id="basicExampleModal_'+ trick.id+'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_'+ trick.id+'" aria-hidden="true">' +
                '<div class="modal-dialog" role="document"> <div class="modal-content"> <div class="modal-header"> <h5 class="modal-title" id="exampleModalLabel_'+ trick.id+'">Supprimer</h5>'+
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>'+
            '</button> </div> <div class="modal-body">Êtes-vous sûr de vouloir supprimer cette figure ? </div> <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-dismiss="modal">Retour</button>'+
                '<form method="post" action="/member/trick/delete/'+ trick.id +'"> <input type="hidden" name="_method" value="DELETE"> <button type="submit" class="btn btn-primary">Supprimer</button> </form>' +
                    '</div> </div> </div> </div> </div>';
            $('.trick-container').append(html);
        })

    })

})
