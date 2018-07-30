<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Offer;

class OfferController extends Controller
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
     * Create a new Special Offer
     * 
     * @param request $request HTTP request
     *
     * @return json
     */
    public function createSpecialOffer(request $request)
    {
        $offer_name     = $request->name;
        $offer_discount = round(($request->discount / 100), 2);

        try {
            $created_new_offer = Offer::create(
                [
                    'offer_name'     => $offer_name,
                    'offer_discount' => $offer_discount
                ]
            );

            if ($created_new_offer) {
                $message = ['offer_id' => $created_new_offer->offer_id];

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
     * View details of a Special Offer
     * 
     * @param int $key Primary key of the Special Offer of interest
     *
     * @return json
     */
    public function viewSpecialOffer($key)
    {
        $json_message = 'invalid key';
        $key         += 0; // make sure we have an integer here

        if ($key == 0) {
            return
                $this->controller->displayMessage($json_message, false);
        }

        $found_offer = Offer::where('offer_id', $key)
            ->selectRaw(
                "offer_id, offer_name,
                offer_discount"
            )
            ->first();

        if ($found_offer) {
            $found_offer = $found_offer->toArray();

            return
                $this->controller->displayMessage($found_offer);
        } else {
            return
                $this->controller->displayMessage($json_message, false);
        }
    }
}
