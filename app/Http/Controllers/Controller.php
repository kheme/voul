<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Return status as json
     * 
     * @param string  $message    (Optional) Message to display along with status
     * @param boolean $is_success (Optional) If status is success (true) or error
     *
     * @return json
     */
    public function displayMessage($message, $is_success = true)
    {
        $status_code = [true => 200, false => 400];

        $data['success'] = $is_success;
        
        if (isset($message)) {
            if (is_array($message)) {
                $data['data'] = $message;
            } else {
                $data['message'] = $message;
            }
        }

        return response()->json($data, $status_code[$is_success]);
    }

    /**
     * Show homepage
     *
     * @return String
     */
    public function showHomePage()
    {
        $message  = 'Voucher Pool - voul v1.0 by @kheme';
        
        return response()->json(
            [
                'success' => true,
                'message' => $message,
            ],
            200
        );
    }
}
