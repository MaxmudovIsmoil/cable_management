<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('language')->nullable();
            $table->enum('status', [1, 0])->default(1)->comment('1-active, 0-no active');
            $table->enum('rule', [1, 0])->default(0)->comment('1-admin, 0-user');
            $table->enum('can_create_cable', [1, 0])->default(0)
                ->comment('1-create cable, 0-not create cable');
            $table->enum('can_update_cable', [1, 0])->default(0)
                ->comment('1-update cable, 0-not update cable');
            $table->rememberToken();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
        });

        DB::table('users')->insert([
            [
                "name" => 'Administrator',
                "email" => 'admin@etc.uz',
                "username" => 'admin',
                "password" => Hash::make(123),
                "status" => 1,
                "rule" => 1,
                "language" => 'en',
                "can_create_cable" => 1,
                "can_update_cable" => 1,
            ],
            [
                "name" => 'User',
                "email" => 'user@etc.uz',
                "username" => 'user',
                "password" => Hash::make(123),
                'status' => 1,
                'rule' => '0',
                'language' => 'ru',
                "can_create_cable" => '1',
                "can_update_cable" => '0',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
