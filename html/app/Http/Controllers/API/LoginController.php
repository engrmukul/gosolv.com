<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Validator;

class LoginController extends BaseController
{
    /**
     * return user all information
     *
     * @param \Illuminate\Http\Request in $request
     * @param \Illuminate\Http\Response
     */
    public function signin()
    {

    }

    /**
     * app session destroy
     *
     * @param \Illuminate\Http\Response
     */
    public function signout()
    {

    }

    /**
     * send email with unique code
     *
     */
    public function forgotPasswordRequest()
    {

    }

    /**
     * send reset password email
     *
     * @param \Illuminate\Http\Response
     */
    public function resetPasswordEmail()
    {

    }

    /**
     * load password update form
     *
     * @param \Illuminate\Http\Response
     */
    public function updateNewPasswordForm()
    {

    }

    /**
     * load password update success page
     *
     * @param \Illuminate\Http\Response
     */
    public function passwordResetSuccess()
    {

    }
}
