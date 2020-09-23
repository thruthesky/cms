<nav class="navbar navbar-expand-lg sbg-primary">
    <div class="container px-0">
        <a class="navbar-brand text-white" href="/"><?=tr(['ko'=> '소너브', 'en'=> 'Sonub'])?></a>
        <a class="d-lg-none text-white" href="/?page=post.list&slug=qna"><?=tr(['ko'=>'질문과답변', 'en' => 'QnA'])?></a>


        <a class="d-lg-none user-profile-photo icon-size circle" href="#" onclick="loginOrProfile()"></a>



        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                <?php if ( login() ) { ?>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/?page=user.profile">Profile</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="/?page=user.logout">Logout</a>
                    </li>
                <?php } else { ?>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/?page=user.login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?=Config::$registerPage?>">Register</a>
                    </li>

                    <?php
                }
                ?>
                <?php if ( admin() ) { ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/?page=admin.home">Admin Page</a>
                    </li>
                <?php } ?>

                <li class="nav-item">
                    <a class="nav-link" href="/?page=post.list&slug=qna">QnA</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/?page=post.list&slug=discussion">Discussion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/?page=post.list&slug=jobs">Jobs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Realestate</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        Community
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

                <li class="nav-item">
                    <a class="nav-link user-profile-photo icon-md-size circle" href="#" onclick="loginOrProfile()"></a>
                </li>

            </ul>
            <form class="d-flex">
                <input class="form-control mr-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>