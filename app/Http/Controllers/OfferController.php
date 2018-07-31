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
        $offer_discount = $request->discount;

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
     * View details of a Special Offer
     * 
     * @param request $request HTTP request
     *
     * @return json
     */
    public function viewSpecialOffer(request $request)
    {
        $json_message = 'invalid key';
        $key          =  $request->key;
        $key         += 0; // make sure we have an integer here

        if ($key == 0) {
            return
                $this->controller->displayMessage($json_message, false, 400);
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
                $this->controller->displayMessage($found_offer, true, 200);
        } else {
            return
                $this->controller->displayMessage($json_message, false, 400);
        }
    }
}
