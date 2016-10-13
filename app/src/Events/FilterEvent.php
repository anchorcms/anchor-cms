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
            $variablePairs = explode(' ', $matches[1]);
            $variables = [];

            foreach ($variablePairs as $variablePair) {

                // explode the key-value pair
                $pair = explode(':', $variablePair);

                if (!$pair) {
                    continue;
                }

                if (count($pair) < 2) {
                    $variables[$pair[0]] = true;
                    continue;
                }

                // set key and value, remove quotes if present
                $variables[$pair[ 0 ]] = trim($pair[1], '"');
            }

            // call the filter with all given variables
            return call_user_func_array($filter, [
                'request' => $request,
                'variables' => $variables
            ]);
        };
    }

    /**
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
     * returns all filters
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
