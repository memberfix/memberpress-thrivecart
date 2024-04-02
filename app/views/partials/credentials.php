<?php
$action = 'mptc-settings-form';
$nonce = wp_create_nonce($action);
?>

<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
	<input type="hidden" name="action" value="<?php echo $action; ?>">
	<input type="hidden" name="mptc_nonce" value="<?php echo $nonce ?>">
	<div>
		<label class="mptc-settings-label" for="mptc-api-key">API Key:</label>
		<input class="mptc-settings-text-field" type="text" id="mptc-api-key" name="mptc-api-key" <?php if(get_option('mptc-api-key') && !empty(get_option('mptc-api-key'))) echo 'value="' . get_option('mptc-api-key') . '"'; ?> required>
	</div>
    <div style="margin-top:10px;">
        <p>Webhook URL:</p>
        <p><?php echo get_bloginfo('wpurl'); ?>/wp-json/memberfix/v1/thrivecart</p>
    </div>
	<div style="margin-top:10px;">
		<label class="mptc-settings-label" for="mptc-webhook-secret">Webhook Secret:</label>
		<input style="display:block;" type="text" id="mptc-webhook-secret" name="mptc-webhook-secret" <?php if(get_option('mptc-webhook-secret') && !empty(get_option('mptc-webhook-secret'))) echo 'value="' . get_option('mptc-webhook-secret') . '"'; ?> required>
	</div>
    <div style="margin-top:10px;">
        <label class="mptc-settings-label" for="mptc-mode">Mode:</label>
        <select style="display:block;" type="text" id="mptc-mode" name="mptc-mode" required>
            <option value="test" <?php if(get_option('mptc-mode') && get_option('mptc-mode') == 'test') echo 'selected'; ?>>Test</option>
            <option value="live" <?php if(get_option('mptc-mode') && get_option('mptc-mode') == 'live') echo 'selected'; ?>>Live</option>
        </select>
    </div>
	<input type="submit" class="button button-primary mptc-settings-btn" value="Save Credentials">
</form>