<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'voucher',
            function (Blueprint $table) {
                $table->integer('voucher_id', true)->unsigned()->change();
                $table->string('voucher_code', 8)->after('voucher_recipient_id')
                    ->nullable()
                    ->unique();
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
        //
    }
}
