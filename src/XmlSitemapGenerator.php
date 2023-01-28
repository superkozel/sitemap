<?php

namespace Superkozel\Sitemap;

use XMLWriter;

class XmlSitemapGenerator
{
    public const FREQ_WEEKLY = 'weekly';
    public const FREQ_DAILY = 'daily';

    private string $host;
    private bool $secure = false;
    protected XMLWriter $writer;

    public static function create(): static
    {
        return new XmlSitemapGenerator();
    }

    public function __construct()
    {
        $this->writer = new XMLWriter();
    }

    public function protocol(): string
    {
        return $this->isSecure() ? 'https' : 'http';
    }

    public function add(
        string $loc,
        float $priority = null,
        string $changefreq = null,
        array $images = []
    ): static {
        $this->writer->startElement('url');
        $this->writer->writeElement('loc', $this->getHost().$loc);
        if ($priority) {
            $this->writer->writeElement('priority', $priority);
        }
        if ($changefreq) {
            $this->writer->writeElement('changefreq', $changefreq);
        }
        foreach ($images as $image) {
            $this->writer->startElement('image:image');

            $this->writer->writeElement('image:loc', $this->protocol().'://'.$this->getHost().$image['loc']);

            if (!empty($image['title'])) {
                $this->writer->writeElement('image:title', $image['title']);
            }

            $this->writer->endElement();
        }
        $this->writer->endElement();

        echo $this->writer->outputMemory();

        return $this;
    }

    public function start(): static
    {
        $this->writer->openMemory();

        $this->writer->startDocument('1.0', 'UTF-8');

        $this->writer->setIndent(true);

        $this->writer->startElement('urlset');
        $this->writer->writeAttribute('xmlns', 'https://www.sitemaps.org/schemas/sitemap/0.9');
        $this->writer->writeAttribute('xmlns:image', 'https://www.google.com/schemas/sitemap-image/1.1');

        echo $this->writer->outputMemory();

        return $this;
    }

    public function end(): static
    {
        $this->writer->endElement();

        $this->writer->endDocument();

        echo $this->writer->outputMemory();

        return $this;
    }

    public function setHost(string $host): static
    {
        $this->host = $host;

        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function isSecure(): bool
    {
        return $this->secure;
    }

    public function setSecure(bool $secure): static
    {
        $this->secure = $secure;

        return $this;
    }

    public function getWriter(): XMLWriter
    {
        return $this->writer;
    }
}