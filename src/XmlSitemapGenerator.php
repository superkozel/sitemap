<?php

namespace Superkozel\Sitemap;

use XMLWriter;

class XmlSitemapGenerator
{
    public const FREQ_WEEKLY = 'weekly';
    public const FREQ_DAILY = 'daily';

    protected string $host;
    protected XMLWriter $writer;
    private ?string $filePath;

    public static function create(string $host, ?string $filePath = null): static
    {
        return new static($host, $filePath);
    }

    public function __construct(string $host, ?string $filePath = null)
    {
        $this->host = $host;
        $this->writer = new XMLWriter();
        $this->filePath = $filePath;
    }
    public function add(
        string $loc,
        float $priority = null,
        string $changefreq = null,
        array $images = []
    ): static {
        $this->writer->startElement('url');
        $this->writer->writeElement('loc', $this->host.$loc);
        if ($priority) {
            $this->writer->writeElement('priority', (string)round($priority, 2));
        }
        if ($changefreq) {
            $this->writer->writeElement('changefreq', $changefreq);
        }
        foreach ($images as $image) {
            $this->writer->startElement('image:image');

            $this->writer->writeElement('image:loc', $this->host.$image['loc']);

            if (!empty($image['title'])) {
                $this->writer->writeElement('image:title', $image['title']);
            }

            $this->writer->endElement();
        }
        $this->writer->endElement();

        if ($this->filePath === null) {
            echo $this->writer->outputMemory();
        }

        return $this;
    }

    public function start(): static
    {
        if ($this->filePath !== null) {
            $this->writer->openURI($this->filePath);
        }
        else {
            $this->writer->openMemory();
        }

        $this->writer->startDocument('1.0', 'UTF-8');

        $this->writer->setIndent(true);

        $this->writer->startElement('urlset');
        $this->writer->writeAttribute('xmlns', 'https://www.sitemaps.org/schemas/sitemap/0.9');
        $this->writer->writeAttribute('xmlns:image', 'https://www.google.com/schemas/sitemap-image/1.1');

        return $this;
    }

    public function end(): static
    {
        $this->writer->endElement();
        $this->writer->endDocument();

        if ($this->filePath) {
            $this->writer->flush();
        } else {
            echo $this->writer->outputMemory();
        }

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

    public function getWriter(): XMLWriter
    {
        return $this->writer;
    }
}