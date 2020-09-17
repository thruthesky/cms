<?php
if ( ! loggedIn() ) return;
?>


<div class="alert alert-secondary">
	logged in with <?=login(SOCIAL_LOGIN)?>
	<a class="btn btn-primary btn-sm" href="/?page=user.logout">logout</a>
</div>
