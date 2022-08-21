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
            $table->string('social_id')
                ->nullable()
                ->after(config('socialite-login.relationship.migration.after'));

            $table->string('social_token', 350)
                ->nullable()
                ->after('social_id');

            $table->string('social_refresh_token', 350)
                ->nullable()
                ->after('social_token');

            $table->string('nickname')
                ->nullable()
                ->after('social_refresh_token');

            $table->string('avatar_url')
                ->nullable()
                ->after('nickname');

            $table->string('register_with')
                ->nullable()
                ->after('avatar_url');

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
                'social_id',
                'social_token',
                'social_refresh_token',
                'nickname',
                'avatar_url',
                'register_with',
                'login_with',
            ]);
        });
    }
}
