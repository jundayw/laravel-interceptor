<?php

namespace Jundayw\LaravelInterceptor;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Traits\Macroable;
use Jundayw\LaravelInterceptor\Contracts\Filter;
use Jundayw\LaravelInterceptor\Contracts\Interceptor as InterceptorContract;
use Jundayw\LaravelInterceptor\Events\BlockEvent;
use Jundayw\LaravelInterceptor\Events\PassEvent;
use Jundayw\LaravelInterceptor\Events\ReplaceEvent;
use Jundayw\LaravelInterceptor\Events\ReviewEvent;

class Interceptor implements InterceptorContract
{
    use Macroable;

    private Filter $filter;

    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param string $content
     * @param callable|null $pass
     * @param callable|null $block
     * @return mixed
     */
    public function username(string $content, callable $pass = null, callable $block = null): mixed
    {
        return $this->filter(
            content: $content,
            type: 'username',
            pass: $pass,
            block: $block
        );
    }

    /**
     * @param string $content
     * @param callable|null $pass
     * @param callable|null $review
     * @param callable|null $block
     * @return mixed
     */
    public function usernick(string $content, callable $pass = null, callable $review = null, callable $block = null): mixed
    {
        return $this->filter(
            content: $content,
            type: 'usernick',
            pass: $pass,
            review: $review,
            block: $block
        );
    }

    /**
     * @param string $content
     * @param callable|null $pass
     * @param callable|null $replace
     * @param callable|null $block
     * @return mixed
     */
    public function message(string $content, callable $pass = null, callable $replace = null, callable $block = null): mixed
    {
        return $this->filter(
            content: $content,
            type: 'message',
            pass: $pass,
            replace: $replace,
            block: $block
        );
    }

    /**
     * @param string $content
     * @param callable|null $pass
     * @param callable|null $review
     * @param callable|null $replace
     * @param callable|null $block
     * @return mixed
     */
    public function content(string $content, callable $pass = null, callable $review = null, callable $replace = null, callable $block = null): mixed
    {
        return $this->filter(
            content: $content,
            type: 'content',
            pass: $pass,
            review: $review,
            replace: $replace,
            block: $block
        );
    }

    /**
     * @param string $content
     * @param string $type
     * @param callable|null $pass
     * @param callable|null $review
     * @param callable|null $replace
     * @param callable|null $block
     * @return mixed
     */
    public function filter(string $content, string $type, callable $pass = null, callable $review = null, callable $replace = null, callable $block = null): mixed
    {
        $collection = $this->filter->handle($content, $type);

        if ($collect = $collection->where($type, Filter::BLOCK)->first()) {
            Event::dispatch(BlockEvent::class, [$collect->get('matches'), $content, $collect]);
            return is_callable($block) ? $block($collect->get('matches'), $content, $collect) : $content;
        }
        if ($collect = $collection->where($type, Filter::REVIEW)->first()) {
            Event::dispatch(ReviewEvent::class, [$collect->get('matches'), $content, $collect]);
            return is_callable($review) ? $review($collect->get('matches'), $content, $collect) : $content;
        }
        if ($collect = $collection->where($type, Filter::REPLACE)->first()) {
            Event::dispatch(ReplaceEvent::class, [$collect->get('matches'), $content, $collect]);
            return is_callable($replace) ? $replace($collect->get('matches'), $content, $collect) : $content;
        }
        if ($collect = $collection->where($type, Filter::PASS)->first()) {
            Event::dispatch(PassEvent::class, [$collect->get('matches'), $content, $collect]);
            return is_callable($pass) ? $pass($collect->get('matches'), $content, $collect) : $content;
        }

        return $content;
    }

}
