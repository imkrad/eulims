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
        Schema::table('tsrs', function (Blueprint $table) {
            $table->tinyInteger('purpose_id')->unsigned()->nullable()->after('status_id');
            $table->foreign('purpose_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tsrs', function (Blueprint $table) {
            //
        });
    }
};
