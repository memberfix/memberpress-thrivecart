<div class="wppl-message-container-2 wppl-d-none">
    <div class="wppl-notice-2">
        <p id="wppl-notice-message-2"></p>
    </div>
</div>

<form id="wppl-mapping">
    <input type="hidden" name="action" value="wppl_products_mapping">
    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wppl_products_mapping'); ?>" id="wppl-mapping-nonce">

	<div class="mptc-settings-row">
		<div class="mptc-settings-col">
			<p>MemberPress Membership</p>
		</div>
		<div class="mptc-settings-col"></div>
		<div class="mptc-settings-col">
			<p>ThriveCart Product ID</p>
		</div>
	</div>

	<?php
	$memberships = MeprProduct::get_all();

	foreach($memberships as $membership){
		?>
		<div class="mptc-settings-row">
			<div class="mptc-settings-col">
				<p><?php echo $membership->post_title; ?></p>
			</div>
			<div class="mptc-settings-col">
				=>
			</div>
			<div class="mptc-settings-col">
				<input type="text" id="<?php echo $membership->ID; ?>" name="<?php echo $membership->ID; ?>" <?php
                    if(MPTC_Mapping_Controller::is_mepr_product_in_sync($membership->ID)){
                        echo 'value="' . MPTC_Mapping_Controller::retrieve_tc_paired_product($membership->ID) . '"';
                    }
                ?>>
			</div>
		</div>
		<?php
	}
	?>

    <div class="wppl-btn-wrapper">
        <input type="submit" class="button button-primary mptc-settings-btn" value="Save Mapping" id="mptc-settings-save">
        <div class="wppl-loader wppl-d-none" id="wppl-loader"></div>
    </div>
</form>