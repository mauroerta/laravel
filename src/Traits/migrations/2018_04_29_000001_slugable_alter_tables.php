<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SlugableAlterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $tables = config('me_trait.slugable.tables', []);

        foreach ($tables as $table_name => $options) {
            if (Schema::hasTable($table_name)) {
                if(!is_array($options)) $options = [];

                $options['column'] = isset($options['column']) ? $options['column'] : 'slug';

                Schema::table($table_name, function (Blueprint $table) use ($options) {
                    $table->string($options['column'])->nullable();
                });
            }
        }
    }
}
