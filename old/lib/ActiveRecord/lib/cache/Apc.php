<?php

namespace ActiveRecord;

class Apc
{
	function flush()
	{
		return apc_clear_cache('user');
	}

	function read($key)
	{
		return apc_fetch($key);
	}

	function write($key, $value, $expire)
	{
		return apc_store($key, $value, $expire);
	}
}
