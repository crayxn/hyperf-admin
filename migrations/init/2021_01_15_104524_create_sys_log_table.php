<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateSysLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sys_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("node")->nullable();
            $table->string("action", 30)->nullable();
            $table->integer("sys_user_id")->nullable();
            $table->string("extend")->nullable();
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
        Schema::dropIfExists('sys_log');
    }
}
