<div class="p-4 h-stack-1 border">
    <div class="fs-xl">
        플러터 1위. 리액트네이티브 2위.
    </div>
    <div>
        <a class="underline fs-xs" href="https://trends.google.com/trends/explore?cat=32&date=today%2012-m,today%2012-m,today%2012-m,today%2012-m,today%2012-m&geo=,,,,&q=xamarin,react%20native,flutter,phonegap,ionic" target="_blank">구글 트랜드: 모바일 앱 개발 크로스 프레임워크 순위</a>
        <a class="underline fs-xs" href="https://www.jetbrains.com/lp/devecosystem-2020/" target="_blank">Jetbrains 크로스플랫폼 모바 프레임워크 순위</a>
    </div>
    <small>많은 자료에서 플러터가 1위, 2위 자리 다툼을 하고 있다는 것을 알 수 있다.</small>
    <div class="px-5">
        <a class="underline fs-xs" href="https://trends.google.com/trends/explore?cat=32&date=today%2012-m,today%2012-m,today%2012-m,today%2012-m,today%2012-m&geo=,,,,&q=xamarin,react%20native,flutter,phonegap,ionic" target="_blank"><img class="w-100" src="<?=THEME_URL?>/tmp/ranking.jpg"></a>
    </div>

</div>


<?php
function decorateLine($post) {
    return <<<EOH
<a class="d-inline-block border-box mt-2 overflow-hidden fw-xs" href="{$post->guid}" style="line-height: 1.4em; height: 2.8em;">
{$post->post_title}
</a>
EOH;
}
?>

<div class="l-center-width mt-space">
<table border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
        <td width="33%">
            <img class="w-100" src="<?=THEME_URL?>/tmp/tap1.jpg">
            <div class="mt-1 px-3 pt-2 pb-3 border">
                <?php
                $posts = get_posts([
                    'category' => implode(',', get_ids_of_slugs(['php', 'dart', 'firebase', 'nodejs'])),
                    'numberposts' => 5
                ]);
                foreach($posts as $post) {
                    echo decorateLine($post);
                }
                ?>
            </div>
        </td>
        <td width="16"><div class="l-space overflow-hidden">&nbsp;</div></td>
        <td width="33%">
            <img class="w-100" src="<?=THEME_URL?>/tmp/tap2.jpg">
            <div class="mt-1 px-3 pt-2 pb-3  border">

                <?php

                $posts = get_posts([
                    'category' => implode(',', get_ids_of_slugs(['flutter', 'news', 'reminder'])),
                    'numberposts' => 5
                ]);

                foreach($posts as $post) {
                    echo decorateLine($post);
                }
                ?>
            </div>
        </td>
        <td width="16"><div class="l-space overflow-hidden">&nbsp;</div></td>
        <td width="33%">
            <img class="w-100" src="<?=THEME_URL?>/tmp/tap3.jpg">
            <div class="mt-1 px-3 pt-2 pb-3  border">

                <?php

$posts = get_posts([
    'category' => implode(',', get_ids_of_slugs(['qna', 'discussion'])),
    'numberposts' => 5
]);

                foreach($posts as $post) {
                    echo decorateLine($post);
                }
                ?>
            </div></td>
    </tr>
</table>
</div>
