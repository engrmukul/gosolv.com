<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse( $result, $message )
    {
        $response = [
          'success' => true,
          'data' => $result,
          'message' => $message,
        ];

        return response()->json( $response, 200 );
    }

    /**
     * return error response
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError( $error, $errorMessage = [], $code = 404 )
    {
        $response = [
            'success' => false,
            'message' => $error,
            'error'=> $errorMessage,
        ];

        return response()->json( $response, $code );
    }

    public function generate_string($strength) {
        $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }
}
