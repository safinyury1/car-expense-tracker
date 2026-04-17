<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->date('service_date')->nullable()->after('due_date');
            $table->decimal('service_cost', 10, 2)->nullable()->after('service_date');
            $table->text('service_notes')->nullable()->after('service_cost');
            $table->string('service_receipt')->nullable()->after('service_notes');
            $table->integer('next_due_odometer')->nullable()->after('service_receipt');
            $table->date('next_due_date')->nullable()->after('next_due_odometer');
            $table->string('service_type')->default('reminder')->after('is_completed');
        });
    }

    public function down()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn([
                'service_date', 'service_cost', 'service_notes', 
                'service_receipt', 'next_due_odometer', 'next_due_date', 'service_type'
            ]);
        });
    }
};