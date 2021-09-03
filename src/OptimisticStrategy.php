<?php
/**
 * Created by PhpStorm.
 * User: zys
 * Date: 2021/9/1
 * Time: 15:58
 */

namespace Really4you\LaravelOptimistic;

class OptimisticStrategy
{
    /**
     * default name of the "optimistic" column.
     *
     * @var string
     */
    const VERSION_AT = 'version_at';

    /**
     * default num of the "optimistic" column.
     *
     * @var int
     */
    const VERSION_AT_DEFAULT_NUM = 1;
}

