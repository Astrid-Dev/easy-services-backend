<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('address');
            $table->decimal('latitude');
            $table->decimal('longitude');
            $table->string('user_intervention_date');
            $table->string('user_price')->nullable();
            $table->string('provider_intervention_date')->nullable();
            $table->string('provider_price')->nullable();
            $table->string('final_intervention_date')->nullable();
            $table->string('final_price')->nullable();
            $table->integer('state')->default(0);
            $table->timestamps();

            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('service_provider_id')->nullable();

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('service_provider_id')
                ->references('id')
                ->on('service_providers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquiries');
    }
}
