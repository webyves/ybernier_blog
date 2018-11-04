/****************************************
file: adminMain.js
Main JS file for Admin custom JS
****************************************/

$(document).ready(function() {

    /****************************************
        Function for modification user
        put goog value in modal
    ****************************************/
    $('.btn_action_mUser').on('click', function(){
        var id_user = $(this).data("iduser");
        $('#userModalIdUser').val(id_user);
        $('#userModalSelRole').val($(this).data("idrole"));
        $('#userModalSelEtat').val($(this).data("idstate"));
        $('#divNameUserModal').html("<strong>" + $("#U_F_"+id_user).html() + " " + $("#U_L_"+id_user).html() + "</strong>");
        $('#modifUserModal').modal('show');
    });





});