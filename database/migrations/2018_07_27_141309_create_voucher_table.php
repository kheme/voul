<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'voucher',
            function (Blueprint $table) {
                $table->string('voucher_id', 8)->primary();
                $table->integer('voucher_recipient_id')->unsigned();
                $table->integer('voucher_offer_id')->unsigned();
                $table->date('voucher_expiry_date');
                $table->date('voucher_used_date')->nullable();
                $table->softDeletes();
                $table->timestamps();
            }
        );

        Schema::table(
            'voucher',
            function ($table) {
                $table
                    ->foreign('voucher_recipient_id', 'fk_01')
                    ->references('recipient_id')
                    ->on('recipient')
                    ->onUpdate('cascade');

                $table
                    ->foreign('voucher_offer_id', 'fk_02')
                    ->references('offer_id')
                    ->on('offer')
                    ->onUpdate('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher');
    }
}
