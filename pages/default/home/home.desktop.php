<div class="l-content-width p-5 white bg-black h-stack-1">
    <div class="fs-xxl white">
        모바일 앱 개발 크로스 프레임워크 순위
    </div>
    <div class="container mt-4">
        <div class="row fs-xs">
            <div class="col white">순위</div>
            <div class="col white">플랫폼</div>
            <div class="col white">점유율</div>
            <div class="col-5 white"></div>
        </div>
        <div class="row mt-3 light">
            <div class="col">
                1위
            </div>
            <div class="col">리액트 네이티브</div>
            <div class="col">43%</div>
            <div class="col-5">
                <div class="progress">
                    <div class="progress-bar bg-gray" role="progressbar" style="width: 53%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>


        <div class="row mt-1 skyblue">
            <div class="col">
                2위
            </div>
            <div class="col">플러터</div>
            <div class="col">39%</div>
            <div class="col-5">
                <div class="progress">
                    <div class="progress-bar bg-skyblue" role="progressbar" style="width: 49%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="row mt-1 gray">
            <div class="col">
                3위
            </div>
            <div class="col">Cordova</div>
            <div class="col">18%</div>
            <div class="col-5">
                <div class="progress">
                    <div class="progress-bar bg-gray" role="progressbar" style="width: 49%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="row gray">
            <div class="col">
                4위
            </div>
            <div class="col">Ionic</div>
            <div class="col">18%</div>
            <div class="col-5">
                <div class="progress">
                    <div class="progress-bar bg-gray" role="progressbar" style="width: 49%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="row gray">
            <div class="col">
                5위
            </div>
            <div class="col">Xamarin</div>
            <div class="col">14%</div>
            <div class="col-5">
                <div class="progress">
                    <div class="progress-bar bg-gray" role="progressbar" style="width: 49%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>


    </div>
</div>


<?php
function decorateLine($title) {
    return <<<EOH
<div class="mt-2 overflow-hidden fw-xs" style="line-height: 1.4em; height: 2.8em;">$title</div>
EOH;
}
?>

<div class="l-center-width mt-space">
<table width="806" border="0" cellspacing="0" cellpadding="0">
    <tr valign="top">
        <td width="258">
            <img class="w-100" src="<?=THEME_URL?>/tmp/tap1.jpg">
            <div class="mt-1 px-3 pt-2 pb-3 border">
                <?php
                for($i = 0; $i < 5; $i ++) {
                    echo decorateLine('유튜브에서 전문 강사들이 무료로 제공하는 왕초보 필수 코드 동영상 강좌입니다.');
                }
                ?>
            </div>
        </td>
        <td width="16"><div class="l-space overflow-hidden">&nbsp;</div></td>
        <td width="258">
            <img class="w-100" src="<?=THEME_URL?>/tmp/tap2.jpg">
            <div class="mt-1 px-3 pt-2 pb-3  border">

                <?php
                for($i = 0; $i < 5; $i ++) {
                    echo decorateLine('유튜브에서 전문 강사들이 무료로 제공하는 왕초보 필수 코드 동영상 강좌입니다.');
                }
                ?>
            </div>
        </td>
        <td width="16"><div class="l-space overflow-hidden">&nbsp;</div></td>
        <td width="258">
            <img class="w-100" src="<?=THEME_URL?>/tmp/tap3.jpg">
            <div class="mt-1 px-3 pt-2 pb-3  border">

                <?php
                for($i = 0; $i < 5; $i ++) {
                    echo decorateLine('유튜브에서 전문 강사들이 무료로 제공하는 왕초보 필수 코드 동영상 강좌입니다.');
                }
                ?>
            </div></td>
    </tr>
</table>
</div>