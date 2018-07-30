<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Recipient;
use App\Voucher;

class RecipientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->controller    = new Controller;
        $this->error_message = 'bad request or duplicate data';
    }

    /**
     * Create a new Recipient
     * 
     * @param request $request HTTP request
     *
     * @return json
     */
    public function createRecipient(request $request)
    {
        $recipient_name    = ucwords($request->name);
        $recipient_surname = ucwords($request->surname);
        $recipient_email   = strtolower($request->email);
        //print_r($request->all());die();
        try {
            $created_new_recipient = Recipient::create(
                [
                    'recipient_name'    => $recipient_name,
                    'recipient_surname' => $recipient_surname,
                    'recipient_email'   => $recipient_email,
                ]
            );

            if ($created_new_recipient) {
                $message = ['recipient_id' => $created_new_recipient->recipient_id];
                return
                    $this->controller->displayMessage($message);
            } else {
                return
                    $this->controller->displayMessage($this->error_message, false);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return
                $this->controller->displayMessage($this->error_message, false);
        }
    }

    /**
     * View details of a Recipient
     * 
     * @param int $key Primary key of the Recipient of interest
     *
     * @return json
     */
    public function viewRecipient($key)
    {
        $json_message = 'invalid key';
        $key         += 0; // make sure we have an integer here

        if ($key == 0) {
            return
                $this->controller->displayMessage($json_message, false);
        }

        $found_recipient = Recipient::where('recipient_id', $key)
            ->selectRaw(
                "recipient_id, recipient_name,
                recipient_surname, recipient_email"
            )
            ->first();

        if ($found_recipient) {
            $found_recipient = $found_recipient->toArray();

            return
                $this->controller->displayMessage($found_recipient);
        } else {
            return
                $this->controller->displayMessage($json_message, false);
        }
    }

    /**
     * View a Recipient
     * 
     * @param request $request HTTP request
     *
     * @return json
     */
    public function verifyRecipientByEmail(request $request)
    {
        $json_message = 'invalid email';

        $recipient_email = $request->verify;

        $found_recipient = Recipient::where('recipient_email', $recipient_email)
            ->join('voucher', 'voucher_recipient_id', 'recipient_id')
            ->join('offer', 'offer_id', 'voucher_offer_id')
            ->select('voucher_code', 'offer_name')
            ->get();

        if ($found_recipient) {
            $found_recipient = $found_recipient->toArray();

            return
                $this->controller->displayMessage($found_recipient);
        } else {
            return
                $this->controller->displayMessage($json_message, false);
        }
    }
}
