<?php
namespace Anchorcms\Plugins\ContactFormPlugin\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Anchorcms\Controllers\AbstractController;

/**
 * Class Forms.
 * Provides all controllers for the contact forms plugin
 *
 * @package Anchorcms\Plugins\ContactFormPlugin\Controllers
 */
class Forms extends AbstractController
{
    /**
     * get a list of contact forms
     *
     * @access public
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function getIndex(Request $request, Response $response, array $args): Response
    {
        $vars = [];

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Contact Forms';

        $vars['contactForms'] = [];

        /**
         * debug foo form.
         */
        $foo = new class
        {
            public function getName()
            {
                return 'foo form';
            }

            public function isActive()
            {
                return false;
            }

            public function getId()
            {
                return '0';
            }
        };
        array_push($vars['contactForms'], $foo);

        // TODO: This path juggling is reeeally awkward.
        $this->renderTemplate($response, 'layouts/default', '../../web/plugins/ContactFormPlugin/Views/index', $vars);

        return $response;
    }

    /**
     * get the contact form creation page
     * @access public
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function getCreate(Request $request, Response $response, array $args): Response
    {
        $vars = [];

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'New contact form';

        // TODO: This path juggling is reeeally awkward.
        $this->renderTemplate($response, 'layouts/default', '../../web/plugins/ContactFormPlugin/Views/create', $vars);

        return $response;
    }

    /**
     * get the contact form edit page
     * @access public
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function getEdit(Request $request, Response $response, array $args): Response
    {
        $vars = [];

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');

        /**
         * debug foo form.
         */
        $foo = new class
        {
            public function getName()
            {
                return 'foo form';
            }

            public function isActive()
            {
                return false;
            }

            public function getId()
            {
                return '0';
            }
        };

        $vars['contactForm'] = $foo;
        $vars['title'] = 'Contact Form ' . $foo->getName();

        // TODO: This path juggling is reeeally awkward.
        $this->renderTemplate($response, 'layouts/default', '../../web/plugins/ContactFormPlugin/Views/edit', $vars);

        return $response;
    }
}
