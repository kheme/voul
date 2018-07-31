<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
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
            return $this->controller->displayMessage($message, false, 400);
        } elseif ($offer_not_found) {
            $message = 'Invalid special offer key provided';
            return $this->controller->displayMessage($message, false, 400);
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
                    $this->controller->displayMessage($message, true, 201);
            } else {
                return
                    $this->controller
                    ->displayMessage($this->error_message, false, 400);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return
                $this->controller
                ->displayMessage($this->error_message, false, 400);
        }
    }

    /**
     * View details of a Voucher
     * 
     * @param request $request HTTP request
     *
     * @return json
     */
    public function viewVoucher(request $request)
    {
        $json_message = 'invalid key';
        $key          =  $request->key;
        $key         += 0; // make sure we have an integer here

        if ($key == 0) {
            return
                $this->controller->displayMessage($json_message, false, 400);
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
                $this->controller->displayMessage($found_voucher, true, 200);
        } else {
            return
                $this->controller->displayMessage($json_message, false, 400);
        }
    }

    /**
     * Verify a Voucher using code
     * 
     * @param request $request HTTP request
     *
     * @return json
     */
    public function verifyVoucherWithCode(request $request)
    {
        $json_message = 'invalid code';
        $code         =  $request->code;
        $code         += 0; // make sure we have an integer here

        if ($code == 0) {
            return
                $this->controller->displayMessage($json_message, false, 400);
        }

        $found_voucher = Voucher::where('voucher_code', $code)
            ->join('recipient', 'recipient_id', 'voucher_recipient_id')
            ->selectRaw(
                "voucher_used_date, voucher_expiry_date,
                voucher_id, voucher_code, recipient_name,
                recipient_surname, recipient_email,
                recipient_id"
            )
            ->first();
        
        if ($found_voucher) {
            $is_used    = true;
            $is_expired = false;

            if (is_null($found_voucher->voucher_used_date)) {
                $is_used = false;
            }

            $today       = Carbon::now();
            $expiry_date = Carbon::parse($found_voucher->voucher_expiry_date);
            $days_past_expiry_date = $today->diffInDays($expiry_date, false);
            $is_expired  = Max(0, $days_past_expiry_date);
            $is_expired  = (boolean) $is_expired;

            $recipient_data = [
                'recipient_id' => $found_voucher->recipient_id,
                'name'         => $found_voucher->recipient_name,
                'surname'      => $found_voucher->recipient_surname,
                'email'        => $found_voucher->recipient_email
            ];
            
            $data = [
                'voucher_id'  => $found_voucher->voucher_id,
                'code'        => $found_voucher->voucher_code,
                'is_used'     => $is_used,
                'is_expired'  => $is_expired,
                'used_date'   => $found_voucher->voucher_used_date,
                'expiry_date' => $found_voucher->voucher_expiry_date,
                'recipient'   => $recipient_data
            ];

            return
                $this->controller->displayMessage($data, true, 200);
        } else {
            return
                $this->controller->displayMessage($json_message, false, 400);
        }
    }

    /**
     * Verify a Voucher using voucher_code and email address of Recipient
     * 
     * @param request $request HTTP request
     *
     * @return json
     */
    public function matchVoucherCodeAndEmail(request $request)
    {
        $recipient_email = $request->email;
        $voucher_code    = $request->code;

        $recipient = Recipient::where('recipient_email', $recipient_email)
            ->selectRaw('recipient_id')
            ->first();
        $recipient_not_found = (boolean) ! $recipient;

        $voucher = Voucher::where('voucher_code', $voucher_code)
            ->where('recipient_email', $recipient_email)
            ->join('recipient', 'recipient_id', 'voucher_recipient_id')
            ->join('offer', 'offer_id', 'voucher_offer_id')
            ->first();
        $voucher_not_found = (boolean) ! $voucher;
        
        if ($recipient_not_found) {
            $message = 'Invalid recipient email provided';
            return
                $this->controller->displayMessage($message, false, 400);
        } elseif ($voucher_not_found) {
            $message = 'Invalid voucher code provided';

            return
                $this->controller->displayMessage($message, false, 400);
        }

        $discount = "$voucher->offer_discount%";
        
        $voucher->update(['voucher_used_date' => DB::raw("now()")]);

        return
                response()->json(
                    [
                        'success'  => true,
                        'discount' => $discount
                    ],
                    200
                );
    }
}
