/**
 *
 */

$(document).ready( function() {



    $(".legende").click( function() {
    	$(this).next().css("display","block");
    	$(this).find(':first-child').css("display","none");
    });

    $(".description").click( function() {
    	$(this).css("display","none");
    	$(".plus").css("display","block");
    });


    $(".presselegende").click( function() {
    	$(this).next().css("display","block");
    	$(".plus").css("display","none");
    });

    $(".pressedescription").click( function() {
    	$(this).css("display","none");
    	$(".plus").css("display","block");
    });



});



