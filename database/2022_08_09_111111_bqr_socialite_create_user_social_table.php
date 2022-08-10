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
        $relationshipModel = config('socialite-login.relationship.model');
        Schema::create(config('socialite-login.table.db'), function (Blueprint $table) use ($relationshipModel) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign( 'user_id' )
                ->references( 'id' )
                ->on( (new $relationshipModel)->getTable() );
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
        Schema::dropIfExists(config('socialite-login.table.db'));
    }
}
