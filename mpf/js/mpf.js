// Wait DOM
	mpf_ced = false;

jQuery(document).ready(function($) {
	
	//jquery to prevent input otherthan numbers on input type number
	
	$('input[type="number"]').keydown(function(event) {
		//alert(event.keyCode);
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            ((event.keyCode == 65 || event.keyCode == 67 || event.keyCode == 86 ) && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });


	// ########## Tabs ##########

	// Nav tab click
	$('#mpf-plugin-tabs span').click(function(event) {
		// Hide tips
		$('.mpf-plugin-spin, .mpf-plugin-success-tip').hide();
		// Remove active class from all tabs
		$('#mpf-plugin-tabs span').removeClass('nav-tab-active');
		// Hide all panes
		$('.mpf-plugin-pane').hide();
		// Add active class to current tab
		$(this).addClass('nav-tab-active');
		// Show current pane
		$('.mpf-plugin-pane:eq(' + $(this).index() + ')').show();
		// Save tab to cookies
		mpfCreateCookie( pagenow + '_last_tab', $(this).index(), 365 );
	});

	// Auto-open tab by link with hash
	if ( mpfStrpos( document.location.hash, '#tab-' ) !== false )
		$('#mpf-plugin-tabs span:eq(' + document.location.hash.replace('#tab-','') + ')').trigger('click');
	// Auto-open tab by cookies
	else if ( mpfReadCookie( pagenow + '_last_tab' ) != null )
		$('#mpf-plugin-tabs span:eq(' + mpfReadCookie( pagenow + '_last_tab' ) + ')').trigger('click');
	// Open first tab by default
	else
		$('#mpf-plugin-tabs span:eq(0)').trigger('click');


	// ########## Ajaxed form ##########

	$('#mpf-plugin-options-form').ajaxForm({
		beforeSubmit: function() {
			$('.mpf-plugin-success-tip').hide();
			$('.mpf-plugin-spin').fadeIn(200);
			$('.mpf-plugin-submit').attr('disabled', true);
		},
		success: function() {
			$('.mpf-plugin-spin').hide();
			$('.mpf-plugin-success-tip').show();
			setTimeout(function() {
				$('.mpf-plugin-success-tip').fadeOut(200);
			}, 2000);
			$('.mpf-plugin-submit').attr('disabled', false);
		}
	});


	// ########## Reset settings confirmation ##########

	$('.mpf-plugin-reset').click(function() {
		if (!confirm($(this).attr('title')))
			return false;
		else
			return true;
	});


	// ########## Notifications ##########

	$('.mpf-plugin-notification').css({
		cursor: 'pointer'
	}).on('click', function(event) {
		$(this).fadeOut(100, function() {
			$(this).remove();
		});
	});


	// ########## Triggables ##########

	// Select
	$('tr[data-trigger-type="select"] select').each(function(i) {

		var // Input data
		name = $(this).attr('name'),
		index = $(this).find(':selected').index();

		//alert( name + ' - ' + index );

		// Hide all related triggables
		$('tr.mpf-plugin-triggable[data-triggable^="' + name + '="]').hide();

		// Show selected triggable
		$('tr.mpf-plugin-triggable[data-triggable="' + name + '=' + index + '"]').show();

		$(this).change(function() {

			index = $(this).find(':selected').index();

			// Hide all related triggables
			$('tr.mpf-plugin-triggable[data-triggable^="' + name + '="]').hide();

			// Show selected triggable
			$('tr.mpf-plugin-triggable[data-triggable="' + name + '=' + index + '"]').show();
		});
	});

	// Radio
	$('tr[data-trigger-type="radio"] .mpf-plugin-radio-group').each(function(i) {

		var // Input data
		name = $(this).find(':checked').attr('name'),
		index = $(this).find(':checked').parent('label').parent('div').index();

		// Hide all related triggables
		$('tr.mpf-plugin-triggable[data-triggable^="' + name + '="]').hide();

		// Show selected triggable
		$('tr.mpf-plugin-triggable[data-triggable="' + name + '=' + index + '"]').show();

		$(this).find('input:radio').each(function(i2) {

			$(this).change(function() {

				alert();

				// Hide all related triggables
				$('tr.mpf-plugin-triggable[data-triggable^="' + name + '="]').hide();

				// Show selected triggable
				$('tr.mpf-plugin-triggable[data-triggable="' + name + '=' + i2 + '"]').show();
			});
		});
	});


	// ########## Clickouts ##########

	$(document).on('click', function(event) {
		if(event.target.className.indexOf('media-button-insert')!== -1)
		{
			mpf_ced = true;
		}
		//alert(event);
		if ( $('.mpf-plugin-prevent-clickout:hover').length == 0 )
			$('.mpf-plugin-clickout').hide();
	});


	// ########## Upload buttons ##########

	$('.mpf-plugin-upload-button').click(function(event) {

		// Define upload field
		window.mpf_current_upload = $(this).attr('rel');

		// Show thickbox with uploader
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');

		// Prevent click
		event.preventDefault();
	});
	
	tempp = window.send_to_editor;
	window.send_to_editor = function(html) {
		if(mpf_ced===true)
		{
			mpf_ced = false;
			tempp(html);
		}
		else
		{
			var url;
			if ( jQuery(html).filter('img:first').length > 0 )
				url = jQuery(html).filter('img:first').attr('src');
			else
				url = jQuery(html).filter('a:first').attr('href');

			// Update upload textfield value
			$('#mpf-plugin-field-' + window.mpf_current_upload).val(url);

			// Hide thickbox
			tb_remove();
		
		}
		
	}


	// ########## Color picker ##########

	$('.mpf-plugin-color-picker-preview').each(function(index) {
		$(this).farbtastic('.mpf-plugin-color-picker-value:eq(' + index + ')');
		$('.mpf-plugin-color-picker-value:eq(' + index + ')').focus(function(event) {
			$('.mpf-plugin-color-picker-preview').hide();
			$('.mpf-plugin-color-picker-preview:eq(' + index + ')').show();
		});
	});

});


// ########## Cookie utilities ##########

function mpfCreateCookie(name,value,days){
	if(days){
		var date=new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires="; expires="+date.toGMTString()
	}else var expires="";
	document.cookie=name+"="+value+expires+"; path=/"
}
function mpfReadCookie(name){
	var nameEQ=name+"=";
	var ca=document.cookie.split(';');
	for(var i=0;i<ca.length;i++){
		var c=ca[i];
		while(c.charAt(0)==' ')c=c.substring(1,c.length);
		if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length,c.length)
	}
	return null
}


// ########## Strpos tool ##########

function mpfStrpos( haystack, needle, offset) {
	var i = haystack.indexOf( needle, offset );
	return i >= 0 ? i : false;
}