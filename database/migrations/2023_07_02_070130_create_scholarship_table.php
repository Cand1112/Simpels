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
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\StudyProgram::class)->nullable();
            $table->foreignIdFor(\App\Models\Province::class)->nullable();
            $table->foreignIdFor(\App\Models\District::class)->nullable();
            $table->foreignIdFor(\App\Models\Subdistrict::class)->nullable();
            $table->string('name');
            $table->foreignIdFor(\App\Models\Subcriteria::class, 'c1_subcriteria_id');
            $table->foreignIdFor(\App\Models\Subcriteria::class, 'c2_subcriteria_id');
            $table->foreignIdFor(\App\Models\Subcriteria::class, 'c3_subcriteria_id');
            $table->foreignIdFor(\App\Models\Subcriteria::class, 'c4_subcriteria_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
