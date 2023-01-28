<?php

namespace Superkozel\Sitemap;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class SitemapPage implements IteratorAggregate
{
    /**
     * @var SitemapPage[]
     */
    protected array $children = [];

    /**
     * @var string
     */
    protected string $title;

    /**
     * @var string
     */
    protected string $url;

    public static function create(): static
    {
        return new static;
    }

    /**
     * @param  SitemapPage[]  $children
     */
    public function setChildren(array $children): static
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return SitemapPage[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setUrl($url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function addChild(SitemapPage $child): static
    {
        $this->children[] = $child;

        return $this;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->children);
    }
}