<?php

namespace Anchorcms\Controllers\Admin;

use Anchorcms\Filters;
use Anchorcms\Forms\ValidateToken;
use Anchorcms\Plugins\PluginManifest;
use Forms\Form;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;
use Validation\ValidatorFactory;
use Validation\ValidatorInterface;

class Plugins extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $plugins = $this->container['plugins']->getPlugins();
        $active = $this->container['plugins']->getActivePlugins($this->container['mappers.meta']);

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Plugins';

        $vars['plugins'] = $plugins;
        $vars['active'] = $active;

        $this->renderTemplate($response, 'layouts/default', 'plugins/index', $vars);

        return $response;
    }

    public function getOptions(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $plugin = $this->container['plugins']->getPluginByFolder($args['folder']);

        $vars['plugin'] = $plugin;

        if ($plugin->hasOptions()) {
            $form = $this->getOptionsForm($plugin, [
                'method' => 'post',
                'action' => $this->container['url']->to(sprintf('/admin/plugins/%s/save', $plugin->getFolder()))
            ]);
            $form->getElement('_token')->setValue($this->container['csrf']->token());
            $form->populate();
            $vars['options'] = $form;
        }

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = $plugin->getName();

        $this->renderTemplate($response, 'layouts/default', 'plugins/options', $vars);

        return $response;
    }

    public function postSaveOptions(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $plugin = $this->container['plugins']->getPluginByFolder($args['folder']);
        $form = $this->getOptionsForm($plugin);
        $input = Filters::withDefaults($request->getParsedBody(), $form->getFilters());
        $validator = ValidatorFactory::create($input, $form->getRules());

        // TODO: Persist options here

        if (false === $validator->isValid()) {
            $this->container['messages']->error($validator->getMessages());

            return $this->redirect($response, '/admin/plugins/' . $plugin->getFolder());
        }

        $this->container['messages']->success([sprintf('%s options updated', $plugin->getName())]);

        return $this->redirect($response, '/admin/plugins/' . $plugin->getFolder());
    }

    public function getActivate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->container['plugins']->activatePlugin($args['folder'], $this->container['mappers.meta']);

        return $this->redirect($response, '/admin/plugins');
    }

    public function getDeactivate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->container['plugins']->deactivatePlugin($args['folder'], $this->container['mappers.meta']);

        return $this->redirect($response, '/admin/plugins');
    }

    protected function getOptionsValidator(array $input, Form $form): ValidatorInterface
    {
        $validator = ValidatorFactory::create($input, $form->getRules());
        $validator->addRule(new ValidateToken($this->container['csrf']->token()), '_token');
        return $validator;
    }
    protected function getOptionsForm(PluginManifest $plugin, array $attributes = []): Form
    {
        $optionsFormClass = $plugin->getOptionsClass();
        $form = new $optionsFormClass($attributes);
        $form->init();
        return $form;
    }
}
