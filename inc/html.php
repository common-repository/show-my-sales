<?php
if(!function_exists('add_action'))
	die("Smart Try!");
global $msms;
//echo "[".$msms->get_option('nonmobile')."]";die();
if($msms->get_option('nonmobile')!=='on')
{
	if(!msms_is_mobile())
	{
		?>
			<center>
			<div style='border:2px solid black;width:600px;height:456px;margin-top:100px;padding:5px;border-radius:5px;box-shadow: 5px 5px 5px #aaa;'>
				<img src='<?php echo $msms->assets('images','mobile_only.jpg'); ?>'>
			</div>
			</center>
		<?php
		die();
	}
}
$redirect_url = '/'.$msms->get_option('url');
include( $msms->includes . '/../inc/sms_class.php' );
//set default timezone to wordpress configured timezone
date_default_timezone_set(get_option('timezone_string'));
						

	if(isset($_GET['msms_start']))
	{
		$type = msms_timediff($_GET['msms_start'],$_GET['msms_end']);
		switch ($type)
		{
			case 'Week':
				$title = date('jS',$_GET['msms_start'])." to ".date('jS M, Y',$_GET['msms_end'])." Week";
				$title2 = date('jS',$_GET['msms_start'])." to ".date('jS M, Y',$_GET['msms_end']);
				break;
			case 'Month':
				$title = date('M-Y',$_GET['msms_start'])." Month";
				$title2 = date('M-Y',$_GET['msms_start'])."";
				break;
			case 'Year':
				$title = date('Y',$_GET['msms_start'])." Year";
				$title2 = date('Y',$_GET['msms_start'])."";
				break;
			case 'All':
				$title = "Full";
				$title2 = "";
				break;
			case 'Day':
				$title = date('jS M Y',$_GET['msms_start'])." Day";
				$title2 = date('jS M Y',$_GET['msms_start'])."";
				break;
		}
		$title .= ' Overview';
		
	}
	else
		$title = $msms->get_option("title");

?>
<html>
<head>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="<?php echo $msms->assets('images','favicon.ico'); ?>">
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<?php if ($msms->get_option('cur')=='i') { ?>
	<link rel="stylesheet" type="text/css" href="http://webrupee.com/font">
	<?php } ?>
	
	
	<?php if ($msms->get_option('old')=='on') { 
		//using old mobile so disable jquerymobile features..
		echo '<link rel="stylesheet" href="'.$msms->assets('css','mms.css').'" />';
	}
	else
	{
		echo '<link rel="stylesheet" href="'.$msms->assets('css','jquery.mobile-1.2.1.min.css').'" />';
		echo '<script src="'.includes_url('js/jquery/jquery.js').'"></script>';
		echo '<script src="'.includes_url('js/jquery/jquery-migrate.js').'"></script>';
		echo '<script src="'.$msms->assets('js','jquery.mobile-1.2.1.min.js').'"></script>';
	}
	?>
	
	
	</head>
