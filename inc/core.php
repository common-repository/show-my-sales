<?php
// main plugin code starts here....
function msms_cur()
{
	global $msms;
	switch($msms->get_option('cur'))
	{
		case "i":
			return '<span class="WebRupee">Rs.</span>';
			break;
		
		case "d":
			return "$";
			break;
		
		case "p":
			return "&pound;";
			break;
		
		case "y":
			return "&yen;";
			break;
		
		case "e":
			return "&euro;";
			break;
	}
	return '$';
}


function msms_detect_ecommerce_plugin()
{
	$msms_ecommer_plugins_installed = array();
	if(class_exists('WP_eCommerce'))
	{
		$msms_ecommer_plugins_installed['wpec']='WP E-Commerce';
	}
	else
	{
		$msms_ecommer_plugins_installed['none']='No Ecommerce Plugin Found, Or Installed E-commerce plugin not supported';
	}
	return $msms_ecommer_plugins_installed;
}

function msms_is_mobile()
{
	require_once 'mobile_detect.php';
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	if ($deviceType=='computer')
		return false;
	return true;
}

function msms_should_be_shown() 
{
	global $msms;
	if ( isset( $_SERVER[ 'HTTPS' ] ) && strtolower( $_SERVER['HTTPS'] ) == 'on' ) {
		$full_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	} else {
		$full_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}	

	$modified_url = str_replace( strtolower( home_url() ), '', strtolower( $full_url ) );
	$modified_url = str_replace( strtolower( $_SERVER['QUERY_STRING'] ), '', $modified_url );
	$modified_url = str_replace( '?', '', $modified_url );
	if ( ( rtrim( $modified_url, '/' ) === strtolower( rtrim( '/'.$msms->get_option('url').'/', '/' ) ) ))   
	{
		return true;	
	}
	return false;
}

add_action('init','msms_front_int');
function msms_front_int()
{
	global $msms;
	//init sms core functionality
	if (msms_should_be_shown() OR isset($_POST['msms_lkksdjfk_password']) OR isset($_GET['msms_lkksdjfk_logout'])) 
	{				
		session_start();
		if(isset($_POST['msms_lkksdjfk_password']) AND $_POST['msms_lkksdjfk_password']==$msms->get_option('password'))
		{
			$_SESSION['wpec_sales_tracking_mode']='on';
			wp_redirect(home_url('/'.$msms->get_option('url')));
			die();
		}
		elseif(isset($_GET['msms_lkksdjfk_logout']))
		{
			$_SESSION['wpec_sales_tracking_mode']='off';
			wp_redirect(home_url('/'.$msms->get_option('url')));
			die();
		}
		elseif(isset($_GET['switch-views']))
		{
			if($msms->get_option('old')=='on')
				$msms->update_option('old','off');
			else
				$msms->update_option('old','on');
			wp_redirect(home_url('/'.$msms->get_option('url')));
			die();
		}
		header( 'HTTP/1.1 200 OK' );
		include( $msms->includes . '/../inc/html.php' );
		die;	
	}
}
?>