/**
 *
 */


$(document).ready( function() {
	//alert("riz parfum�");
	$("#navigation1 li").mouseover(function(){

		//$("#navigation1").animate({left:"0"});
	});

	$("#navigation1 li").mouseout(function(){

		//$("#navigation1").animate({left:"-100px"});
	});


	$("#navigation1 ul li.passive").mouseover( function() {
		$(this).css("background-color","#cf0069");
		$(this).css("color","#fff");


	});
	$("#navigation1 ul li.passive").mouseout( function() {
		$(this).css("background-color","transparent");
		$(this).css("color","#000");

	});

	var hauteur = window.innerHeight;
	//alert( hauteur );
	//$(".main").css("height", hauteur);



	$(".coursmaxclick").click( function() {

		$(".coursmax").css("display","none");
		$( this ).parent().parent().parent().next(".coursmax").slideDown('slow');
		$( this ).parent().css("display","none");
	});

	$(".coursminclick").click( function() {
		$(".coursmax").slideUp('slow');
		$(".plus").css("display","block");
		$(".plus").css("display","block");

	});




	$(".addpanier").click( function() {
		//var selectionne = ( $(this).is(':checked') );
		/*var request = $.ajax({
			url: "ajax_addbasket.php",
			method: "GET",
			data: 'object=' + $(this).attr("ref")  ,
			dataType: "html"
			});
			request.done(function( msg ) {
					$("#decompte").html( "PANIER (" + msg + ")")
			});
			request.fail(function( jqXHR, textStatus ) {
				   //alert( "Request failed: " + textStatus );
			});
		 */
		var obj = $(this).attr("ref");
		window.location.href="addbasket.php?object=" + obj ;

	});

	$(".login").click( function() {

		var obj = $(this).attr("ref");
		window.location.href="login.php" ;

	});


	$(".removepanier").click( function() {
		/*$(this).css("display","none");

	var request = $.ajax({
		url: "ajax_removebasket.php",
		method: "GET",
		data: 'object=' + $(this).attr("val")  ,
		dataType: "html"
		});
		request.done(function( msg ) {
			$( this ).css("display","none");
			$("#decompte").html( "PANIER (" + msg + ")");
		     if (msg>0) {
			     $(".footerf").css("position","relative");
			     $("#menupanier").css("display","block");
		     } else {
		    	 $("#menupanier").css("display","none");
		     }

			});

		});
		request.fail(function( jqXHR, textStatus ) {
			   //alert( "Request failed: " + textStatus );
		});

		 */
		var obj = $(this).attr("ref");
		window.location.href="removebasket.php?object=" + obj ;
	});

	$(".couverture").mouseover( function() {
		$( this ).animate({
		    opacity: 0,
		    }, 1000, function() {
		    // Animation complete.
		  });
	});
	$(".couverture").mouseout( function() {
		  $( this ).animate({
			    opacity: 0.7,
			    }, 1000, function() {
			    // Animation complete.
			  });
	});

	$(".calcul").change( function() {
		var prixtotal 	= $("#prixtotal").val();
		var nombre 		= $("#nombre").val();

		$(this).attr('checked', true) ;

		var dejamembre   = 	$('#dejamembre').is(':checked');
		var seraimembre  = 	$('#seraimembre').is(':checked');

		if ( dejamembre || seraimembre ) {
			prixtotal = prixtotal - ( nombre * 30 );
		}

		$("#coursseul").val( prixtotal );
		$("#cotisation").val( "0" );

		if ( seraimembre ) {
			prixtotal	=	prixtotal + 20;
			$('#dejamembre').attr('checked', false) ;
			$("#cotisation").val( "30" );
		}

		$("#total").html( prixtotal );
		$("#prixfinal").val( prixtotal );
	});
});

