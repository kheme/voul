<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class OffersTest extends TestCase
{
    /**
     * Test adding Special Offer
     *
     * @return void
     */
    public function testAddingSpecialOffer()
    {
        $this
            ->post(
                'offer',
                [
                    'name'     => '10% Off!',
                    'discount' => '10'
                ]
            )
            ->seeStatusCode(201)
            ->seeJson(['success' => true]);
    }

    /**
     * Test viewing Special Offer
     *
     * @return void
     */
    public function testViewingSpecialOffer()
    {
        $this
            ->get(
                'offer',
                ['key' => 1]
            )
            ->seeStatusCode(200)
            ->seeJson(
                [
                    'success' => true,
                    'data'    => [
                        'offer_id'   => 1,
                        'offer_name' => '10% Off!',
                    ]
                ]
            )
            ;
    }
}
