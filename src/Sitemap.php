<?php

namespace Superkozel\Sitemap;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Sitemap implements IteratorAggregate
{
    /**
     * @var SitemapPage[]
     */
    protected array $list;

    /**
     * @param  SitemapPage[]  $list
     */
    public function setList($list): Sitemap
    {
        $this->list = $list;

        return $this;
    }

    /**
     * @return SitemapPage[]
     */
    public function getList()
    {
        return $this->list;
    }

    public function addChild(SitemapPage $child)
    {
        $this->list[] = $child;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->list);
    }
}