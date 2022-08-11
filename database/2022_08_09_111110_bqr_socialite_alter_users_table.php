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
        Schema::table(socialite_relationship_table(), function (Blueprint $table) {
            $table->string('nickname')
                ->nullable()
                ->after(config('socialite-login.relationship.migration.after'));

            $table->string('avatar')
                ->nullable()
                ->after('nickname');

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
        Schema::table(socialite_relationship_table(), function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'nickname',
                'login_with',
                'register_with'
            ]);
        });
    }
}
