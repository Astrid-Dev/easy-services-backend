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
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->timestamps();
        });

        DB::table('services')->insert([
            [
                "parent_id" => null,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage",
                "label_en" => 'Cleaning',
                'description' => "Le nettoyage est le processus d'élimination des substances indésirables, telles que la saleté, les agents infectieux et autres impuretés, d'un objet ou d'un environnement. Le nettoyage se produit dans de nombreux contextes différents et utilise de nombreuses méthodes différentes. Plusieurs métiers sont consacrés au nettoyage. Le nettoyage a différents objectif dont la propreté et l'hygiène, etc",
                'description_en' => "Cleaning is the process of removing unwanted substances, such as dirt, infectious agents, and other impurities, from an object or environment. Cleaning is often performed for aesthetic, hygienic, functional, environmental, or safety purposes. Cleaning occurs in many different contexts, and uses many different methods. Several occupations are devoted to cleaning."
            ],
            [
                "parent_id" => null,
                "illustration" => "images/plant-pot.png",
                'label' => "Jardinage",
                "label_en" => 'Gardening',
                "description" => "Le jardinage est la pratique de la culture et de la culture de plantes dans le cadre de l'horticulture . Dans les jardins, les plantes ornementales sont souvent cultivées pour leurs fleurs , leur feuillage ou leur apparence générale ; les plantes utiles, telles que les légumes- racines , les légumes-feuilles , les fruits et les herbes , sont cultivées pour la consommation, pour être utilisées comme colorants ou à des fins médicinales ou cosmétiques .",
                'description_en' => "Gardening is the practice of growing and cultivating plants as part of horticulture. In gardens, ornamental plants are often grown for their flowers, foliage, or overall appearance; useful plants, such as root vegetables, leaf vegetables, fruits, and herbs, are grown for consumption, for use as dyes, or for medicinal or cosmetic use."
            ],
            [
                "parent_id" => null,
                "illustration" => "images/bug-spray.png",
                'label' => "Antiparasitaire",
                "label_en" => 'Pest control',
                "description" => "La lutte antiparasitaire est la régulation ou la gestion d'une espèce définie comme nuisible ; tout animal, plante ou champignon ayant un impact négatif sur les activités humaines ou l'environnement. La réponse humaine dépend de l'importance des dommages causés et ira de la tolérance, en passant par la dissuasion et la gestion, jusqu'aux tentatives d'éradication complète du ravageur. Des mesures de lutte contre les ravageurs peuvent être effectuées dans le cadre d'une stratégie de lutte intégrée contre les ravageurs .",
                'description_en' => "Pest control is the regulation or management of a species defined as a pest; any animal, plant or fungus that impacts adversely on human activities or environment The human response depends on the importance of the damage done and will range from tolerance, through deterrence and management, to attempts to completely eradicate the pest. Pest control measures may be performed as part of an integrated pest management strategy."
            ],
            [
                "parent_id" => null,
                "illustration" => "images/rubbish.png",
                'label' => "Dépérissement",
                "label_en" => 'Rubbish removal',
                "description" => "La récupération des déchets est une des activités de la gestion des déchets qui, sans faire partie des trois R, contribue à la fin de vie des produits. La récupération des déchets consiste à les séparer des autres déchets avant qu'ils n'arrivent à leur traitement final.",
                'description_en' => "Waste collection is a part of the process of waste management. It is the transfer of solid waste from the point of use and disposal to the point of treatment or landfill. Waste collection also includes the curbside collection of recyclable materials that technically are not waste, as part of a municipal landfill diversion program."
            ],
            [
                "parent_id" => null,
                "illustration" => "images/handyman.png",
                'label' => "Prestataire à tout faire",
                "label_en" => 'Handy service provider',
                "description" => "Un bricoleur , également connu sous le nom de réparateur , bricoleur ou bricoleur , est une personne qualifiée dans un large éventail de réparations, généralement autour de la maison. Ces tâches comprennent des compétences professionnelles, des travaux de réparation, des travaux d'entretien , sont à la fois intérieures et extérieures, et sont parfois décrites comme des « travaux annexes », des « petits travaux » ou des « tâches de réparation ». Plus précisément, ces travaux pourraient être des travaux de plomberie légers tels que la réparation d'une toilette qui fuit ou des travaux électriques légers tels que le changement d'un luminaire ou d'une ampoule.",
                'description_en' => "A handyman, also known as a fixer, handyperson or handyworker, is a person skilled at a wide range of repairs, typically around the home. These tasks include trade skills, repair work, maintenance work, are both interior and exterior, and are sometimes described as « side work », « odd jobs » or « fix-up tasks ». Specifically, these jobs could be light plumbing jobs such as fixing a leaky toilet or light electric jobs such as changing a light fixture or bulb. The term handyman increasingly describes a paid worker, but it also includes non-paid homeowners or do-it-yourselfers."
            ],


            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Assainissement antiviral",
                "label_en" => 'Antiviral sanitization'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage de fenêtre",
                "label_en" => 'Window cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage régulier",
                "label_en" => 'Regular cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage de fin de bail",
                "label_en" => 'End of lease cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage de printemps",
                "label_en" => 'Spring cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage four/barbecue",
                "label_en" => 'Oven/BBQ Cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage de matelas",
                "label_en" => 'Mattress cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage de carreaux et joints",
                "label_en" => 'Tiles & Grout cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Sellerie véhicule",
                "label_en" => 'Vehicle upholstery'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage de tissus d'ameublement",
                "label_en" => 'Upholstery cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage à pression",
                "label_en" => 'Pressure cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage des constructeurs",
                "label_en" => 'Builders cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage de moquette/tapis",
                "label_en" => 'Carpet/Rug cleaning'
            ],
            [
                "parent_id" => 1,
                "illustration" => "images/cleaning.png",
                'label' => "Nettoyage de gouttière",
                "label_en" => 'Gutter cleaning'
            ],


            [
                "parent_id" => 2,
                "illustration" => "images/plant-pot.png",
                'label' => "Entretien du jardin",
                "label_en" => 'Garden maintenance'
            ],
            [
                "parent_id" => 2,
                "illustration" => "images/plant-pot.png",
                'label' => "Tonte de la pelouse",
                "label_en" => 'Lawn mowing'
            ],
            [
                "parent_id" => 2,
                "illustration" => "images/plant-pot.png",
                'label' => "Lavage à pression",
                "label_en" => 'Pressure washing'
            ],

            [
                "parent_id" => 3,
                "illustration" => "images/bug-spray.png",
                'label' => "Infestation d'insectes",
                "label_en" => 'Insect infestation'
            ],
            [
                "parent_id" => 3,
                "illustration" => "images/bug-spray.png",
                'label' => "Infestation de rongeurs",
                "label_en" => 'Rodent infestation'
            ],
            [
                "parent_id" => 3,
                "illustration" => "images/bug-spray.png",
                'label' => "Forfait emménagement/déménagement",
                "label_en" => 'Move in/out package'
            ],
            [
                "parent_id" => 3,
                "illustration" => "images/bug-spray.png",
                'label' => "Inspection antiparasitaire",
                "label_en" => 'Pest inspection'
            ],

            [
                "parent_id" => 4,
                "illustration" => "images/rubbish.png",
                'label' => "Enlèvement des déchets mixtes",
                "label_en" => 'Mixed waste removal'
            ],
            [
                "parent_id" => 4,
                "illustration" => "images/rubbish.png",
                'label' => "Enlèvement des déchets verts",
                "label_en" => 'Green waste removal'
            ],
            [
                "parent_id" => 4,
                "illustration" => "images/rubbish.png",
                'label' => "Suppression des constructeurs",
                "label_en" => 'Builders removal'
            ],

            [
                "parent_id" => 5,
                "illustration" => "images/handyman.png",
                'label' => "Homme à tout faire",
                "label_en" => 'Handyman'
            ],
            [
                "parent_id" => 5,
                "illustration" => "images/handyman.png",
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
