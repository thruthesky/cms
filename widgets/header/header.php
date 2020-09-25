<nav class="navbar navbar-expand-lg navbar-dark bg-skyblue">
    <div class="container px-0">
        <a class="navbar-brand" href="/"><?=tr(['ko'=> '소너브', 'en'=> 'Sonub'])?></a>
        <a class="d-lg-none text-white" href="/?page=post.list&slug=qna"><?=tr(['ko'=>'질문과답변', 'en' => 'QnA'])?></a>
        <a class="d-lg-none user-profile-photo icon-size circle" href="#" onclick="loginOrProfile()"></a>
        <a class="" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars white" aria-hidden="true"></i>
        </a>
        <div class="collapse navbar-collapse bg-white py-2" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto mb-2 mb-lg-0 fs-14 roboto ">
                <?php if ( login() ) { ?>

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
                        <li><a class="dropdown-item" href="#">Greetings</a></li>
                        <li><a class="dropdown-item" href="#">PHP Developers</a></li>
                        <li><a class="dropdown-item" href="#">Javascript Developers</a></li>
                        <li><a class="dropdown-item" href="#">Flutter Developers</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Create your forum</a></li>
                        <li><a class="dropdown-item" href="/?page=contact">Contact</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex px-10">
                <input class="form-control mr-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>