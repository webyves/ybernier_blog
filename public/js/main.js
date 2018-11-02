/****************************************
file: main.js
Main JS file for custom JS
****************************************/

$(document).ready(function() {

    $('.btn_action_reply').on('click', function(){
        // alert($(this).data("idcom"));
        $('#respComInputIdCom').val($(this).data("idcom"));
        $('#respComModal').modal('show');
    });





});