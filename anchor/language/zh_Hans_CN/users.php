<?php

return array(

	'users' => '用户',

	'create_user' => '创建用户',
	'add_user' => '增加新用户',
	'editing_user' => '编辑用户 %s',
	'remembered' => '我知道我的密码',
	'forgotten_password' => '忘记密码?',

	// roles
	'administrator' => '管理员',
	'administrator_explain' => '',

	'editor' => '编辑',
	'editor_explain' => '',

	'user' => '用户',
	'user_explain' => '',

	// form fields
	'real_name' => '昵称',
	'real_name_explain' => '',

	'bio' => '个人简介',
	'bio_explain' => '',

	'status' => '状态',
	'status_explain' => '',

	'role' => '角色',
	'role_explain' => '',

	'username' => '用户名',
	'username_explain' => '',
	'username_missing' => '请输入用户名, 最少 %s 个字符',

	'password' => '密码',
	'password_explain' => '',
	'password_too_short' => '密码至少 %s 个字符',

	'new_password' => '新密码',

	'email' => '邮件',
	'email_explain' => '',
	'email_missing' => '请输入正确的邮件地址',
	'email_not_found' => '找不到个人资料.',

	// messages
	'updated' => '用户信息已更新.',
	'created' => '用户信息已创建.',
	'deleted' => '用户信息已删除.',
	'delete_error' => '不能删除自己的信息',
	'login_error' => '用户名或密码错误',
	'logout_notice' => '您已退出登陆.',
	'recovery_sent' => '已经发送确认密码变更的邮件.',
	'recovery_expired' => '密码恢复令牌已过期，请重试.',
	'password_reset' => '新密码已经生效，请重新登陆!',

	// password recovery email
	'recovery_subject' => '重置密码',
	'recovery_message' => '您请求重置密码.' .
		'请点击下面的链接继续.' . PHP_EOL . '%s',

);
