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
        Schema::create('reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id()->unsigned();
            $table->unsignedBigInteger('report_status_id');
            $table->unsignedBigInteger('report_type_id');
            $table->string('filename')->nullable();
            $table->timestamps();
            $table->timestamp('generated_at')->nullable();

            $table->foreign('report_status_id')
                ->references('id')
                ->on('report_statuses');

            $table->foreign('report_type_id')
                ->references('id')
                ->on('report_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
