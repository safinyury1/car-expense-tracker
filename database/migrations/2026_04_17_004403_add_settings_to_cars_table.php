<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('distance_unit')->default('km')->after('photo');
            $table->string('volume_unit')->default('liters')->after('distance_unit');
            $table->string('currency')->default('RUB')->after('volume_unit');
        });
    }

    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['distance_unit', 'volume_unit', 'currency']);
        });
    }
};