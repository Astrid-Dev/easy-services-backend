<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('label_en')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->string('illustration')->nullable();
            $table->timestamps();
        });

        DB::table('services')->insert([
            [
                "parent_id" => null,
                "illustration" => "Eau",
                'label' => "Nettoyage",
                "label_en" => 'Cleaning'
            ],
            [
                "parent_id" => null,
                "illustration" => "Eau",
                'label' => "Jardinage",
                "label_en" => 'Gardening'
            ],
            [
                "parent_id" => null,
                "illustration" => "Eau",
                'label' => "Antiparasitaire",
                "label_en" => 'Pest control'
            ],
            [
                "parent_id" => null,
                "illustration" => "Eau",
                'label' => "Dépérissement",
                "label_en" => 'Rubbish removal'
            ],
            [
                "parent_id" => null,
                "illustration" => "Eau",
                'label' => "Homme à tout faire",
                "label_en" => 'Handyman'
            ],


            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Assainissement antiviral",
                "label_en" => 'Antiviral sanitization'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage de fenêtre",
                "label_en" => 'Window cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage régulier",
                "label_en" => 'Regular cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage de fin de bail",
                "label_en" => 'End of lease cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage de printemps",
                "label_en" => 'Spring cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage four/barbecue",
                "label_en" => 'Oven/BBQ Cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage de matelas",
                "label_en" => 'Mattress cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage de carreaux et joints",
                "label_en" => 'Tiles & Grout cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Sellerie véhicule",
                "label_en" => 'Vehicle upholstery'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage de tissus d'ameublement",
                "label_en" => 'Upholstery cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage à pression",
                "label_en" => 'Pressure cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage des constructeurs",
                "label_en" => 'Builders cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage de moquette/tapis",
                "label_en" => 'Carpet/Rug cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "Eau",
                'label' => "Nettoyage de gouttière",
                "label_en" => 'Gutter cleaning'
            ],


            [
                "parent_id" => 2,
                "illustration" => "Eau",
                'label' => "Entretien du jardin",
                "label_en" => 'Garden maintenance'
            ],
            [
                "parent_id" => 2,
                "illustration" => "Eau",
                'label' => "Tonte de la pelouse",
                "label_en" => 'Lawn mowing'
            ],
            [
                "parent_id" => 2,
                "illustration" => "Eau",
                'label' => "Lavage à pression",
                "label_en" => 'Pressure washing'
            ],

            [
                "parent_id" => 3,
                "illustration" => "Eau",
                'label' => "Infestation d'insectes",
                "label_en" => 'Insect infestation'
            ],
            [
                "parent_id" => 3,
                "illustration" => "Eau",
                'label' => "Infestation de rongeurs",
                "label_en" => 'Rodent infestation'
            ],
            [
                "parent_id" => 3,
                "illustration" => "Eau",
                'label' => "Forfait emménagement/déménagement",
                "label_en" => 'Move in/out package'
            ],
            [
                "parent_id" => 3,
                "illustration" => "Eau",
                'label' => "Inspection antiparasitaire",
                "label_en" => 'Pest inspection'
            ],

            [
                "parent_id" => 4,
                "illustration" => "Eau",
                'label' => "Enlèvement des déchets mixtes",
                "label_en" => 'Mixed waste removal'
            ],
            [
                "parent_id" => 4,
                "illustration" => "Eau",
                'label' => "Enlèvement des déchets verts",
                "label_en" => 'Green waste removal'
            ],
            [
                "parent_id" => 4,
                "illustration" => "Eau",
                'label' => "Suppression des constructeurs",
                "label_en" => 'Builders removal'
            ],

            [
                "parent_id" => 5,
                "illustration" => "Eau",
                'label' => "Homme à tout faire",
                "label_en" => 'Handyman'
            ],
            [
                "parent_id" => 5,
                "illustration" => "Eau",
                'label' => "Montage du téléviseur",
                "label_en" => 'TV Mounting'
            ],

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
