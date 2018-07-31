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
$app->get('offer', 'OfferController@viewSpecialOffer');

$app->post('recipient', 'RecipientController@createRecipient');
$app->get('recipient', 'RecipientController@viewRecipient');
$app->get('recipient/verify', 'RecipientController@verifyRecipientByEmail');

$app->post('voucher', 'VoucherController@createVoucherForEachRecipient');
$app->get('voucher', 'VoucherController@viewVoucher');
$app->get('voucher/redeem', 'VoucherController@matchVoucherCodeAndEmail');
//$app->get('voucher/verify', 'VoucherController@verifyVoucherWithCode');