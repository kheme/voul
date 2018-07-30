<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', 'Controller@showHomePage');

$app->post('offer', 'OfferController@createSpecialOffer');
$app->get('offer/{key}', 'OfferController@viewSpecialOffer');

$app->post('recipient', 'RecipientController@createRecipient');
$app->get('recipient', 'RecipientController@verifyRecipientByEmail');
$app->get('recipient/{key}', 'RecipientController@viewRecipient');

$app->post('voucher', 'VoucherController@createVoucherForEachRecipient');
$app->get('voucher/verify', 'VoucherController@verifyVoucherWithCodeAndEmail');
$app->get('voucher/{key}', 'VoucherController@viewVoucher');