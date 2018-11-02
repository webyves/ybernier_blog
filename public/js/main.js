/****************************************
file: main.js
Main JS file for custom JS
****************************************/

$(document).ready(function() {

    /****************************************
        Function for response comments
        put id in hidden input in modal
    ****************************************/
    $('.btn_action_reply').on('click', function(){
        $('#respComInputIdCom').val($(this).data("idcom"));
        $('#respComModal').modal('show');
    });





});