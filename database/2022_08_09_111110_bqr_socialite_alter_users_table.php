<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUserSocialTable
 */
class BqrSocialiteAlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $relationshipModel = config('socialite-login.relationship.model');
        $relationshipTable = (new $relationshipModel)->getTable();
        Schema::table($relationshipTable, function (Blueprint $table) {
            $table->string('avatar')
                ->nullable()
                ->after(config('socialite-login.relationship.migration.after'));

            $table->string('register_with')
                ->nullable()
                ->after('avatar');

            $table->string('login_with')
                ->nullable()
                ->after('register_with');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $relationshipModel = config('socialite-login.relationship.model');
        $relationshipTable = (new $relationshipModel)->getTable();
        Schema::table($relationshipTable, function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('login_with');
            $table->dropColumn('register_with');
        });
    }
}
