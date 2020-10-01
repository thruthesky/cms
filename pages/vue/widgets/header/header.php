<section class="d-none d-lg-block">

    <div class="flex justify-content-between">

        <a href="/">Home</a>
        <a href="/?page=user.login" v-if="isLoggedOut">Login</a>
        <a href="#" @click="logout" v-if="isLoggedIn">Logout</a>
        <a href="/?page=user.register" v-if="isLoggedOut">Register</a>
        <a href="/?page=user.profile" v-if="isLoggedIn">Profile</a>
        (Desktop header)
    </div>

</section>
<section class="d-lg-none">

    <div class="flex justify-content-between">
        <a href="/">Home</a>
        <a href="/?page=user.login" v-if="isLoggedOut">Login</a>
        <a href="#" @click="logout" v-if="isLoggedIn">Logout</a>
        <a href="/?page=user.register" v-if="isLoggedOut">Register</a>
        <a href="/?page=user.profile" v-if="isLoggedIn">Profile</a>
        (Mobile Header)
    </div>
</section>