<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateSysMenuTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sys_menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("pid")->default(0);
            $table->string("title")->nullable();
            $table->string("node")->nullable();
            $table->string("icon")->nullable();
            $table->string("url")->nullable();
            $table->string("params")->nullable();
            $table->string("target", 20)->default('_self')->nullable();
            $table->tinyInteger("sort")->default(1);
            $table->tinyInteger("status")->default(1);
            $table->integer("created_at")->nullable();
            $table->integer("updated_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_menu');
    }
}
