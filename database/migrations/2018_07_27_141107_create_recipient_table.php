<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'recipient',
            function (Blueprint $table) {
                $table->integer('recipient_id', true)->unsigned();
                $table->string('recipient_name', 32);
                $table->string('recipient_surname', 32);
                $table->string('recipient_email', 32)->unique();
                $table->timestamps();
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
        Schema::dropIfExists('recipient');
    }
}
