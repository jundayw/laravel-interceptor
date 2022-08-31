<?php

namespace Jundayw\LaravelInterceptor\Contracts;

interface Interceptor
{
    /**
     * @param string $content
     * @param string $type
     * @param callable|null $pass
     * @param callable|null $review
     * @param callable|null $replace
     * @param callable|null $block
     * @return mixed
     */
    public function filter(string $content, string $type, callable $pass = null, callable $review = null, callable $replace = null, callable $block = null): mixed;

}
