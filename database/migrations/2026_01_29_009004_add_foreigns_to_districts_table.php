<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('districts')) {
            // Check if foreign key already exists
            $keyExists = DB::select("
                SELECT COUNT(*) as count FROM information_schema.TABLE_CONSTRAINTS
                WHERE CONSTRAINT_SCHEMA = DATABASE()
                AND TABLE_NAME = 'districts'
                AND CONSTRAINT_NAME = 'districts_division_id_foreign'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            ");

            if ($keyExists[0]->count == 0) {
                Schema::table('districts', function (Blueprint $table) {
                    $table
                        ->foreign('division_id')
                        ->references('id')
                        ->on('divisions')
                        ->onUpdate('CASCADE')
                        ->onDelete('CASCADE');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('districts')) {
            $keyExists = DB::select("
                SELECT COUNT(*) as count FROM information_schema.TABLE_CONSTRAINTS
                WHERE CONSTRAINT_SCHEMA = DATABASE()
                AND TABLE_NAME = 'districts'
                AND CONSTRAINT_NAME = 'districts_division_id_foreign'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            ");

            if ($keyExists[0]->count > 0) {
                Schema::table('districts', function (Blueprint $table) {
                    $table->dropForeign(['division_id']);
                });
            }
        }
    }
};
