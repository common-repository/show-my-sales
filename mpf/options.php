<?php
	$msms_options = array(
		array( 'name' => __( 'Overview', $msms->textdomain ), 'type' => 'opentab' ),
		array( 'type' => 'about' ),
		array( 'type' => 'closetab', 'actions' => false ),
		array(
			'name' => __( 'Settings', $msms->textdomain ),
			'type' => 'opentab'
		),
		array(
			'name' => __( 'Title of WebApp:', $msms->textdomain ),
			'desc' => __( 'Title, that will be displayed on top of the webapp', $msms->textdomain ),
			'std' => get_bloginfo('name'),
			'id' => 'title',
			'type' => 'text'
		),
		array(
			'name' => __( 'URL:', $msms->textdomain ),
			'desc' => __( 'URL to access the stats on your mobile device, to access at http://www.yoursite.com/stats/ inserts "stats" above', $msms->textdomain ),
			'std' => 'msms',
			'id' => 'url',
			'type' => 'text'
		),
		array(
			'name' => __( 'Currency Symbol:', $msms->textdomain ),
			'desc' => __( 'Select which currency symbol you want to see on sales data, this just for display, has nothing to do with currency conversion or calculations', $msms->textdomain ),
			'std' => 'd',
			'id' => 'cur',
			'options' => array(
						'd'=>'Dollar ($)',
						'y'=>'Yen (&yen;)',
						'p'=>'Pound (&pound;)',
						'e'=>'Euro (&euro;)',
						'i'=>'Indian Rupee (Rs. (symbol))',
					),
			'type' => 'select'
		),
		array(
			'name' => __( 'Select your E-Commerce Plugin', $msms->textdomain ),
			'desc' => __( 'Which e-commerce plugin you are using for your online store?', $msms->textdomain ),
			'std' => 'wpec',
			'id' => 'shop_plugin',
			'type' => 'select',
			'options' => msms_detect_ecommerce_plugin()
		),
		array(
			'name' => __( 'Password', $msms->textdomain ),
			'desc' => __( 'Enter your password above, you need to login with this password to see stats on mobile devices', $msms->textdomain ),
			'std' => '1234',
			'id' => 'password',
			'type' => 'text',
		),
		array(
			'name' => __( 'Allow Computer Browser Also.', $msms->textdomain ),
			'desc' => __( 'Mark this to enable stats on computer browser also, by default stats url will only work on mobile devices.', $msms->textdomain ),
			'std' => on,
			'id' => 'nonmobile',
			'type' => 'checkbox',
			'label' => __('Yes! Let me see on computer browser also',$msms->textdomain)
		),
		array(
			'name' => __( 'Number of Best Seller Items', $msms->textdomain ),
			'desc' => __( 'This will display the sayed number of best sellter items for the period of time you are seeing, i.e. if you are on month overview page, and configured 10 items here, you will see top 10 items sold during that month period', $msms->textdomain ),
			'std' => 10,
			'id' => 'bestseller',
			'type' => 'number'
		),
		array(
			'name' => __( 'Enable Basic Template', $msms->textdomain ),
			'desc' => __( 'By default, webapp that you\'ll load in mobile browser is developed on JQuery Mobile platform which may be heavy in size if your are using older browser or having slow internet data connection. By checking this on, it will disable all advanced jquery mobile features and will displayed performance optimized lowest possible size webapp for better view in older browser and on slow data connection too', $msms->textdomain ),
			'std' => 'off',
			'id' => 'old',
			'type' => 'checkbox',
			'label' => 'Yes! I am using old mobile or having slow data (internet) speed..'
		),
		array( 'type' => 'closetab' ),
	);