<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = config('me_trait.tables', []);

        foreach ($tables as $key => $table_name) {
            if (Schema::hasTable($table_name)) {
                Schema::table($table_name, function (Blueprint $table) {
                    $table->unsignedInteger(config('me_trait.drafted_by_column', 'drafted_by'))->nullable();
                    $table->foreign(config('me_trait.drafted_by_column', 'drafted_by'))->references('id')->on('users');
                    $table->timestamp(config('me_trait.drafted_at_column', 'drafted_at'))->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = config('me_trait.tables', []);

        foreach ($tables as $key => $table_name) {
            if (Schema::hasTable($table_name)) {
                Schema::table($table_name, function (Blueprint $table) {
                    $table->dropColumn([
                        config('me_trait.drafted_by_column', 'drafted_by'),
                        config('me_trait.drafted_at_column', 'drafted_at')
                    ]);
                });
            }
        }
    }
}