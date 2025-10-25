<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('empleados', 'user_id')) {
            Schema::table('empleados', function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }

        $firstUserId = DB::table('users')->min('id');
        if ($firstUserId) {
            DB::table('empleados')
                ->whereNull('user_id')
                ->update(['user_id' => $firstUserId]);
        }

        Schema::table('empleados', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('empleados', 'user_id')) {
            Schema::table('empleados', function (Blueprint $table) {
                $table->dropUnique('empleados_user_id_unique');
                $table->dropConstrainedForeignId('user_id');
            });
        }
    }
};
