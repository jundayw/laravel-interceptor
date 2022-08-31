<?php

namespace Jundayw\LaravelInterceptor\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Jundayw\LaravelInterceptor\Contracts\Filter;
use Jundayw\LaravelInterceptor\Models\Interceptors;

class LocalFilter implements Filter
{
    use Macroable;

    private Interceptors $interceptor;

    public function __construct(Interceptors $interceptor)
    {
        $this->interceptor = $interceptor;
    }

    public function handle(string $content, string $type): Collection
    {
        return cache()->store(config('interceptor.cache.driver'))->remember(static::class, config('interceptor.cache.ttl'), function () use ($type) {
            return $this->interceptor->where($type, '<>', self::PASS)->get();
        })->where(function (Interceptors $interceptor) use ($content) {
            $keywords = $interceptor->getAttribute('keywords');
            // 转义元字符并生成正则
            $keywords = sprintf('/%s/iu', addcslashes($keywords, '\/^$()[]{}|+?.*'));
            // 将 {n} 转换为 .{0,n}
            $pattern = preg_replace('/\\\{(\d{1,})\\\}/', '.{0,${1}}', $keywords);
            // 匹配测试
            if (preg_match($pattern, $content, $matches)) {
                return $interceptor->setAttribute('matches', $matches);
            }
            return null;
        })->map(function (Interceptors $interceptor) {
            return collect($interceptor->toArray());
        });
    }

}
