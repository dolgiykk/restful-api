<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'login');
            $table->addColumn('string', 'first_name')->nullable();
            $table->addColumn('string', 'last_name')->nullable();
            $table->addColumn('string', 'second_name')->nullable();
            $table->date('birthday')->nullable();
            $table->enum('sex', ['male', 'female'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'second_name', 'birthday', 'sex']);
            $table->renameColumn('login', 'name');
        });
    }
};
