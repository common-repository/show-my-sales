<?php $triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="mpf-plugin-triggable hide-if-js"' : ''; ?>
<tr<?php echo $triggable; ?>>
	<th scope="row"><label for="mpf-plugin-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td>
		<textarea name="<?php echo $option['id']; ?>" id="mpf-plugin-field-<?php echo $option['id']; ?>" class="regular-text mpf-plugin-code" rows="<?php echo ( isset( $option['rows'] ) ) ? $option['rows'] : 5; ?>" placeholder="<?php _e( 'Insert code', $this->textdomain ); ?>"><?php echo stripslashes( $settings[$option['id']] ); ?></textarea>
		<span class="description"><?php echo $option['desc']; ?></span>
	</td>
</tr>