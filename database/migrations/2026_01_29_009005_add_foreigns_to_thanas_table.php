<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('thanas')) {
            Schema::table('thanas', function (Blueprint $table) {
                $table
                    ->foreign('district_id')
                    ->references('id')
                    ->on('districts')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thanas', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
        });
    }
};
