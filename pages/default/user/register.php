<script>

    function registerUrl(form) {
        var url = apiUrl + '?route=user.register&' + $( form ).serialize();
        console.log(url);
        return url;
    }

    function onRegisterFormSubmit(form) {

        $.ajax( registerUrl(form) )
            .done(function(re) {
                if ( isBackendError(re) ) {
                    alert(re);
                }
                else {
                    setLogin(re);
                    move(homePage);
                }
            })
            .fail(function() {
                alert( "Server error" );
            });

        return false;
    }
</script>
<form onsubmit="return onRegisterFormSubmit(this)">
    <div class="mb-3">
        <label  class="form-label">Email address</label>
        <input type="email" class="form-control" aria-describedby="emailHelp" name="user_email">
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="user_pass">
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label  class="form-label">First name</label>
                    <input type="text" class="form-control" name="first_name">
                </div>
            </div>
            <div class="col">

                <div class="mb-3">
                    <label class="form-label">Middle name</label>
                    <input type="text" class="form-control" name="middle_name" maxlength="1">
                </div>
            </div>
            <div class="col">

                <div class="mb-3">
                    <label class="form-label">Last name</label>
                    <input type="text" class="form-control" name="last_name">
                </div>

            </div>
        </div>
    </div>


    <div class="mb-3">
        <label class="form-label">Nickname</label>
        <input type="text" class="form-control" name="nickname">
    </div>

    <div class="mb-3">
        <label class="form-label">Mobile number</label>
        <input type="text" class="form-control" name="mobile">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

