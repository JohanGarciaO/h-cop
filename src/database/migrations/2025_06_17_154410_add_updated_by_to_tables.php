<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('check_in_by')->nullable()->after('check_in_at')->constrained('users')->nullOnDelete();
            $table->foreignId('check_out_by')->nullable()->after('check_out_at')->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->after('created_at')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('updated_at')->constrained('users')->nullOnDelete();
        });

        Schema::table('guests', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('created_at')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('updated_at')->constrained('users')->nullOnDelete();
        });

        Schema::table('committees', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('created_at')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('updated_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('check_in_by');
            $table->dropConstrainedForeignId('check_out_by');
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('updated_by');
        });

        Schema::table('guests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('updated_by');
        });

        Schema::table('committees', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('updated_by');
        });
    }
};
