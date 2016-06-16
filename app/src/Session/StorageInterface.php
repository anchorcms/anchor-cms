<?php

namespace Anchorcms\Session;

interface StorageInterface {

	public function has(string $key): bool;

	public function get(string $key);

	public function put(string $key, $value);

	public function remove(string $key);

}
