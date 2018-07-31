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
    public function displayMessage($message, $is_success, $code)
    {
        $data['success'] = $is_success;
        
        if (isset($message)) {
            if (is_array($message)) {
                $data['data'] = $message;
            } else {
                $data['message'] = $message;
            }
        }

        return response()->json($data, $code);
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
