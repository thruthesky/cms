<style>
    .side-nav {
        margin: -48px -16px -9px 70px;
    }
</style>


<nav class="navbar navbar-expand-lg navbar-dark bg-skyblue">
    <div class="container px-0">
        <a class="navbar-brand" href="/"><?=tr(['ko'=> '소너브', 'en'=> 'Sonub'])?></a>
        <a class="d-lg-none text-white" href="/?page=post.list&slug=qna"><?=tr(['ko'=>'질문과답변', 'en' => 'QnA'])?></a>
        <a class="d-lg-none user-profile-photo icon-size circle" href="#" onclick="loginOrProfile()"></a>
        <a class="" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars white" aria-hidden="true"></i>
        </a>
        <div class="side-nav collapse navbar-collapse position-relative bg-white show" id="navbarSupportedContent">
            <div class="position-absolute right top mt-8 mr-8">
                <a  href="/">
                    <i class="fa fa-home darkergray100 fs-22 p-8px" aria-hidden="true"></i>
                </a>
                <a  type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-times-circle darkergray100 fs-22 p-8px" aria-hidden="true"></i>
                </a>
            </div>

            <ul class="navbar-nav mr-auto pt-40 mb-lg-0 fs-14 roboto">
                <?php if ( login() ) { ?>
                    <li class="nav-item mb-22">
                        <a class="nav-item" href="#" onclick="loginOrProfile()">
                            <div class="ml-16px">
                                <img class="user-profile-photo wh40x40 circle mb-28" src="<?=loginProfilePhotoUrl()?>">
                                <div class="d-flex justify-content-between">
                                    <div class="fs-20 mb-6"><?=mb_strcut(loginNickname(), 0, 10)?></div>
                                    <div class="d-flex justify-content-center align-items-center mr-8">
                                        <a href="#profileModal" data-toggle="modal"
                                           data-field="mobile"
                                           data-title="<?=tr([en=>"Input your mobile.", ko =>'Input your mobile.'])?>"
                                           data-value="<?=login('mobile')?>"
                                        ><i class="fa fa-edit gray900 p-8px"></i></a>
                                    </div>
                                </div>

                                <div class="fs-xs"><?=tr('login_with')?> <?=login(SOCIAL_LOGIN)?></div>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="nav-item mb-22">
                        <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=user.profile">
                            <span class="w-60 text-center"><i class="fa fa-address-book mr-12 fs-lg"></i></span>
                            <div class="fw-medium"><?=tr('profile')?></div>
                        </a>
                    </li>

                    <li class=" nav-item mb-22">
                        <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=user.logout">
                            <span class="w-60 text-center"><i class="fa fa-power-off mr-12 fs-lg"></i></span>
                            <div class="fw-medium"><?=tr('logout')?></div>
                        </a>
                    </li>
                <?php } else { ?>

                    <li class=" nav-item mb-22">
                        <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=user.login">
                            <span class="w-60 text-center"><i class="fa fa-reply mr-12 fs-lg"></i></span>
                            <div class="fw-medium"><?=tr('login')?></div>
                        </a>
                    </li>
                    <li class=" nav-item mb-22">
                        <a class="nav-link d-flex justify-content-start align-items-center black600" href="<?=Config::$registerPage?>">
                            <span class="w-60 text-center"><i class="fa fa-address-card mr-12 fs-lg"></i></span>
                            <div class="fw-medium"><?=tr('register')?></div>
                        </a>
                    </li>

                    <?php
                }
                ?>
                <?php if ( admin() ) { ?>
                    <li class=" nav-item mb-22">
                        <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=admin.home">
                            <span class="w-60 text-center"><i class="fa fa-cubes mr-12 fs-lg"></i></span>
                            <div class="fw-medium">Admin Page</div>
                        </a>
                    </li>
                <?php } ?>

                <li class=" nav-item mb-22">
                    <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=post.list&slug=qna">
                        <span class="w-60 text-center"><i class="fa fa-question mr-12 fs-lg"></i></span>
                        <div class="fw-medium"><?=tr('qna')?></div>
                    </a>
                </li>
                <li class=" nav-item mb-22">
                    <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=post.list&slug=discussion">
                        <span class="w-60 text-center"><i class="fa fa-book mr-12 fs-lg"></i></span>
                        <div class="fw-medium"><?=tr('discussion')?></div>
                    </a>
                </li>
                <li class=" nav-item mb-22">
                    <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=post.list&slug=jobs">
                        <span class="w-60 text-center"><i class="fa fa-graduation-cap mr-12 fs-lg"></i></span>
                        <div class="fw-medium"><?=tr('jobs')?></div>

                    </a>
                </li>
                <li class="nav-item mb-22">
                    <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                        <span class="w-60 text-center"><i class="fa fa-address-book mr-12 fs-lg"></i></span>
                        <div class="fw-medium">Realestate</div>

                    </a>
                </li>
                <li class="nav-item dropdown mb-22 ">
                    <a class="nav-link dropdown-toggle  d-flex justify-content-start align-items-center black600" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        <span class="w-60 text-center"><i class="fa fa-users mr-12 fs-lg"></i></span>
                        <div class="fw-medium">Community</div>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                                <span class="w-60 text-center"><i class="fa fa-address-book mr-12 fs-lg"></i></span>
                                <div class="fw-medium">Greetings</div>
                            </a>
                        </li>
                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                                <span class="w-60 text-center"><i class="fa fa-address-book mr-12 fs-lg"></i></span>
                                <div class="fw-medium">PHP Developers</div>
                            </a>
                        </li>
                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                                <span class="w-60 text-center"><i class="fa fa-address-book mr-12 fs-lg"></i></span>
                                <div class="fw-medium">Javascript Developers</div>
                            </a>
                        </li>
                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                                <span class="w-60 text-center"><i class="fa fa-address-book mr-12 fs-lg"></i></span>
                                <div class="fw-medium">Flutter Developers</div>
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                                <span class="w-60 text-center"><i class="fa fa-address-book mr-12 fs-lg"></i></span>
                                <div class="fw-medium">Create your forum</div>
                            </a>
                        </li>
                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=contact">
                                <span class="w-60 text-center"><i class="fa fa-address-book mr-12 fs-lg"></i></span>
                                <div class="fw-medium">Contact</div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex px-10 pb-20">
                <input class="form-control mr-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>