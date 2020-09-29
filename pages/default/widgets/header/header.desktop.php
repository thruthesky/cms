<div class="container">
    <div class="row no-gutters">
        <div class="col">
            <div class="mt-3 d-flex justify-content-end">
                <a class="link d-inline-block fs-sm" href="/?page=post.list&slug=discussion"><i class="fa fa-edit mr-2"></i><?=tr(['en' => 'Greetings', 'ko' => '가입인사'])?></a>
                <a class="link d-inline-block pl-3 fs-sm" href="https://open.kakao.com/o/g20m41Mb" target="_blank"><i class="fa fa-comments mr-2"></i><?=tr(['en' => 'Chatting', 'ko' => '채팅방입장'])?></a>
	            <?php if ( login() ) { ?><a class="link d-inline-block d-lg-none pl-3 fs-sm" href="/?page=user.profile"><i class="fa fa-user mr-2"></i><?=tr(['en' => 'Profile', 'ko' => '회원정보'])?></a><?php } ?>
	            <?php if ( !login() ) { ?><a class="link d-inline-block d-lg-none pl-3 fs-sm" href="/?page=user.login"><i class="fa fa-sign-in-alt mr-2"></i><?=tr(login)?></a><?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="header container">
    <div class="row no-gutters">
        <div class="col-12 col-lg-9">
            <div class="logo-search py-3 d-flex">
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

            <div class="mainmenu">
                <div class="d-flex justify-content-between fw-sm fs-sm">
                    <a href="/?page=post.list&slug=discussion">자유게시판</a>
                    <a href="/?page=post.list&slug=qna">질문과답변</a>
                    <a href="/?page=post.list&slug=flutter">플러터 강좌</a>
                    <a href="/?page=post.list&slug=dart">다트 강좌</a>
                    <a href="/?page=post.list&slug=firebase">파이어베이스 강좌</a>
                    <a href="/?page=post.list&slug=php">PHP 워드프레스 강좌</a>
                    <a href="/?page=post.list&slug=nodejs">NODE.JS 강좌</a>
                    <a href="/?page=post.list&slug=meeting">오프라인 모임</a>
                </div>
            </div>
        </div>
        <div class="d-none d-lg-block col-lg-3">
            <div class="ml-space-lg border" style="margin-top: 25px; height: 94px;">
                <div class="p-3">
                    <a class="d-block fs-xs" href="/?page=post.list&slug=news">플러터 새소식</a>
                    <?php
                    $posts = get_posts(['category_name' => 'news', 'posts_per_page' => 2]);
                    foreach( $posts as $post ) {
                        ?>
                        <a class="d-block fs-sm" href="<?=$post->guid?>"><?=strcut($post->post_title, 14)?></a>
                    <?
                    }
                    ?>

                </div>
            </div>
        </div>

    </div>
</div>

