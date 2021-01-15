<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateSysUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sys_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name", 20)->nullable();
            $table->string("phone", 20)->nullable();
            $table->string("password")->nullable();
            $table->string("salt", 64)->nullable();
            $table->string("remark")->nullable();
            $table->string("avatar")->nullable();
            $table->string("authorize")->nullable();
            $table->tinyInteger("status")->default(1);
            $table->tinyInteger("is_deleted")->default(0);
            $table->integer("created_at")->nullable();
            $table->integer("updated_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_user');
    }
}
