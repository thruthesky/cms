<?php
	$options = get_page_options();
?>
<div class="top l-center mt-3 d-flex justify-content-end">
	<a class="link d-inline-block fs-sm" href="#"><i class="fa fa-edit mr-2"></i><?=tr(['en' => 'Greetings', 'ko' => '가입인사'])?></a>
    <a class="link d-inline-block pl-3 fs-sm" href="#"><i class="fa fa-comments mr-2"></i><?=tr(['en' => 'Chatting', 'ko' => '채팅방입장'])?></a>
</div>
<div class="header l-center d-flex">
    <div class="logo-search l-content-width py-3 d-flex">
        <a href="/"><img class="h-70" src="<?=PAGE_URL?>/img/icons/flutter-icon.png"></a>
        <a href="/">
            <div class="ml-3 mt-2">
                <div class="fs-xs">소통하면서 배우는</div>
                <div class="fs-xl">플러터 커뮤니티</div>
            </div>
        </a>
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

<div class="mainmenu l-center">
    <div class="l-content-width d-flex justify-content-between fw-sm fs-sm">
        <a href="#">자유게시판</a>
        <a href="#">질문과답변</a>
        <a href="#">플러터 강좌</a>
        <a href="#">다트 강좌</a>
        <a href="#">파이어베이스 강좌</a>
        <a href="#">워드프레스 강좌</a>
        <a href="#">NODE.JS 강좌</a>
        <a href="#">오프라인 모임</a>
    </div>
</div>



<div class="layout-body l-center d-flex">
    <div class="layout-body-content l-content-width">
        <main class="mr-lg-4">
		    <?php
		    include $options['page_script'];
		    ?>
        </main>
    </div>
    <div class="layout-body-side-bar flex-grow-1 my-3 ml-space-lg">
	    <div class="p-3 border rounded bg-light">
		    <?php include widget('sidebar-login')?>
        </div>
    </div>
</div>

<?php include widget('footer')?>

<?php include widget('bootstrap/toast')?>
