<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class SysUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Hyperf\DbConnection\Db::table("sys_user")->insert([
            "name" => "admin",
            "phone" => "123456",
            "password" => "3f16207fd3406bdc66fa2f5e0ae3f549",
            "salt" => "4ec9dad0bae7b035fcce1a3a856ad3df",
            "created_at" => time(),
            "updated_at" => time()
        ]);
    }
}
