<h1>MemberPress ThriveCart</h1>
<h2>API Credentials</h2>

<?php require WPPL_PATH . '/app/views/partials/credentials.php'; ?>

<?php if(MPTC_ThriveCart_Controller::tc_api_valid()): ?>
<h2>Product Mapping</h2>
<?php
    require WPPL_PATH . '/app/views/partials/mapping.php';
endif;