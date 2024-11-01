<?php $triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="mpf-plugin-triggable hide-if-js"' : ''; ?>
<tr<?php echo $triggable; ?>>
	<th scope="row"><label for="mpf-plugin-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td>
	
		<?php
		$settings = array(
				'wpautop'=>true,
				'media_buttons'=>$option['media'],
				'textarea_rows'=>$option['rows'],
				'teeny'=>false,
			);
		wp_editor( stripslashes( $settings[$option['id']] ), $option['id'], $settings );
		?>
		<p class="description"><?php echo $option['desc']; ?></p>
	</td>
</tr>