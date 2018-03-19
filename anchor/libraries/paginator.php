<?php

/**
 * paginator class
 * Paginates sets of models
 */
class paginator
{
    /**
     * Holds the paginated results
     *
     * @var array
     */
    public $results = [];

    /**
     * Holds the result count
     *
     * @var int
     */
    public $count = 0;

    /**
     * Holds the page offset
     *
     * @var int
     */
    public $page = 1;

    /**
     * Holds the page limit
     *
     * @var int
     */
    public $perpage = 10;

    /**
     * Holds the translated label for the first page link
     *
     * @var string
     */
    public $first;

    /**
     * Holds the translated label for the last page link
     *
     * @var string
     */
    public $last;

    /**
     * Holds the translated label for the next page link
     *
     * @var string
     */
    public $next;

    /**
     * Holds the translated label for the previous page link
     *
     * @var string
     */
    public $prev;

    /**
     * Holds the URL to paginate
     *
     * @var string
     */
    protected $url;

    /**
     * paginator constructor
     *
     * @param array  $results results to paginate
     * @param int    $count   result count
     * @param int    $page    page offset
     * @param int    $perpage page limit
     * @param string $url     base URL
     */
    public function __construct($results, $count, $page, $perpage, $url)
    {
        $this->results = $results;
        $this->count   = $count;
        $this->page    = $page;
        $this->perpage = $perpage;
        $this->url     = rtrim($url, '/');

        $this->first = __('global.first');
        $this->last  = __('global.last');
        $this->next  = __('global.next');
        $this->prev  = __('global.previous');
    }

    /**
     * Generates the previous page link
     *
     * @param string|null $text    (optional) link text
     * @param string      $default (optional) fallback if no previous page
     * @param array       $attrs   (optional) attributes for the link element
     *
     * @return string
     */
    public function prev_link($text = null, $default = '', $attrs = [])
    {
        if (is_null($text)) {
            $text = $this->next;
        }

        $pages = ceil($this->count / $this->perpage);

        if ($this->page < $pages) {
            $page = $this->page + 1;

            return $this->link($text, array_merge(
                ['href' => $this->url . '/' . $page], $attrs
            ));
        }

        return $default;
    }

    /**
     * Generates a pagination link
     *
     * @param string $text  link text
     * @param array  $attrs (optional) attributes for the link element
     *
     * @return string
     */
    public function link($text, $attrs = [])
    {
        $attr = '';

        if (is_string($attrs)) {
            $attr = 'href="' . $attrs . '"';
        } else {
            foreach ($attrs as $key => $val) {
                $attr .= $key . '="' . $val . '" ';
            }
        }

        return '<a ' . trim($attr) . '>' . $text . '</a>';
    }

    /**
     * Generates a next page link
     *
     * @param string|null $text    (optional) link text
     * @param string      $default (optional) fallback if no previous page
     * @param array       $attrs   (optional) attributes for the link element
     *
     * @return string
     */
    public function next_link($text = null, $default = '', $attrs = [])
    {
        if (is_null($text)) {
            $text = $this->prev;
        }

        if ($this->page > 1) {
            $page = $this->page - 1;

            return $this->link($text, array_merge(
                ['href' => $this->url . '/' . $page], $attrs
            ));
        }

        return $default;
    }

    /**
     * Generates the pagination links
     *
     * @return string
     */
    public function links()
    {
        $html = '';

        $pages = ceil($this->count / $this->perpage);
        $range = 4;

        if ($pages > 1) {
            if ($this->page > 1) {
                $page = $this->page - 1;

                $html = '<a href="' . $this->url . '">' . $this->first . '</a>
					<a href="' . $this->url . '/' . $page . '">' . $this->prev . '</a>';
            }

            for ($i = $this->page - $range; $i < $this->page + $range; $i++) {
                if ($i < 0) {
                    continue;
                }

                $page = $i + 1;

                if ($page > $pages) {
                    break;
                }

                if ($page == $this->page) {
                    $html .= ' <strong>' . $page . '</strong> ';
                } else {
                    $html .= ' ' . $this->link($page, $this->url . '/' . $page) . ' ';
                }
            }

            if ($this->page < $pages) {
                $page = $this->page + 1;

                $html .= $this->link($this->next, $this->url . '/' . $page)
                         . $this->link($this->last, $this->url . '/' . $pages);
            }
        }

        return $html;
    }
}
