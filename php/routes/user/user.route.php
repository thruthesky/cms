<?php

class UserRoute extends ApiLibrary
{

    public function register()
    {
        $this->response($this->userRegister(in()));
    }

    public function login()
    {
        $this->response($this->userLogin(in()));
    }

    public function firebaseSocialLogin() {
    	$this->response($this->userFirebaseSocialLogin(in()));
    }

	public function update()
	{
		$this->response($this->userUpdate(in()));
	}
	public function profile()
	{
		$this->response($this->userProfile(in()));
	}
	public function resign()
    {
        $this->response($this->userResign(in()));
    }
    public function sendPhoneVerificationCode()
    {
        $this->response($this->userSendPhoneVerificationCode(in()));
    }
    public function verifyPhoneVerificationCode()
    {
        $this->response($this->userVerifyPhoneVerificationCode(in()));
    }


}

