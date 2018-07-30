<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\Recipient;
use App\Offer;
use App\Voucher;

class VoucherController extends Controller
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
    public function createVoucherForEachRecipient(request $request)
    {
        $recipient_id        = ucwords($request->recipient);
        $offer_id            = ucwords($request->offer);
        $voucher_expiry_date = strtolower($request->expiry);

        $recipient = Recipient::where('recipient_id', $recipient_id)
            ->selectRaw(
                "recipient_id, recipient_name,
                recipient_surname, recipient_email"
            )
            ->first();
        $recipient_not_found = (boolean) ! $recipient;

        $offer = Offer::where('offer_id', $offer_id)
            ->selectRaw("offer_id, offer_name")
            ->first();
        $offer_not_found = (boolean) ! $offer;

        if ($recipient_not_found) {
            $message = 'Invalid recipient key provided';
            return $this->controller->displayMessage($message, false);
        } elseif ($offer_not_found) {
            $message = 'Invalid special offer key provided';
            return $this->controller->displayMessage($message, false);
        }

        try {
            DB::beginTransaction();

            $created_new_voucher = Voucher::create(
                [
                    'voucher_recipient_id' => $recipient_id,
                    'voucher_offer_id'     => $offer_id,
                    'voucher_expiry_date'  => $voucher_expiry_date,
                ]
            );
            
            $created_new_voucher->update(
                [
                    'voucher_code' => hash(
                        'crc32', $created_new_voucher->voucher_id
                    )
                ]
            );
            //print_r($created_new_voucher);die();
            if ($created_new_voucher) {
                $message = [
                    'voucher_id'   => $created_new_voucher->voucher_id,
                    'voucher_code' => $created_new_voucher->voucher_code
                ];

                DB::commit();
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
     * View details of a Voucher
     * 
     * @param int $key Primary key of the Recipient of interest
     *
     * @return json
     */
    public function viewVoucher($key)
    {
        $json_message = 'invalid key';
        $key         += 0; // make sure we have an integer here

        if ($key == 0) {
            return
                $this->controller->displayMessage($json_message, false);
        }

        $found_voucher = Voucher::where('voucher_code', $key)
            ->selectRaw(
                "voucher_id, voucher_code, voucher_recipient_id,
                voucher_offer_id, voucher_expiry_date, voucher_used_date"
            )
            ->first();
        
        if ($found_voucher) {
            $found_voucher = $found_voucher->toArray();

            return
                $this->controller->displayMessage($found_voucher);
        } else {
            return
                $this->controller->displayMessage($json_message, false);
        }
    }

    /**
     * Verify a Voucher using voucher_code and email address of Recipient
     * 
     * @param request $request HTTP request
     *
     * @return json
     */
    public function verifyVoucherWithCodeAndEmail(request $request)
    {
        $recipient_email = $request->email;
        $voucher_code    = $request->code;

        $recipient = Recipient::where('recipient_email', $recipient_email)
            ->selectRaw('recipient_id')
            ->first();
        $recipient_not_found = (boolean) ! $recipient;

        $voucher = Voucher::where('offer_id', $offer_id)
            ->selectRaw("offer_id, offer_name")
            ->first();
        $offer_not_found = (boolean) ! $offer;

        if ($recipient_not_found) {
            $message = 'Invalid recipient key provided';
            return $this->controller->displayMessage($message, false);
        } elseif ($offer_not_found) {
            $message = 'Invalid special offer key provided';
            return $this->controller->displayMessage($message, false);
        }
    }
}
