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
            $table->string('address');
            $table->decimal('latitude');
            $table->decimal('longitude');
            $table->string('creation_date');
            $table->string('intervention_date');
            $table->integer('state')->default(0);
            $table->timestamps();

            $table->unsignedBigInteger('enquiry_type_id');
            $table->unsignedBigInteger('habitation_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('service_provider_id');


            $table->foreign('enquiry_type_id')
                ->references('id')
                ->on('enquiry_types')
                ->onDelete('cascade');

            $table->foreign('habitation_id')
                ->references('id')
                ->on('habitations')
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
