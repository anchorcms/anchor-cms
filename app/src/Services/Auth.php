<?php

namespace Anchorcms\Services;

class Auth {

	protected $config;

	protected $users;

	protected $options = ['cost' => 14];

	protected $dummyHash;

	public function __construct($config, $users) {
		$this->config = $config;
		$this->users = $users;

		$string = bin2hex(random_bytes(64));
		$this->dummyHash = $this->hashPassword($string);
	}

	public function login($username, $password) {
		// check username
		$user = $this->users->fetchByUsername($username);

		if(false === $user) {
			// protected against user enumeration
			$this->verifyPassword($password, $this->dummyHash);

			return false;
		}
		elseif($this->verifyPassword($password, $user->password)) {
			return $user;
		}

		return false;
	}

	public function hashPassword($password) {
		return password_hash($password, PASSWORD_DEFAULT, $this->options);
	}

	public function checkPasswordHash($hash) {
		return password_needs_rehash($hash, PASSWORD_DEFAULT, $this->options);
	}

	public function verifyPassword($password, $hash) {
		return password_verify($password, $hash);
	}

	public function changePassword($user, $password) {
		$this->users->where('id', '=', $user->id)->update([
			'password' => $this->hashPassword($password),
			'token' => '',
		]);
	}

	public function resetToken($user) {
		$token = bin2hex(random_bytes(64));
		$key = $this->config->get('app.secret');
		$hash = hash_hmac('sha512', $token, $key);

		$this->users->where('id', '=', $user->id)->update(['token' => $hash]);

		return $token;
	}

	public function verifyToken($token) {
		$key = $this->config->get('app.secret');
		$hash = hash_hmac('sha512', $token, $key);

		return $this->users->where('token', '=', $hash)->fetch();
	}

}
