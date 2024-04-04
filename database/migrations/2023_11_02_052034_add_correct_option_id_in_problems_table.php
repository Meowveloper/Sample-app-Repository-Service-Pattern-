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
        Schema::table('problems', function (Blueprint $table) {
            $table->unsignedBigInteger('correct_option_id')->nullable()->after('expected_time_to_complete');
            $table->foreign('correct_option_id')->references('id')->on('problem_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('problems', function (Blueprint $table) {
            $table->dropForeign(['correct_option_id']);
            $table->dropColumn('correct_option_id');
        });
    }
};
