<?php

namespace Jundayw\LaravelInterceptor\Contracts;

use Illuminate\Support\Collection;

interface Filter
{
    public const PASS    = 'PASS';   // 正常
    public const REPLACE = 'REPLACE';// 替换
    public const REVIEW  = 'REVIEW'; // 审核
    public const BLOCK   = 'BLOCK';  // 黑名单

    /**
     * 支持验证类型
     *
     * @var array|string[]
     */
    public const allowTypes = [
        'username',
        'usernick',
        'message',
        'content',
    ];

    /**
     * @param string $content
     * @param string $type
     * @return Collection
     */
    public function handle(string $content, string $type): Collection;
}
