<?php

namespace Jundayw\LaravelInterceptor\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed username(string $content, callable $pass = null, callable $block = null)
 * @method static mixed usernick(string $content, callable $pass = null, callable $review = null, callable $block = null)
 * @method static mixed message(string $content, callable $pass = null, callable $replace = null, callable $block = null)
 * @method static mixed content(string $content, callable $pass = null, callable $review = null, callable $replace = null, callable $block = null)
 * @method static mixed filter(string $content, string $type, callable $pass = null, callable $review = null, callable $replace = null, callable $block = null)
 *
 * @method static void macro($name, $macro)
 * @method static void mixin($mixin, $replace = true)
 * @method static bool hasMacro($name)
 * @method static void flushMacros()
 *
 * @see \Jundayw\LaravelInterceptor\Interceptor
 * @see Macroable
 */
class Interceptor extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Jundayw\LaravelInterceptor\Contracts\Interceptor::class;
    }

}
