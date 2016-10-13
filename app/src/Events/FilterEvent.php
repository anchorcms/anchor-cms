<?php
namespace Anchorcms\Events;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\EventDispatcher\Event;
use \Anchorcms\Models\Page as PageModel;

class FilterEvent extends Event
{
    /**
     * holds all filters and their associated callbacks
     *
     * @access protected
     * @var array
     */
    protected $filters = [];

    /**
     * the sequence hinting the start of a filter
     *
     * @access protected
     * @var string
     */
    protected $filterStartDelimiter = '{{';

    /**
     * the sequence hinting the end of a filter
     *
     * @access protected
     * @var string
     */
    protected $filterEndDelimiter = '}}';

    /**
     * the request object as received from the client
     *
     * @access protected
     * @var
     */
    protected $request;

    /**
     * FilterEvent constructor.
     *
     * @param ServerRequestInterface $request
     * @constructor
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * adds a new filter to the stack
     *
     * @access public
     * @param string   $filterName
     * @param callable $filter
     * @return void
     */
    public function addFilter(string $filterName, callable $filter)
    {
        $request = $this->request;

        $this->filters[$this->createFilterRegex($filterName)] = function(&$matches) use ($request, $filter) {
            $attributePairs = explode(' ', $matches[1]);
            $attributes = [];

            foreach ($attributePairs as $attributePair) {

                // explode the key-value pair
                $pair = explode(':', $attributePair);

                // if there are no attributes
                if (!$pair) {
                    continue;
                }

                // if this attribute has no value assigned
                if (count($pair) < 2) {
                    $attributes[$pair[0]] = true;
                    continue;
                }

                // set key and value, remove quotes if present
                $attributes[$pair[ 0 ]] = trim($pair[1], '"');
            }

            // call the filter with all given attributes
            return call_user_func_array($filter, [
                'request' => $request,
                'attributes' => $attributes
            ]);
        };
    }

    /**
     * replaces all filter occurrences in a page with the filter callback result
     *
     * @access public
     * @param PageModel $page
     * @return string
     */
    public function applyFilters(PageModel $page): string
    {
        // replace each filter occurrence
        return preg_replace_callback_array($this->filters, $page->html) ?? $page->html;
    }

    /**
     * assembles the filter regex
     *
     * @access protected
     * @param string $filterName
     * @return string
     */
    protected function createFilterRegex(string $filterName): string
    {
        $regex = '/' . $this->filterStartDelimiter;
        $regex .= $filterName;
        $regex .= '\s?(.*?)';
        $regex .= $this->filterEndDelimiter . '/im';

        return $regex;
    }
}
