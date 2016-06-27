<?php

namespace Anchorcms\Session;

use Psr\Http\Message\ResponseInterface;

interface SessionInterface extends StashInterface, StorageInterface
{
    public function id(): string;

    public function name(): string;

    public function migrate();

    public function destroy();

    public function start();

    public function started(): bool;

    public function close(ResponseInterface $response);
}
