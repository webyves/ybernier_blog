/****************************************
file: adminMain.js
Main JS file for Admin custom JS
****************************************/

$(document).ready(function() {

    /****************************************
        Function for modification user
        put value in modal
    ****************************************/
    $(".btn_action_mUser").on("click", function(){
        $("#userModalIdUser").val($(this).data("iduser"));
        $("#userModalSelRole").val($(this).data("idrole"));
        $("#userModalSelEtat").val($(this).data("idstate"));
        $("#divNameUserModal").html("<strong>" + $("#U_F_" + $(this).data("iduser")).html() + " " + $("#U_L_" + $(this).data("iduser")).html() + "</strong>");
        $("#userModalChkbxUpdPostState").prop("checked", false);
        $("#userModalChkbxUpdComState").prop("checked", false);
        if ($("#userModalSelEtat").val() === 2) {
            $("#userModalUpdPostCom").removeClass("d-none");
        } else {
            $("#userModalUpdPostCom").addClass("d-none");
        }
        $("#modifUserModal").modal("show");
    });
    
    /****************************************
        Function for modification user
        uncheck & show/hide checkbox options
    ****************************************/
    $("#userModalSelEtat").on("change", function(){
        if ($("#userModalSelEtat").val() === 2) {
            $("#userModalUpdPostCom").removeClass("d-none");
        } else {
            $("#userModalUpdPostCom").addClass("d-none");
            $("#userModalChkbxUpdPostState").prop("checked", false);
            $("#userModalChkbxUpdComState").prop("checked", false);
        }
    });

    /****************************************
        Function for modification comment
        put value in modal
    ****************************************/
    $(".btn_action_mComment").on("click", function(){
        $("#commentModalIdUser").val($(this).data("iduser"));
        $("#commentModalIdCom").val($(this).data("idcom"));
        $("#commentModalSelEtat").val($(this).data("idstate"));
        $("#divNameCommentModal").html("<strong>Commentaire id: " + $(this).data("idcom") + "</strong>");
        $("#modifCommentModal").modal("show");
    });

    /****************************************
        Function for modification Category post
        put value in modal
    ****************************************/
    $(".btn_action_mCat").on("click", function(){
        $("#catModifModalIdCat").val($(this).data("idcat"));
        $("#catModifModalText").val($("#C_T_" + $(this).data("idcat")).html());
        $("#divNameModifCatModal").html("<strong>" + $(this).data("idcat") + " : " + $("#C_T_"+$(this).data("idcat")).html() + "</strong>");
        $("#modifCatModal").modal("show");
    });

    /****************************************
        Function for Suppression Category post
        put value in modal
    ****************************************/
    $(".btn_action_sCat").on("click", function(){
        $("#catSupModalIdCat").val($(this).data("idcat"));
        $("#divSpanSupCatModal").html("<strong>" + $(this).data("idcat") + " : " + $("#C_T_"+$(this).data("idcat")).html() + "</strong>");
        $("#supCatModal").modal("show");
    });
    
    /****************************************
        Function for modification Post
        put value in modal
    ****************************************/
    $(".btn_action_mPost").on("click", function(){
        $("#modifPostModalSelCat").val($(this).data("idcat"));
        $("#modifPostModalSelEtat").val($(this).data("idstate"));
        $("#modifPostModalIdPost").val($(this).data("idpost"));
        $("#divNamePostModal").html("<strong>" + $("#P_T_"+$(this).data("idpost")).html() + "</strong>");
        $("#modifPostModal").modal("show");
    });

    
});