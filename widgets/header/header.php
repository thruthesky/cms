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
                            <i class="fa fa-address-book px-20 mr-12 fs-lg"></i>
                            <div class="fw-medium">Profile</div>
                        </a>
                    </li>

                    <li class=" nav-item mb-22">
                        <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=user.logout">
                            <i class="fa fa-power-off px-20 mr-12 fs-lg"></i>
                            <div class="fw-medium">Logout</div>
                        </a>
                    </li>
                <?php } else { ?>

                    <li class=" nav-item mb-22">
                        <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=user.login">
                            <i class="fa fa-reply px-20 mr-12 fs-lg"></i>
                            <div class="fw-medium">Login</div>
                        </a>
                    </li>
                    <li class=" nav-item mb-22">
                        <a class="nav-link d-flex justify-content-start align-items-center black600" href="<?=Config::$registerPage?>">
                            <i class="fa fa-address-card px-20 mr-12 fs-lg"></i>
                            <div class="fw-medium">Register</div>
                        </a>
                    </li>

                    <?php
                }
                ?>
                <?php if ( admin() ) { ?>
                    <li class=" nav-item mb-22">
                        <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=admin.home">
                            <i class="fa fa-cubes px-20 mr-12 fs-lg"></i>
                            <div class="fw-medium">Admin Page</div>
                        </a>
                    </li>
                <?php } ?>

                <li class=" nav-item mb-22">
                    <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=post.list&slug=qna">
                        <i class="fa fa-question px-20 mr-12 fs-lg"></i>
                        <div class="fw-medium">QnA</div>
                    </a>
                </li>
                <li class=" nav-item mb-22">
                    <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=post.list&slug=discussion">
                        <i class="fa fa-book px-20 mr-12 fs-lg"></i>
                        <div class="fw-medium">Discussion</div>
                    </a>
                </li>
                <li class=" nav-item mb-22">
                    <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=post.list&slug=jobs">
                        <i class="fa fa-graduation-cap px-20 mr-12 fs-lg"></i>
                        <div class="fw-medium">Jobs</div>

                    </a>
                </li>
                <li class="nav-item mb-22">
                    <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                        <i class="fa fa-address-book px-20 mr-12 fs-lg"></i>
                        <div class="fw-medium">Realestate</div>

                    </a>
                </li>
                <li class="nav-item dropdown mb-22 ">
                    <a class="nav-link dropdown-toggle  d-flex justify-content-start align-items-center black600" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-users px-20 mr-12 fs-lg"></i>
                        <div class="fw-medium">Community</div>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                                <i class="fa fa-address-book px-20 mr-12 fs-lg"></i>
                                <div class="fw-medium">Greetings</div>
                            </a>
                        </li>
                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                                <i class="fa fa-address-book px-20 mr-12 fs-lg"></i>
                                <div class="fw-medium">PHP Developers</div>
                            </a>
                        </li>
                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                                <i class="fa fa-address-book px-20 mr-12 fs-lg"></i>
                                <div class="fw-medium">Javascript Developers</div>
                            </a>
                        </li>
                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                                <i class="fa fa-address-book px-20 mr-12 fs-lg"></i>
                                <div class="fw-medium">Flutter Developers</div>
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="#">
                                <i class="fa fa-address-book px-20 mr-12 fs-lg"></i>
                                <div class="fw-medium">Create your forum</div>
                            </a>
                        </li>
                        <li class="nav-item mb-22">
                            <a class="nav-link d-flex justify-content-start align-items-center black600" href="/?page=contact">
                                <i class="fa fa-address-book px-20 mr-12 fs-lg"></i>
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