$(document).ready(function() {

	$( "#buttonAjaxFilter" ).click( function(  ) {
		$( "#ajax-search-form" ).trigger('submit');
	});

	$("body").on("click", ".col-pagination-ajax ul li a", function(e) {

		e.preventDefault();

		// get page location
		var page = $( this ).attr( 'href' );

		// parse page number
		var pageNum = /page=([0-9]+)/;
		var matchPage = page.match( pageNum );
		var pageInt = parseInt( matchPage[1] );

		$( "#ajax-search-form" ).trigger('submit', [ pageInt ]);

		return false;

	});

	var ajaxFilterDomains = $( "#ajax-search-form" );

	ajaxFilterDomains.submit(function(event, pageNum) {

		console.log( 'form submitted' )
		console.log( 'Pagenum: ' + pageNum );

		event.preventDefault();

		var formData = $( this ).serialize();

		$.ajax({
				type: 'POST',
				url: '/ajax/domain_filtering?page=' + pageNum,
				data: formData,
				cache: false,
				beforeSend:  function() {
					$( '.preload-search' ).show();
					$( '#ajax-filtered-domains' ).hide();
			},
				success: function(data){
					$( '.preload-search' ).hide();
					$( '#ajax-filtered-domains' ).show();
					$( '#ajax-filtered-domains' ).html( data );
				},
				error: function(data) {

					$( '.preload-search' ).hide();
					$( '#ajax-filtered-domains' ).show();
					sweetAlert("Oops...", data, "error");

				}
			});

		return false;
	});

		$( "#tablebuttonAjaxFilter" ).click( function(  ) {
		$( "#ajax-search-form" ).trigger('submit');
	});
	var ajaxFilterDomains = $( "#table-ajax-search-form" );

		ajaxFilterDomains.submit(function(event, pageNum) {

			console.log( 'form submitted' )
			console.log( 'Pagenum: ' + pageNum );

			event.preventDefault();

			var formData = $( this ).serialize();

			$.ajax({
				type: 'POST',
				url: '/ajax/table_domain_filtering',
				data: formData,
				cache: false,
				beforeSend:  function() {
					$( '.preload-search' ).show();
					$( '#table-ajax-filtered-domains' ).hide();
				},
				success: function(data){
					$( '.preload-search' ).hide();
					$( '#table-ajax-filtered-domains' ).show();
				$( '#table-ajax-filtered-domains' ).html( data );
				},
				error: function(data) {

					$( '.preload-search' ).hide();
					$( '#table-ajax-filtered-domains' ).show();
				sweetAlert("Oops...", data, "error");

				}
			});

			return false;
	});

	// stripe form - plan subscription
	var $form = $('#payment-form');

	$form.submit(function(event) {
			// Disable the submit button to prevent repeated clicks:
			$form.find('.submit').prop('disabled', true);

			// Request a token from Stripe:
			Stripe.createToken({
					number: $('.card-number').val(),
					cvc: $('.card-cvc').val(),
					exp_month: $('.card-expiry-month').val(),
					exp_year: $('.card-expiry-year').val()
			}, stripeResponseHandler);
			//Stripe.card.createToken($form, stripeResponseHandler);

			// Prevent the form from being submitted..:
			return false;
	});

	function stripeResponseHandler(status, response) {
		var $form = $('#payment-form');

		if (response.error) {
			// Show the errors on the form

			//$form.find('.payment-errors').text(response.error.message);
			$form.find('button').prop('disabled', false);

			sweetAlert("Oops...", response.error.message, "error");

		} else {

			// response contains id and card, which contains additional card details
			var token = response.id;
			var customer = $('.name-on-card').val();
			var email = $('.email-address').val();

			// append values we need!
			$form.append($('<input type="hidden" name="stripeToken" />').val(token));
			$form.append($('<input type="hidden" name="customer" />').val(customer));
			$form.append($('<input type="hidden" name="email" />').val(email));

			// and submit
			$form.get(0).submit();
		}

	}

	// stripe form - buy domains
	var $form = $('#checkout-form');

	$form.submit(function(event) {
			// Disable the submit button to prevent repeated clicks:
			$form.find('.submit').prop('disabled', true);

			// Request a token from Stripe:
			Stripe.createToken({
					number: $('.card-number').val(),
					cvc: $('.card-cvc').val(),
					exp_month: $('.card-expiry-month').val(),
					exp_year: $('.card-expiry-year').val()
			}, stripeResponseHandler2);

			// Prevent the form from being submitted..:
			return false;
	});

	function stripeResponseHandler2(status, response) {
		var $form = $('#checkout-form');

		if (response.error) {
			// Show the errors on the form

			//$form.find('.payment-errors').text(response.error.message);
			$form.find('button').prop('disabled', false);

			sweetAlert("Oops...", response.error.message, "error");

		} else {

			// response contains id and card, which contains additional card details
			var token = response.id;
			var customer = $('.name-on-card').val();
			var email = $('.email-address').val();
			var domain = $('.domain-checkout').val();

			// append values we need!
			$form.append($('<input type="hidden" name="stripeToken" />').val(token));
			$form.append($('<input type="hidden" name="customer" />').val(customer));
			$form.append($('<input type="hidden" name="email" />').val(email));
			$form.append($('<input type="hidden" name="domain" />').val(domain));

			// and submit
			$form.get(0).submit();
		}

	}


	// add to cart buttons ( home + inner )
	$('.add-to-cart, .add-to-cart-inner').click(function(ev) {

		ev.preventDefault();

		var uri = $(this).attr('href');

		$.get( uri, function( r ) {

			swal({
				title: "Domain added to cart!",
				text: r + "You can Checkout or Continue Shopping",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Checkout",
				cancelButtonText: "Continue Shopping",
				closeOnConfirm: true,
				closeOnCancel: true,
				imageUrl: '/image/cart.webp' ,
				html: true
			}, function(isConfirm) {
				if (isConfirm) {
					document.location.href = '/checkout';
				}
			});

		}).fail(function(xhr, status, error) {
				swal({ title: 'woops', text: error, type: "warning",  }); // or whatever
		});

		return false;

	});


	// remove from cart
	$('.cart-remove').click(function(ev) {
		ev.preventDefault();

		var removeUri = $(this).attr('href');

		swal({
			title: "Are you sure?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, remove it!",
			closeOnConfirm: false
		}, function(){
			document.location.href = removeUri;
		});

		return false;
	});


	$('.paypalSubmit').click(function() {
		swal({
			title: "Redirecting you to PayPal...",
			text: 'It takes just a few seconds.',
			timer: 10000,
			showConfirmButton: false,
			imageUrl: '/image/ajax.webp'
		});
	});

	$( '#make-offer' ).submit(function( ev ) {
		ev.preventDefault();

		var formData = $( this ).serialize();

		$.ajax({
				type: 'post',
				url: '/make-offer',
				data: formData,
				dataType: 'json',
				success: function(data){
					$( '.make-offer-result' ).html( data.message );
				},
				error: function(data) {

					var errors = data.responseJSON;
					errorsHtml = '<br /><div class="alert alert-danger"><ul>';

					$.each( errors, function( key, value ) {
							errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
					});

					errorsHtml += '</ul></div>';

					$( '.make-offer-result' ).html( errorsHtml );

				}
			});

		return false;
	});

	$( '#make-financing' ).submit(function( ev ) {
		ev.preventDefault();

		var formData = $( this ).serialize();

		$.ajax({
				type: 'post',
				url: '/make-financing',
				data: formData,
				dataType: 'json',
				success: function(data){
					$( '.make-financing-result' ).html( data.message );
				},
				error: function(data) {

					var errors = data.responseJSON;
					errorsHtml = '<br /><div class="alert alert-danger"><ul>';

					$.each( errors, function( key, value ) {
							errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
					});

					errorsHtml += '</ul></div>';

					$( '.make-financing-result' ).html( errorsHtml );

				}
			});

		return false;
	});

});

