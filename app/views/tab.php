<?php
    $user_id = get_current_user_id();
?>
<div class="mp-wrapper">
    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" name="mptc-cancel-button">
        <input type="hidden" name="action" value="mptc-cancel">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('mptc-cancel'); ?>">
        <input type="submit" value="Cancel Subscription">
    </form>

    <?php if(MPTC_ThriveCart_Controller::is_paused($user_id)): ?>

    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" name="mptc-resume-button">
        <input type="hidden" name="action" value="mptc-resume">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('mptc-resume'); ?>">
        <input type="submit" value="Resume Subscription">
    </form>

    <?php else: ?>

    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" name="mptc-pause-button">
        <input type="hidden" name="action" value="mptc-pause">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('mptc-pause'); ?>">
        <input type="submit" value="Pause Subscription">
    </form>

    <?php endif; ?>
</div>