<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateSysAuthNodeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sys_auth_node', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("auth_id");
            $table->string("node")->nullable();
            $table->integer("node_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_auth_node');
    }
}
