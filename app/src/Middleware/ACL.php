<?php

namespace Anchorcms\Middleware;

use Tari\ServerMiddlewareInterface;
use Tari\ServerFrameInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Session\SessionInterface;
use Anchorcms\Mappers\MapperInterface;
use Anchorcms\Models\User;

/**
 * Enforce ACL rules
 */
class ACL implements ServerMiddlewareInterface
{
    protected $session;

    protected $users;

    protected $acl;

    public function __construct(SessionInterface $session, MapperInterface $users, array $acl)
    {
        $this->session = $session;
        $this->users = $users;
        $this->acl = $acl;
    }

    private function fetchUser(): User {
        return $this->session->has('user') ?
            $this->users->fetchById($this->session->get('user')) :
            new User(['role' => 'guest']);
    }

    private function fetchRules(User $user): array {
        return $this->acl['rules'][$user->role] ?? [];
    }

    public function handle(ServerRequestInterface $request, ServerFrameInterface $frame): ResponseInterface
    {
        // only run for admin
        if (strpos($request->getUri()->getPath(), '/admin') !== 0) {
            return $frame->next($request);
        }

        // make the session has been started
        if(false === $this->session->started()) {
            $this->session->start();
        }

        // get requested path
        $path = $request->getUri()->getPath();

        // get a user model
        $user = $this->fetchUser();

        // get rules for user
        $rules = $this->fetchRules($user);

        // check at least one rule matches
        foreach($rules as $rule) {
            // do we have access to this resource
            if($rule == '*' || preg_match('#^'.preg_quote($rule).'#', $path)) {
                return $frame->next($request);
            }
        }

        return $frame->factory()->createResponse(302, [
            'Location' => $this->acl['login'],
        ]);
    }
}
