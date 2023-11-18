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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\StudyProgram::class)->nullable();
            $table->foreignIdFor(\App\Models\Province::class)->nullable();
            $table->foreignIdFor(\App\Models\District::class)->nullable();
            $table->foreignIdFor(\App\Models\Subdistrict::class)->nullable();
            $table->string('name');
            $table->string('registration_number');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);

            $table->foreignIdFor(\App\Models\Subcriteria::class, 'c1_subcriteria_id')->nullable();
            $table->foreignIdFor(\App\Models\Subcriteria::class, 'c2_subcriteria_id')->nullable();
            $table->foreignIdFor(\App\Models\Subcriteria::class, 'c3_subcriteria_id')->nullable();
            $table->foreignIdFor(\App\Models\Subcriteria::class, 'c4_subcriteria_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
