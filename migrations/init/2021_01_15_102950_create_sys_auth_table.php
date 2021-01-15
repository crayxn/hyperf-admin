<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateSysAuthTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sys_auth', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name", 20);
            $table->tinyInteger("status")->default(1);
            $table->tinyInteger("sort")->default(1);
            $table->string("desc")->nullable();
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
        Schema::dropIfExists('sys_auth');
    }
}