$(function() {
		 var totalrowshidden;
var rows2display=20;
var rem=0;
var rowCount=0;
var forCntr;
var forCntr1;
var MaxCntr=0;


$('#show').click(function() {
		rowCount = $('#jsWebKitTable tr').length;

		MaxCntr=forStarter+rows2display;

		 if (forStarter<=$('#jsWebKitTable tr').length)
				{

				for (var i = forStarter; i < MaxCntr; i++)
				{
					 $('#jsWebKitTable tr:nth-child('+ i +')').show(200);
				}

				forStarter=forStarter+rows2display

				 }
		else
		{
		 $('#show').hide();
		 }



			 });



 $(document).ready(function() {
 var rowCount = $('#jsWebKitTable tr').length;


 for (var i = $('#jsWebKitTable tr').length; i > rows2display; i--) {
	rem=rem+1
				 $('#jsWebKitTable tr:nth-child('+ i +')').hide(200);

				 }
forCntr=$('#jsWebKitTable tr').length-rem;
forStarter=forCntr+1

	 });


 });


 $(function() {
		 var totalrowshidden;
var rows2display=20;
var rem=0;
var rowCount=0;
var forCntr;
var forCntr1;
var MaxCntr=0;


$('#show1').click(function() {
		rowCount = $('#jsWebKitTable1 tr').length;

		MaxCntr=forStarter+rows2display;

		 if (forStarter<=$('#jsWebKitTable1 tr').length)
				{

				for (var i = forStarter; i < MaxCntr; i++)
				{
					 $('#jsWebKitTable1 tr:nth-child('+ i +')').show(200);
				}

				forStarter=forStarter+rows2display

				 }
		else
		{
		 $('#show1').hide();
		 }



			 });



 $(document).ready(function() {
 var rowCount = $('#jsWebKitTable1 tr').length;


 for (var i = $('#jsWebKitTable1 tr').length; i > rows2display; i--) {
	rem=rem+1
				 $('#jsWebKitTable1 tr:nth-child('+ i +')').hide(200);

				 }
forCntr=$('#jsWebKitTable1 tr').length-rem;
forStarter=forCntr+1

	 });


 });

	$(function() {
		 var totalrowshidden;
var rows2display=20;
var rem=0;
var rowCount=0;
var forCntr;
var forCntr1;
var MaxCntr=0;


$('#show3').click(function() {
		rowCount = $('#jsWebKitTable3 tr').length;

		MaxCntr=forStarter+rows2display;

		 if (forStarter<=$('#jsWebKitTable3 tr').length)
				{

				for (var i = forStarter; i < MaxCntr; i++)
				{
					 $('#jsWebKitTable3 tr:nth-child('+ i +')').show(200);
				}

				forStarter=forStarter+rows2display

				 }
		else
		{
		 $('#show3').hide();
		 }



			 });



 $(document).ready(function() {
 var rowCount = $('#jsWebKitTable3 tr').length;


 for (var i = $('#jsWebKitTable3 tr').length; i > rows2display; i--) {
	rem=rem+1
				 $('#jsWebKitTable3 tr:nth-child('+ i +')').hide(200);

				 }
forCntr=$('#jsWebKitTable3 tr').length-rem;
forStarter=forCntr+1

	 });


 });


 $(function () {
		$(".view_more_product").slice(0, 16).show();
		$("body").on('click touchstart', '.load-more', function (e) {
			e.preventDefault();
			$(".view_more_product:hidden").slice(0, 16).slideDown();
			if ($(".view_more_product:hidden").length == 0) {
				$(".load-more").css('visibility', 'hidden');
			}
			$('html,body').animate({
				scrollTop: $(this).offset().top
			}, 1000);
		});
	});


