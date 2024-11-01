<?php global $msms; 
$shop_plugin  = $msms->get_option('shop_plugin');
$msms_ecommer_plugins_installed = msms_detect_ecommerce_plugin();

if(isset($msms_ecommer_plugins_installed['none']) or !isset($msms_ecommer_plugins_installed[$shop_plugin]))
{
	echo "<div class='error'><p>Sorry, I could not find any E-commerce Plugin installed on your site or the E-commerce plugin you are using is not supported by <strong>'Mindstien Show My Sales'</strong> plugin.<p>
	<p>Either install e-commerce plugin or <a href='http://mindstien.com/contact-us/' target='_blank'>Click Here and contact us</a> to request support for the ecommerce plugin you are using...</p>
	</div>";
}

//prepare information
date_default_timezone_set(get_option('timezone_string'));
include( $msms->includes . '/../inc/sms_class.php' );

if(file_exists($msms->includes . '/../inc/class_'.$msms->get_option('shop_plugin').'.php'))
	include( $msms->includes . '/../inc/class_'.$msms->get_option('shop_plugin').'.php' );
else
	include( $msms->includes . '/../inc/class_blank.php' );
$obj = new msms_wpec;
$msms_time = new msms_time;
$msms_time->setup_date_time();

$results = array();
/* Data for 'Today' */
$results['Today'] = $obj->get_summary_between_dates( $msms_time->date_time_helper['today'][0], $msms_time->date_time_helper['today'][1] );


/* Data for 'This Week' */
$results['This Week'] = $obj->get_summary_between_dates( $msms_time->date_time_helper['this-week'][0], $msms_time->date_time_helper['this-week'][1] );


/* Data for 'This Month' */
$results['This Month'] = $obj->get_summary_between_dates( $msms_time->date_time_helper['this-month'][0], $msms_time->date_time_helper['this-month'][1] );


/* Data for 'This Year' */
$results['This Year'] = $obj->get_summary_between_dates( $msms_time->date_time_helper['this-year'][0], $msms_time->date_time_helper['this-year'][1] );


/* Data for 'All Time' */
$results['All Time'] = $obj->get_summary_between_dates( 0, $msms_time->date_time_helper['today'][1] );
$overview = "";
//print_r($results); die();
foreach ($results as $k=>$v)
{
	$overview .=  "<span style='width:100px;display:inline-block;'>$k</span>: &nbsp;  &nbsp;  &nbsp;  &nbsp; <strong>".msms_cur().number_format($v->total,2)."</strong><br />";
}
$results = array();
/* Data for 'This Month' */
$results['This Month'] = $obj->get_product_summary_between_dates( $msms_time->date_time_helper['this-month'][0], $msms_time->date_time_helper['this-month'][1] );


/* Data for 'This Year' */
$results['This Year'] = $obj->get_product_summary_between_dates( $msms_time->date_time_helper['this-year'][0], $msms_time->date_time_helper['this-year'][1] );


/* Data for 'All Time' */
$results['All Time'] = $obj->get_product_summary_between_dates( 0, $msms_time->date_time_helper['today'][1] );
$bestseller =   "";

foreach ($results as $k=>$v)
{
	$bestseller .="<h3>$k Best Sellers</h3><ol>";
	foreach ($v as $kk=>$vv)
	{
		$bestseller .= "<li>$kk <i>(sales: $vv->count, Total: ".msms_cur().$vv->total." )</i></li>";
	}
	$bestseller .="</ol><br />";
}




$url = home_url()."/".$msms->get_option('url');
//include_once($msms->includes."/phpqrcode.php");
?>
<div class="mpf-plugin-onehalf">
	<h3><?php _e( 'Quick Overview', $this->textdomain ); ?></h3>
	<p><?php echo $overview; ?></p>
	<h3><?php _e( 'Best Sellers', $this->textdomain ); ?></h3>
	<p><?php echo $bestseller; ?></p>
	
</div>
<div class="mpf-plugin-onehalf">
	<h3><?php _e( 'How to use this plugin.', $this->textdomain ); ?></h3>
	<p>Your current URL is:<br><a href='<?php echo $url; ?>' target="_blank"><?php echo $url; ?></a> (Refresh page if settings changed)</p>
	<p>To access this, visit the URL in mobile Safari on iOS devices, or the web browser on Android Mobiles.</p>
	<h3>Scan following QR Code in mobile to open in mobile browser</h3>
	<p><img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?php echo $url; ?>&choe=UTF-8"></p>
</div>
<div class="mpf-plugin-clear"></div>