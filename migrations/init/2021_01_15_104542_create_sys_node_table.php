<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateSysNodeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sys_node', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("node")->nullable();
            $table->string("title")->nullable();
            $table->tinyInteger("is_on")->default(1);
            $table->integer("created_at")->nullable();
            $table->integer("updated_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_node');
    }
}
