/****************************************
file: adminMain.js
Main JS file for Admin custom JS
****************************************/

$(document).ready(function() {

    /****************************************
        Function for modification user
        put value in modal
    ****************************************/
    $('.btn_action_mUser').on('click', function(){
        var id_user = $(this).data("iduser");
        $('#userModalIdUser').val(id_user);
        $('#userModalSelRole').val($(this).data("idrole"));
        $('#userModalSelEtat').val($(this).data("idstate"));
        $('#divNameUserModal').html("<strong>" + $("#U_F_"+id_user).html() + " " + $("#U_L_"+id_user).html() + "</strong>");
        $('#modifUserModal').modal('show');
    });

    /****************************************
        Function for modification comment
        put value in modal
    ****************************************/
    $('.btn_action_mComment').on('click', function(){
        $('#commentModalIdUser').val($(this).data("iduser"));
        $('#commentModalIdCom').val($(this).data("idcom"));
        $('#commentModalSelEtat').val($(this).data("idstate"));
        $('#divNameCommentModal').html("<strong>Commentaire id: " + $(this).data("idcom") + "</strong>");
        $('#modifCommentModal').modal('show');
    });

    /****************************************
        Function for modification Category post
        put value in modal
    ****************************************/
    $('.btn_action_mCat').on('click', function(){
        $('#catModifModalIdCat').val($(this).data("idcat"));
        $('#catModifModalText').val($("#C_T_"+$(this).data("idcat")).html());
        $('#divNameModifCatModal').html("<strong>" + $(this).data("idcat") + " : " + $("#C_T_"+$(this).data("idcat")).html() + "</strong>");
        $('#modifCatModal').modal('show');
    });

    /****************************************
        Function for Suppression Category post
        put value in modal
    ****************************************/
    $('.btn_action_sCat').on('click', function(){
        $('#catSupModalIdCat').val($(this).data("idcat"));
        $('#divSpanSupCatModal').html("<strong>" + $(this).data("idcat") + " : " + $("#C_T_"+$(this).data("idcat")).html() + "</strong>");
        $('#supCatModal').modal('show');
    });

});