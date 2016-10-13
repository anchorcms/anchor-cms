<?php
namespace Anchorcms\Plugins\Debugger;

use Anchorcms\Events\FilterEvent;
use Anchorcms\Events\MiddlewareEvent;
use Anchorcms\Plugin as AnchorPlugin;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Plugin extends AnchorPlugin
{
    public function getSubscribedEvents(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener('middleware', [$this, 'loadDebugMiddleware']);
        $dispatcher->addListener('filters', [$this, 'insertContactForm']);
    }

    public function loadDebugMiddleware(MiddlewareEvent $middlewares)
    {
        $middlewares->prepend(new Middleware\Debug($middlewares->getContainer()));
    }

    public function insertContactForm(FilterEvent $filters)
    {
        $filters->addFilter('contactForm', [$this, 'createContactForm']);
    }

    public function createContactForm($request, $variables)
    {
        if (!key_exists('id', $variables)) return '';

        $contactForm = '<form id="contact-form-' . $variables['id'] . '">';
        $contactForm .= '<h3>Kontaktformular ' . $variables['id'] . '</h3>';
        $contactForm .= '</form>';

        return $contactForm;
    }
}
