<?php

namespace Anchorcms;

class Paginator
{
    protected $page;

    protected $pages;

    protected $url;

    protected $params;

    protected $linkPageFormat = '<a href="{{ url }}">{{ text }}</a>';

    protected $currentPageFormat = '<strong>{{ text }}</strong>';

    protected $pageNumberFormat = '<span>Page {{ page }} of  {{ pages }}</span>';

    protected $langFirstPage = 'First';

    protected $langNextPage = 'Next';

    protected $langPreviousPage = 'Previous';

    protected $langLastPage = 'Last';

    protected $range = 4;

    public function __construct(string $url, int $page, int $pages, array $params = [])
    {
        $this->url = $url;
        $this->page = max(1, $page);
        $this->pages = $pages;
        $this->params = array_filter($params);
    }

    protected function link(int $page, string $text): string
    {
        $query = http_build_query(array_merge($this->params, ['page' => $page]));
        $url = sprintf('%s?%s', $this->url, $query);

        return str_replace(['{{ url }}', '{{ text }}'], [$url, $text], $this->linkPageFormat);
    }

    protected function number(int $page): string
    {
        return str_replace('{{ text }}', $page, $this->currentPageFormat);
    }

    public function links(): string
    {
        $links = [];

        if ($this->pages == 1) {
            return '';
        }

        if ($this->page > 1) {
            $links[] = $this->link(1, $this->langFirstPage);

            $links[] = $this->link($this->page - 1, $this->langPreviousPage);
        }

        for ($i = $this->page - $this->range; $i < $this->page + $this->range; ++$i) {
            if ($i < 0) {
                continue;
            }

            $page = $i + 1;

            if ($page > $this->pages) {
                break;
            }

            if ($page == $this->page) {
                $links[] = $this->number($page);
            } else {
                $links[] = $this->link($page, $page);
            }
        }

        if ($this->page < $this->pages) {
            $links[] = $this->link($this->page + 1, $this->langNextPage);

            $links[] = $this->link($this->pages, $this->langLastPage);
        }

        $links[] = str_replace(['{{ page }}', '{{ pages }}'], [$this->page, $this->pages], $this->pageNumberFormat);

        return implode(' ', $links);
    }
}
