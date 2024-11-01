<?php $triggable = ( $option['triggable'] ) ? ' data-triggable="' . $option['triggable'] . '" class="mpf-plugin-triggable hide-if-js"' : ''; ?>
<tr<?php echo $triggable; ?>>
	<th scope="row"><label for="mpf-plugin-field-<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label></th>
	<td>
		<div class="mpf-plugin-color-picker">
			<input type="text" value="<?php echo $settings[$option['id']]; ?>" name="<?php echo $option['id']; ?>" id="mpf-plugin-field-<?php echo $option['id']; ?>" class="regular-text mpf-plugin-color-picker-value mpf-plugin-prevent-clickout" style="width:100px" />
			<span class="mpf-plugin-color-picker-preview mpf-plugin-clickout"></span>
		</div>
		<span class="description"><?php echo $option['desc']; ?></span>
	</td>
</tr>