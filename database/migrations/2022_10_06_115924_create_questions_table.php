<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->timestamps();

            $table->unsignedBigInteger('service_id');

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('cascade');
        });

        DB::table('questions')->insert([
            [
                "content" => "[{\"key\":\"enquiry_type\",\"type\":\"radio\",\"label\":\"Quel est le type de votre demande\",\"label_en\":\"What is type of your enquiry\",\"answer_label\":\"Type de demande\",\"answer_label_en\":\"Enquiry type\",\"required\":true,\"options\":[{\"label_en\":\"Domestic\",\"label\":\"Domestique\",\"value\":\"enquiryType1\"},{\"label_en\":\"Commercial\",\"label\":\"Commerciale\",\"value\":\"enquiryType2\"}]},{\"key\":\"habitation\",\"type\":\"radio\",\"label\":\"S'il vous plaît parlez-nous de votre habitat\",\"label_en\":\"Please tell us about your place\",\"answer_label\":\"Type d'habitation\",\"answer_label_en\":\"Habitation type\",\"required\":true,\"options\":[{\"label_en\":\"Studio\",\"label\":\"Studio\",\"value\":\"habitationType1\"},{\"label_en\":\"Flat/House\",\"label\":\"Appartement/Maison\",\"value\":\"habitationType2\"}]},{\"key\":\"deep_cleaning\",\"type\":\"radio\",\"label\":\"Voulez-vous que votre maison soit nettoyée en profondeur avant la désinfection\",\"label_en\":\"Do you want your home to be deep cleaned before the sanitization\",\"answer_label\":\"Nettoyage en profondeur de la maison\",\"answer_label_en\":\"Deep cleaning of the house\",\"required\":true,\"options\":[{\"label_en\":\"Yes\",\"label\":\"Oui\",\"value\":\"deepCleaningAnswer1\"},{\"label_en\":\"No, thank you\",\"label\":\"Non, merci\",\"value\":\"deepCleaningAnswer2\"}]},{\"key\":\"addition\",\"type\":\"textarea\",\"label\":\"Y a-t-il autre chose que vous voudriez que nous sachions ?\",\"label_en\":\"Is there anything else you'd like us to know ?\",\"answer_label\":\"En complément\",\"answer_label_en\":\"In addition\",\"maxlength\":255}]",
                "service_id" => 6
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
