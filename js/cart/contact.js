	$(document).ready(function(){





		initContact();




	}); 

	var messageDelay = 2000;

	function initContact() {


  		// Hide the form initially.
  		// Make submitForm() the form's submit handler.
  		// Position the form so it sits in the centre of the browser window.
  		$('#contactform').hide().submit( submitForm ).addClass( 'positioned' );

  		// When the "Send us an email" link is clicked:
  		// 1. Fade the content out
  		// 2. Display the form
  		// 3. Move focus to the first field
  		// 4. Prevent the link being followed

  		$('a[href="#contactformlink"]').click( function() {
    			$('#container').fadeTo( 'slow', .2 );
    			$('#contactform').fadeIn( 'slow', function() {
      				$('#senderName').focus();
    			} )

    			return false;
  		} );
  
  		// When the "Cancel" button is clicked, close the form
  		$('#cancel').click( function() { 
    			$('#contactform').fadeOut();
    			$('#container').fadeTo( 'slow', 1 );
  		} );  

  		// When the "Escape" key is pressed, close the form
  		$('#contactform').keydown( function( event ) {
    			if ( event.which == 27 ) {
      				$('#contactform').fadeOut();
      				$('#container').fadeTo( 'slow', 1 );
    			}
  		} );


	}

	function submitForm() {


  		var contactForm = $(this);

  		// Are all the fields filled in?



  		if ( !$('#senderName').val() || !$('#senderEmail').val() || !$('#senderMessage').val() ) {

    			// No; display a warning message and return to the form
    			$('#incompleteMessage').fadeIn().delay(messageDelay).fadeOut();
    			contactForm.fadeOut().delay(messageDelay).fadeIn();

  		} else {

    			$('#sendingMessage').fadeIn();
    			contactForm.fadeOut();


			var contactUrl = "contact/contactform.php?ajax=true";
	
    			$.ajax( {
      				url: contactUrl,
      				type: contactForm.attr( 'method' ),
      				data: contactForm.serialize(),
      				success: submitFinished
    			} );
  		}

  		// Prevent the default form submission occurring
  		return false;
	}


	// Handle the Ajax response

	function submitFinished( response ) {
  		response = $.trim( response );
  		$('#sendingMessage').fadeOut();

  		if ( response == "success" ) {

    			// Form submitted successfully:
    			// 1. Display the success message
    			// 2. Clear the form fields
    			// 3. Fade the content back in

    			$('#successMessage').fadeIn().delay(messageDelay).fadeOut();
    			$('#senderName').val( "" );
    			$('#senderEmail').val( "" );
    			$('#senderMessage').val( "" );

    			$('#container').delay(messageDelay+500).fadeTo( 'slow', 1 );

  		} else {

    			// Form submission failed: Display the failure message,
    			// then redisplay the form
    			$('#failureMessage').fadeIn().delay(messageDelay).fadeOut();
    			$('#contactForm').delay(messageDelay+500).fadeIn();
  		}
	}
