<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Validator;

class UserController extends BaseController
{
    /**
     * Store newly created user
     *
     * @param \Illuminate\Http\Request in $request
     * @param \Illuminate\Http\Response
     */
    public function saveUser()
    {

    }

    /**
     * Resent user verification code
     *
     * @param one time generate code
     * @param \Illuminate\Http\Response
     */
    public function resendVerificationLink()
    {

    }

    /**
     * Send email to newly registered user for email verification
     *
     * @param email address both of receiver and sender and mail subject and mail body
     * @param \Illuminate\Http\Response
     */
    private function sendEmail()
    {

    }

    /**
     * Activate newly added user
     *
     * @param activation code
     * @param \Illuminate\Http\Response
     */
    public function activate()
    {

    }

    /**
     * Update user profile
     *
     * @param \Illuminate\Http\Request in $request
     * @param \Illuminate\Http\Response
     */
    public function updateProfile()
    {

    }

    /**
     * Update user FcmToken
     *
     * @param \Illuminate\Http\Request in $request
     * @param \Illuminate\Http\Response
     */
    public function updateFcmToken()
    {

    }
}
