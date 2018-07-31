<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Recipient;

class RecipientTest extends TestCase
{
    /**
     * Test adding Special Offer
     *
     * @return void
     */
    public function testAddingRecipient()
    {
        $this
            ->post(
                'recipient',
                [
                    'name'    => 'Name1',
                    'surname' => 'Surname',
                    'email'   => 'name1.surname1@gmail.com'
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
    public function testViewingRecipient()
    {
        $created = Recipient::create(
            [
                'recipient_name'    => 'Name1',
                'recipient_surname' => 'Surname1',
                'recipient_email'   => 'name1.surname1@gmail.com',
            ]
        );

        $this
            ->get(
                'recipient',
                ['key' => $created->recipient_id]
            )
            ->seeStatusCode(200)
            ->seeJson(
                [
                    'success' => true,
                ]
            )
            ;
    }
}