<body>
<?php
if ($_SESSION['wpec_sales_tracking_mode']=='on')
{ ?>
<div data-role="page" class="page" data-title="<?php echo $title; ?>">

	<div data-role="header" class='header'>
		<h1><?php echo $msms->get_option("title"); ?></h1>
	</div><!-- /header -->
	
	<div data-role="content" class='content'>	
	
		<?php 
		//print_r($_GET);die();
		
			global $wpdb;
			
			$no_plugins="";
			if(file_exists($msms->includes . '/../inc/class_'.$msms->get_option('shop_plugin').'.php'))
				include( $msms->includes . '/../inc/class_'.$msms->get_option('shop_plugin').'.php' );
			else
			{
				$no_plugins = "<div class='error'><p>Sorry, I could not find any E-commerce Plugin installed on your site or the E-commerce plugin you are using is not supported by <strong>'Mindstien Show My Sales'</strong> plugin.<p>
				<p>Either install e-commerce plugin or <a href='http://mindstien.com/contact-us/' target='_blank'>Click Here and contact us</a> to request support for the ecommerce plugin you are using...</p>
				</div>";
				include( $msms->includes . '/../inc/class_blank.php' );
			}

			$obj = new msms_wpec;
			$msms_time = new msms_time;
			$msms_time->setup_date_time();
			
			if (isset($_GET['msms_start']) and isset($_GET['msms_end']))
			{ 
				//secho "posted";die();
				$type = msms_timediff($_GET['msms_start'],$_GET['msms_end']);
				switch ($type)
				{
					case 'Week':
						$results = msms_prepare_week_sales($_GET['msms_start'],$_GET['msms_end']);
						break;
					case 'Month':
						$results = msms_prepare_month_sales($_GET['msms_start'],$_GET['msms_end']);
						break;
					case 'Year':
						$results = msms_prepare_year_sales($_GET['msms_start'],$_GET['msms_end']);
						break;
					case 'All':
						$results = msms_prepare_all_year_sales($_GET['msms_start'],$_GET['msms_end']);
						break;
					case 'Day':
						$results = $obj->get_sales_between_dates($_GET['msms_start'],$_GET['msms_end']);
						break;
				}
				//print_r($results);
				if ($type=='Day')
				{
					//print_r($results);
					
					echo "<center><h1>".$type." Overview (".count($results)." Orders)</h1>";
					echo "<strong>$title2</strong></center>";
					echo '<div data-role="collapsible-set" data-theme="b" data-content-theme="d">';
					//print_r($results); die();
					$sr = count($results);
					foreach ($results as $k=>$v)
					{
						?>
						<div data-role="collapsible" class="collapsible" data-content-theme="c">
							<h3>(<?php echo "$sr";?>) <?php echo date('h:i A',$v->date) ?> <br/> <?php echo msms_cur().number_format($v->total_price,2)." (".count($v->sales)." Products)"; ?>
							</h3>
							
							<?php
							$ii = 1;
							foreach ($v->sales as $i)
							{	
								?>
								<h3><?php echo $ii; ?>. <?php echo $i->product; ?></h3> 
								<div class="ui-grid-a">
									<div class="ui-block-a"><strong>Quantity:</strong></div>
									<div class="ui-block-b"><strong><?php echo $i->quantity; ?></strong></div>
								</div>
								<div class="ui-grid-a">
									<div class="ui-block-a"><strong>Amount:</strong></div>
									<div class="ui-block-b"><strong><?php echo msms_cur().number_format($i->value,2); ?></strong></div>
								</div>
								<?php
								$ii++;
							}
							?>
						
							
							
						</div>
						<?php
						$sr--;
						
					}
					echo "</div>";
				}	
				else
				{
					echo "<center><h1>".$type." Overview</h1>";
					echo "<strong>$title2</strong></center>";
					//print_r($results); die();
					echo '<div class="collapsible-set" data-role="collapsible-set" data-theme="b" data-content-theme="d">';
					foreach ($results as $k=>$v)
					{
						?>
						<div data-role="collapsible" class="collapsible" data-content-theme="c">
							<h3><strong><?php echo $k; ?></strong><br/>(<?php echo msms_cur().number_format($v->total,2); ?>)</h3>
							
							<div class="ui-grid-a">
								<div class="ui-block-a"><strong>Total Sales:</strong></div>
								<div class="ui-block-b"><strong><?php echo number_format($v->count); ?></strong></div>
							</div>
							
							<div class="ui-grid-a">
								<div class="ui-block-a"><strong>Avg $/sale:</strong></div>
								<div class="ui-block-b"><strong><?php
										if ($v->count==0)
											echo msms_cur()."0";
										else
											echo msms_cur().number_format($v->total/$v->count,2);
										?></strong></div>
							</div>
							
							<div class="ui-grid-a">
								<div class="ui-block-a"><strong>Total:</strong></div>
								<div class="ui-block-b"><strong><?php echo msms_cur().number_format($v->total,2); ?></strong></div>
							</div>
							
							<a  data-role="button" class="button"  data-theme="c" href="<?php echo home_url($redirect_url); ?>?msms_start=<?php echo $v->start; ?>&msms_end=<?php echo $v->end; ?>">Look inside <?php echo $k; ?></a>
							
						</div>
					<?php
					}
					echo "</div>";
					$obj = new msms_wpec;
					$results = $obj->get_product_summary_between_dates( $_GET['msms_start'],$_GET['msms_end']);
						echo  "<h1>Best Sellers in this $type</h1>";
						echo  '<div data-role="collapsible-set" class="collapsible-set" data-theme="c" data-content-theme="d">';
						foreach ($results as $k=>$v)
						{
							?>
							<div data-role="collapsible" class="collapsible" data-content-theme="c"><h3><?php echo $k; ?></h3>
							<h3><?php echo $k; ?></h3>
							<div class="ui-grid-a">
								<div class="ui-block-a"><strong>Total Sales:</strong></div>
								<div class="ui-block-b"><strong><?php echo number_format($v->count,0); ?></strong></div>
							</div>
							<div class="ui-grid-a">
								<div class="ui-block-a"><strong>Avg $/Sale:</strong></div>
								<div class="ui-block-b"><strong><?php 
											if ($v->count==0)
												echo   msms_cur()."0";
											else
												echo   msms_cur().number_format($v->total/$v->count,2);?></strong></div>
							</div>
							<div class="ui-grid-a">
								<div class="ui-block-a"><strong>Total:</strong></div>
								<div class="ui-block-b"><strong><?php echo msms_cur().number_format($v->total,2); ?></strong></div>
							</div>
							<hr/>
							</div>
							<?php
						}
				}
				
				
			}
			else
			{
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
				echo "<h1>Overview</h1>";
				echo '<div data-role="collapsible-set" class="collapsible-set" data-theme="b" data-content-theme="d">';
				//print_r($results); die();
				foreach ($results as $k=>$v)
				{
					?>
					<div data-role="collapsible" class="collapsible" data-content-theme="c">
						<h3>
							<strong><?php echo $k; ?></strong><br/> (<?php echo msms_cur().number_format($v->total,2); ?>)
						</h3>
						
						<div class="ui-grid-a">
							<div class="ui-block-a"><strong>Total Sales:</strong></div>
							<div class="ui-block-b"><strong><?php echo number_format($v->count,0); ?></strong></div>
						</div>
						
						<div class="ui-grid-a">
							<div class="ui-block-a"><strong>Avg $/sale:</strong></div>
							<div class="ui-block-b"><strong><?php
									if ($v->count==0)
										echo msms_cur()."0";
									else
										echo msms_cur().number_format($v->total/$v->count,2);
									?></strong></div>
						</div>
						
						<div class="ui-grid-a">
							<div class="ui-block-a"><strong>Total:</strong></div>
							<div class="ui-block-b"><strong><?php echo msms_cur().number_format($v->total,2); ?></strong></div>
						</div>
						
						<a  data-role="button" class="button"  data-theme="c" href="<?php echo home_url($redirect_url); ?>?msms_start=<?php echo $v->start; ?>&msms_end=<?php echo $v->end; ?>">Look inside <?php echo $k; ?></a>
						
					</div>
					<?php
					
					
				}
				echo "</div>";
				$results = array();
				/* Data for 'This Month' */
				$results['This Month'] = $obj->get_product_summary_between_dates( $msms_time->date_time_helper['this-month'][0], $msms_time->date_time_helper['this-month'][1] );
				
				
				/* Data for 'This Year' */
				$results['This Year'] = $obj->get_product_summary_between_dates( $msms_time->date_time_helper['this-year'][0], $msms_time->date_time_helper['this-year'][1] );
				
				
				/* Data for 'All Time' */
				$results['All Time'] = $obj->get_product_summary_between_dates( 0, $msms_time->date_time_helper['today'][1] );
				
				echo  "<h1>Best Sellers</h1>";
				echo  '<div data-role="collapsible-set" class="collapsible-set" data-theme="c" data-content-theme="d">';
				//print_r($results);
				foreach ($results as $k=>$v)
				{
					?>
					<div data-role="collapsible" class="collapsible">
						<h3><?php echo $k; ?></h3>
						
					<?php
							foreach($v as $kk=>$vv)
							{
								?>
								<h3><?php echo $kk; ?></h3>
								
								<div class="ui-grid-a">
									<div class="ui-block-a"><strong>Orders:</strong></div>
									<div class="ui-block-b"><strong><?php echo $vv->count; ?></strong></div>
								</div>
								<div class="ui-grid-a">
									<div class="ui-block-a"><strong>Totals:</strong></div>
									<div class="ui-block-b"><strong><?php echo msms_cur().number_format($vv->total,2); ?></strong></div>
								</div>
								<hr/>
								<?php
							}
					?>
					</div>
					<?php
					
				}
				echo "</div>";	
			
			}
			
			
			
			?>
			
				<div data-role="collapsible" class="collapsible" data-content-theme="c">
					<h3>Help?</h3>
					<?php echo $no_plugins; ?>
					<p>This is Web Application to get real time sales data of your wordpress based E-Commerce site on your mobile/tablet device.</p>
					<p>For settings, login to your WP dashboard with administrator access and go to settings->Mindstien show my sales</p>
					<p><small>For quick help / support please contact us at http://www.mindstien.com/contact-us</small></p>
					<p><small>By: <a href='http://www.mindstien.com' target='_blank'>Mindstien Technologies</a></small></p>
					
				</div>
				
			
				<?php if(isset($_GET['msms_start'])){ ?>
				<div class="ui-grid-a">
					<div class="ui-block-a">
						<a  href="<?php echo home_url($redirect_url);?>" data-role="button" class="button">Home</a>
					</div>
					<div class="ui-block-b">
						<a  name="back" onclick="window.history.back()" data-role="button" class="button">Back</a>
					</div>
				</div>
				<?php } ?>
				<div class="ui-grid-a">
					<div class="ui-block-a">
						<a  href="<?php echo home_url($redirect_url.'?switch-views=true');?>" data-role="button" class="button" data-ajax="false">
							<?php
								if($msms->get_option('old')=='on')
									echo "Advanced View";
								else
									echo "Old View";
							?>
						</a>
					</div>
					<div class="ui-block-b">
						<a  href="<?php echo home_url($redirect_url.'?msms_lkksdjfk_logout=true'); ?>" data-role="button" class="button" data-ajax="false">Logout</a>
					</div>
				</div>
				<?php
		
	
	?>
	
	</div><!-- /content -->
	
	<div data-role="footer" class="footer">
		<h4>By: Mindstien Technologies</h4>
	</div><!-- /footer -->
	
</div><!-- /page -->
<?php

}
else
{
?>
<div data-role="page" class="page" data-ajax="false" data-title='Login'>

	<div data-role="header" class="header">
		<h1><?php echo $msms->get_option("title"); ?></h1>
	</div><!-- /header -->
	
	<div data-role="content" class="content">	

			<form  data-ajax="false" action='<?php echo home_url($redirect_url); ?>' method='post'>
				<center><h2>
				<?php 
				if(isset($_POST['msms_lkksdjfk_password']) AND $_POST['msms_lkksdjfk_password']!=$options['password'])
				{
					echo "<span style='color:red;'>Invalid password, please try again !</span><br>";
				}
				?>
				<span style='color:green;'>Enter Password</span></h2></center>
				<input type='password' name='msms_lkksdjfk_password' value='' />
				<input type='submit'  data-ajax="false" name='submit' value='Login'>
			</form>
	</div><!-- /content -->
	
	<div data-role="footer" class="footer">
		<h4>By: Mindstien Technologies</h4>
	</div><!-- /footer -->
</div><!-- /page two -->
<?php } ?>

</body>
</html>