$(document).ready(function(){

 $('.damain_name').click(function(){

	 var domain_name = $(this).data('domain');
	 var id = $(this).data('id');
	 console.log(domain_name);
	 // AJAX request
	 $(".offer_model").html(domain_name+' Offer' );
	 $("#domainId").val(id);
 });
});


$(document).ready(function(){

 $('.offer').click(function(){

	 var domain_name = $(this).data('domain');
	 var amount = $(this).data('amount');
	 var installment = $(this).data('installment');
	 document.getElementById("credit-card").href="/checkout/credit-card?domain="+domain_name;
	 document.getElementById("paypal-card").href="/checkout/paypal?domain="+domain_name;
	 document.getElementById("escrow-card").href="/checkout/escrow?domain="+domain_name;
	 console.log(domain_name);
	 // AJAX request
	 $(".header-model").html(domain_name+' Offer' );
	 $(".amount-model").html(amount);
	 $(".installment-amount").html(installment+'/month');
 });
});

$(document).ready(function(){

 $('.sold_domain').click(function(){

	 var domain_name = $(this).data('domain');
	 var price = $(this).data('price');
	 var description = $(this).data('description');
	 var register = $(this).data('register');
	 var registrar = $(this).data('registrar');
	 var age = $(this).data('age');
	 var category = $(this).data('category');
	 console.log(domain_name);
	 // AJAX request
	 $(".sold-model").html(domain_name+' SOLD' );
	 $(".sold-domain").html(domain_name);
	 $(".sold-price").html(price);
	 $(".descrip").html(description);
	 $(".register-date").html(register);
	 $(".registrar").html(registrar);
	 $(".age").html(age);
	 $(".is_premiumss").html(category);
 });
});

