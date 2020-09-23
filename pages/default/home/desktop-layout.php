<?php
	$options = get_page_options();
?>
<div class="top l-center mt-3 d-flex justify-content-end">
	<a class="link d-inline-block fs-sm" href="#"><i class="fa fa-edit mr-2"></i><?=tr(['en' => 'Greetings', 'ko' => '가입인사'])?></a>
    <a class="link d-inline-block pl-3 fs-sm" href="#"><i class="fa fa-comments mr-2"></i><?=tr(['en' => 'Chatting', 'ko' => '채팅방입장'])?></a>
</div>
<div class="header l-center d-flex">
    <div class="logo-search l-content-width py-3 d-flex">
        <img class="h-70" src="<?=PAGE_URL?>/img/icons/flutter-icon.png">
        <div class="ml-3 mt-2">
            <div class="fs-xs">소통하면서 배우는</div>
            <div class="fs-xl">플러터 커뮤니티</div>
        </div>
        <div class="ml-4 mt-2 flex-grow-1 position-relative">
                <input class="p-2 w-100 fs-lg border-w-2 border-darkgray" name="searchKey" value="">
                <i class="fa fa-search position-absolute pt-2 top-xs right-md fs-xl fa-darkgray"></i>
        </div>
    </div>
    <div class="flex-grow-1 my-3 ml-space-lg bg-lighter">
        <div class="p-3">
            Banner
        </div>
    </div>
</div>
<div class="mainmenu l-center bg-dark text-white">
    .mainmenu
</div>

<div class="container px-0">
	<div class="row no-gutters">
		<div class="col">
			<main class="mr-lg-4">
				<?php
				include $options['page_script'];
				?>
			</main>
		</div>
		<div class="col-lg-3 d-none d-lg-block">
			<?php include widget('sidebar-login')?>
		</div>
	</div>
</div>
<?php include widget('footer')?>

<?php include widget('bootstrap/toast')?>
