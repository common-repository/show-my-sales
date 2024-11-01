<?php do_action( 'mpf_page_before' ); ?>
<div style='width:100%;height:100px;'>
		<div id='mpf-logo'></div>
		<h2 id='mpf-title'><?php echo $this->name; ?></h2>
		<p><?php echo $this->meta['Description']; ?></p>
		<div style='clear:both'></div>
		<p style='float:right;margin-right:20px;'><a href='http://mindstien.com/products/' target='_blank'>Click Here To See Our Latest Wordpress Products</a></p>
	</div>
	
<div id="mpf-plugin-settings" class="wrap">
	<h2 id="mpf-plugin-tabs" class="nav-tab-wrapper hide-if-no-js">
		<?php
			// Show tabs
			$this->render_tabs();
		?>
	</h2>
	<?php
		// Show notifications
		$this->notifications( array( 'js' => __( 'For full functionality of this page it is reccomended to enable javascript.', $this->textdomain ),
		                             'reseted' => __( 'Settings reseted successfully', $this->textdomain ),
		                             'not-reseted' => __( 'There is already default settings', $this->textdomain ),
		                             'saved' => __( 'Settings saved successfully', $this->textdomain ),
		                             'not-saved' => __( 'Settings not saved, because there is no changes', $this->textdomain ) ) );
	?>
	<form action="<?php echo $this->admin_url; ?>" method="post" id="mpf-plugin-options-form">
		<?php
			// Show options
			$this->render_panes();
		?>
		<input type="hidden" name="action" value="save" />
	</form>
</div>
<?php do_action( 'mpf_page_after' ); ?>
