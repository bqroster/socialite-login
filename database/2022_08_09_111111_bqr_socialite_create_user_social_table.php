<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUserSocialTable
 */
class BqrSocialiteCreateUserSocialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $relationshipTable = socialite_relationship_table();
        Schema::create(socialite_table_name(), function (Blueprint $table) use ($relationshipTable) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign( 'user_id' )
                ->references( 'id' )
                ->on( $relationshipTable );
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(socialite_table_name());
    }
}
