<?php

namespace Jundayw\LaravelInterceptor\Events;

use Illuminate\Support\Collection;

class ReplaceEvent
{
    public array      $matches;
    public string     $content;
    public Collection $collect;

    /**
     * Create a new event instance.
     *
     * @param array $matches
     * @param string $content
     * @param Collection $collect
     */
    public function __construct(array $matches, string $content, Collection $collect)
    {
        $this->matches = $matches;
        $this->content = $content;
        $this->collect = $collect;
    }

}
