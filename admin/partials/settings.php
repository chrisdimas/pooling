<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://indie.systems/pooling-plugin
 * @since      1.0.0
 *
 * @package    PLG
 * @subpackage PLG/admin/partials
 */
?>
<?php
if (isset($_POST['submit']) && wp_verify_nonce($_POST['_wpnonce'], 'pooling-settings') ) {
	update_option('pooling_verification_method',sanitize_text_field($_POST['pooling_verification_method']));
	update_option('pooling_couchdb_url',($_POST['pooling_couchdb_url']));
	update_option('pooling_couchdb_db',($_POST['pooling_couchdb_db']));
	update_option('pooling_twilio_sid',($_POST['pooling_twilio_sid']));
	update_option('pooling_twilio_token',($_POST['pooling_twilio_token']));
	update_option('pooling_gmaps_api',($_POST['pooling_gmaps_api']));
} else {
	error_log('asd');
}
$verification_method = get_option('pooling_verification_method','email');
 ?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap" style="float:left;clear:left">
	<h1><?php echo __('Settings', 'syncdrop'); ?></h1>
	<table class="form-table widefat tools-table">
		<form method="post" action="" >
		<tbody>
			<tr>
				<th><?php _e('Verification method', 'pooling');?>:</th>
				<td>
					<select name="pooling_verification_method">
						<option value="sms" <?php selected($verification_method, 'sms');?>>SMS</option>
						<option value="email" <?php selected($verification_method, 'email');?>>e-mail</option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php _e('CouchDB URL', 'pooling');?>:</th>
				<td>
					<input type="url" name="pooling_couchdb_url" value="<?php echo get_option('pooling_couchdb_url', null);?>">
				</td>
			</tr>
			<tr>
				<th><?php _e('CouchDB database name', 'pooling');?>:</th>
				<td>
					<input type="text" name="pooling_couchdb_db" value="<?php echo get_option('pooling_couchdb_db', null);?>">
				</td>
			</tr>
			<tr>
				<th><?php _e('Twilio sid', 'pooling');?>:</th>
				<td>
					<input type="text" name="pooling_twilio_sid" value="<?php echo get_option('pooling_twilio_sid', null);?>">
				</td>
			</tr>
			<tr>
				<th><?php _e('Twilio token', 'pooling');?>:</th>
				<td>
					<input type="text" name="pooling_twilio_token" value="<?php echo get_option('pooling_twilio_token', null);?>">
				</td>
			</tr>
			<tr>
				<th><?php _e('Google Maps API Key', 'pooling');?>:</th>
				<td>
					<input type="text" name="pooling_gmaps_api" value="<?php echo get_option('pooling_gmaps_api', null);?>">
				</td>
			</tr>
			<tr>
				<td>
					<?php
						wp_nonce_field('pooling-settings');
						submit_button(__('Submit'));
					?>
				</td>
			</tr>
		</tbody>
		</form>
	</table>
</div>
