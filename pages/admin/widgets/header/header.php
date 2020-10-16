
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-0">
        <a class="navbar-brand" href="/"><?=tr(['ko'=> '관리자 홈', 'en'=> 'Admin Panel'])?></a>
        <a class="d-lg-none" href="/?page=admin.user.list"><?=tr(['ko'=>'사용자', 'en' => 'Users'])?></a>
        <a class="d-lg-none" href="/?page=admin.forum.list"><?=tr(['ko'=>'게시판', 'en' => 'Forum'])?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto mb-2 mb-lg-0">


                <li class="nav-item">
                    <a class="nav-link" href="/?page=admin.user.list"><?=tr(['ko'=>'사용자', 'en' => 'Users'])?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Posts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Comments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/?page=admin.forum.list"><?=tr(['ko'=>'게시판', 'en' => 'Forum'])?></a>
                </li>

                <li class="nav-item"><a class="nav-link" href="/?page=admin.settings.system">System Settings</a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        More
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/?page=admin.settings.domain">Domain Settings</a></li>
                        <li><a class="dropdown-item" href="/?page=admin.settings.i18n">i18n Settings</a></li>
                        <li><a class="dropdown-item" href="/?page=admin.notification.push">Push Notification</a></li>
                        <li><a class="dropdown-item" href="#">System Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Help</a></li>
                    </ul>
                </li>

            </ul>
            <form class="d-flex">
                <input class="form-control mr-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>