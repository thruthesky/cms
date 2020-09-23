$$(function() {
    $(".show").mousedown(function(){
        $(this).prev().attr('type','text');
    }).mouseup(function(){
        $(this).prev().attr('type','password');
    }).mouseout(function(){
        $(this).prev().attr('type','password');
    });
});