$(document).ready(function(){

	$('.price_drop_popup').click(function(){

		var domain_name = $(this).data('domain');
		var domain_id = $(this).data('id');
		var pricing = $(this).data('pricing');
		console.log(domain_id);
		// AJAX request
		$(".header-model").html(domain_name+' Price Drop' );
		$("#domain_id").val(domain_id);
		$("#pricing").val(pricing);
	});
	 });


$(function () {
	$('#datetimepicker1').datetimepicker({
		format: 'YYYY-MM-DD HH:mm:ss',
		minDate:new Date()
	});

});

$(function () {
	$('#datetimepicker2').datetimepicker({
		format: 'YYYY-MM-DD HH:mm:ss',
	});

});

$(function () {


	$("#saveBtn").click(function(e){

		e.preventDefault();
		var domain_id = $('#domain_id').val();;
		console.log(domain_id);
		var start_datetime = $('#start_datetime').val();
		var pricing = $('#pricing').val();
		var price_drop_value = $('#price_drop_value').val();
		var CSRF_TOKEN = $('input[name="_token"]').val();
		$.ajax({
			url:  '/domain/postinsert',
			method:"POST",
			data: { _token: CSRF_TOKEN,
					domain_id: domain_id,
					start_datetime: start_datetime,
					price_drop_value: price_drop_value,
					pricing: pricing
				},
			success: function (data) {

				$('#CustomerForm').trigger("reset");
				$('#myModal2').modal('hide');
				location.reload();

			},
			error: function (data) {
				console.log('Error:', data);
				$('#saveBtn').html('Save Changes');
			}
		});
	});
	});

	//premium domain4
$(document).ready(function(){

	$('.premium_popup').click(function(){

		var domain_name = $(this).data('domain');
		var domain_id = $(this).data('id');
		console.log(domain_id);
		// AJAX request
		$(".header-model").html(domain_name);
		$("#domain_id").val(domain_id);
	});
	 });

	 $(function () {


	$("#savepremium").click(function(e){

		e.preventDefault();
		var domain_id = $('#domain_id').val();
		var is_premium = $('#is_premium option:selected').val()
		var CSRF_TOKEN = $('input[name="_token"]').val();
		$.ajax({
			url:  '/domain/update_premium_data',
			method:"POST",
			data: { _token: CSRF_TOKEN,
					domain_id: domain_id,
					is_premium: is_premium
				},
			success: function (data) {

				$('#CustomerForm').trigger("reset");
				$('#myModal2').modal('hide');
				location.reload();

			},
			error: function (data) {
				console.log('Error:', data);
				$('#saveBtn').html('Save Changes');
			}
		});
	});
	});


	$(document).ready(function () {

		(function ($) {

			$('#filter').keyup(function () {

				var rex = new RegExp($(this).val(), 'i');
				$('.searchable tr').hide();
				$('.searchable tr').filter(function () {
					return rex.test($(this).text());
				}).show();

			})

		}(jQuery));

	});

	$(document).ready(function(){

		$('.offer_listing').click(function(){

			var domain_name = $(this).data('domain_name');
			var username = $(this).data('name');
			var phone = $(this).data('phone');
			var email = $(this).data('email');
			var offer = $(this).data('offer');
			var remark = $(this).data('remark');
			// AJAX request
			$(".modal-title").html(domain_name);
			$(".domains").html(domain_name);
			$(".username").html(username);
			$(".phone").html(phone);
			$(".email").html(email);
			$(".offer").html('$'+offer);
			$(".remark").html(remark);
		});
		 });


