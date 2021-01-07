<?php
/**
 *  +-------------------------------------
 *  | MADE IN RETURN
 *  |-------------------------------------
 *  | Time: 2020/11/20
 *  | Author: CRAYOON <so.wo@foxmail.com>
 *  +--------------------------------------
 */

declare(strict_types=1);


namespace App\Annotation;


use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * Class Node
 * @Annotation
 * @Target({"METHOD", "CLASS"})
 * @package App\Annotation
 */
class Node extends AbstractAnnotation
{
    /**
     * @var string
     */
    public $value;

    public function __construct($value = null)
    {
        parent::__construct($value);
    }
}