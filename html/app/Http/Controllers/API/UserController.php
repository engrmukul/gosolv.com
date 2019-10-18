<?php

namespace App\Http\Controllers\API;

use App\UserDetail;
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
     * @return \Illuminate\Http\Response
     */
    public function saveUser(Request $request)
    {
        if ($request->method() == 'GET') {
            return $this->sendError('Use post method.');
        } else {

            $input = $request->all();

            $validator = Validator::make($input, [
                'user_type' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'companyInfo' => 'required',
                'country' => 'required',
                'state' => 'required',
                'zipe_code' => 'required',
                'country_code' => 'required',
                'phone' => 'required',
                'source_list' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            // Using the Query Builder
            $getLastUserId = \DB::table('users')->find(\DB::table('users')->max('id'));

            $activationCode = $this->generate_string(30) . time() . ($getLastUserId->id + 1);
            $activationLink = base_path("activate/" . $activationCode);

            $user = new User;
            $userDetail = new UserDetail();

            $user->name = $request->first_name . $request->last_name;
            $user->email = $request->email;
            $user->password = password_hash($request->password, PASSWORD_DEFAULT);
            $user->status = 0;
            $user->activation_code = $activationCode;
            $user->remember_token = '';
            $user->created_at = date('Y-m-d H:i:s');
            $user->creator_id = 0;

            $user->save();

            $userDetail->user_id = $user->id;
            $userDetail->user_type = strtolower( trim( $request->user_type ) );
            $userDetail->first_name = trim($request->first_name);
            $userDetail->last_name = trim($request->last_name);
            $userDetail->companyInfo = trim($request->companyInfo);
            $userDetail->address = trim($request->address);
            $userDetail->country = trim($request->country);
            $userDetail->state = trim($request->state);
            $userDetail->zipe_code = trim($request->zipe_code);
            $userDetail->phone = trim($request->phone);
            $userDetail->telephone = trim($request->telephone);
            $userDetail->created_at = date('Y-m-d H:i:s');
            $userDetail->buyer_cat = '["Apparel"]';
            $userDetail->source_list = "[" . implode(",", array_map(function ($val) {
                    return sprintf('"%s"', $val);
                }, explode(",", $request->source_list))) . "]";

            $userDetail->save();

            if ($userDetail->id) {

                //Send Email for activation
                //$userDataForEmailBody = array('name' => $user->name, 'activationLink' => $activationLink);
                //$emailData['emailBody'] = $this->load->view('emailTemplate/registrationCompleteTemplate', $userDataForEmailBody, true);
                //$emailData['receiver_email'] = $this->userInput->email;
                //$this->sendEmail($emailData);

                return $this->sendResponse(array(), 'Registration Successful, A confirmation email has been sent to your email address. Please verify first.');
            } else {
                return $this->sendError('Something went wrong, please try again later.');
            }
        }
    }

    /**
     * Resent user verification code
     *
     * @param one time generate code
     * @param \Illuminate\Http\Response
     */
    public function resendVerificationLink()
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $userData = App\User::where('email', $request->email)->first();


        //CHECK IF USER IS ALREADY VERIFIED
        if( $userData->status == 1 )
        {
            return $this->sendError('This email address is already verified');
        }


        //SEND VERIFICATION EMAIL
        //$activationLink = base_url("activate/" . $userData->activation_code);
        //$userDataForEmailBody = array('name' => $userData->name, 'activationLink' => $activationLink);
        //$emailData['emailBody'] = $this->load->view( 'emailTemplate/registrationCompleteTemplate' , $userDataForEmailBody, true );
        //$emailData['receiver_email'] = $userInput->email;
        

        if( $this->sendEmail($emailData) )
        {
            return $this->sendResponse(array(), 'Verification email is sent again.');
        }
        else
        {
            return $this->sendError('Something went wrong !!');
        }
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
    public function activate( $code )
    {
        if (!empty($code)) {
            
            $affected = App\User::where('activation_code', $code)
                ->where('destination', 'San Diego')
                ->update(['activation_code' => '', 'status' => 1]);
            
			if ( $affected > 0 ) {
                return $this->sendResponse(array(), 'Your Email is verified. You can login!n.');
			} else {
                return $this->sendError('Your Email is NOT verified!');
			}
		}
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
