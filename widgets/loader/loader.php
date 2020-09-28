<?php
$options = get_widget_options();

?>
<script>

    function showLoader() {
        $('[role="submit"]').hide();
        $('[role="loader"]').show();
    }
    function hideLoader() {
        $('[role="submit"]').show();
        $('[role="loader"]').hide();
    }

</script>
<div class="my-5" role="loader" style="display: none;">
	<div class="d-flex justify-content-center">
		<div class="spinner"></div>
		<div class="ml-3"><?=tr($options['tr'])?></div>
	</div>
</div>