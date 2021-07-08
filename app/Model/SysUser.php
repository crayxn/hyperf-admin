<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/10/27
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */


namespace App\Model;


use Hyperf\Scout\Searchable;
use Qbhy\HyperfAuth\Authenticatable;

/**
 * Class SysUser
 * @package App\Model
 */
class SysUser extends Model implements Authenticatable
{
    use Searchable;

    protected $table = "sys_user";


    public function getId()
    {
        return $this->id;
    }

    public static function retrieveById($key): ?Authenticatable
    {
        return self::query()->where("id","=",$key)->first();
    }
}