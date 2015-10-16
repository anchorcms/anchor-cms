<?php

return array(

	'users' => '用户',

	'create_user' => '新建用户',
	'add_user' => '添加用户',
	'editing_user' => '编辑 %s&rsquo;s 配置',
	'remembered' => '记住我的密码',
	'forgotten_password' => '忘记密码?',

	// roles
	'administrator' => '管理员',
	'administrator_explain' => '',

	'editor' => '编辑器',
	'editor_explain' => '',

	'user' => '用户',
	'user_explain' => '',

	// form fields
	'real_name' => '真实名称',
	'real_name_explain' => '',

	'bio' => '个人简介',
	'bio_explain' => '',

	'status' => '状态',
	'status_explain' => '',

	'role' => '角色',
	'role_explain' => '',

	'username' => '用户名',
	'username_explain' => '',
	'username_missing' => '请输入用户名, 必须至少 %s 个字符',

	'password' => '密码',
	'password_explain' => '',
	'password_too_short' => '密码必须至少 %s 个字符',

	'new_password' => '新密码',

	'email' => 'Email',
	'email_explain' => '',
	'email_missing' => '请输入一个有效的email地址',
	'email_not_found' => '用户不存在.',

	// messages
	'updated' => '用户配置已更新.',
	'created' => '用户配置已创建.',
	'deleted' => '用户配置已删除.',
	'delete_error' => '不能删除自己',
	'login_error' => '用户名或密码不正确.',
	'logout_notice' => '退出成功.',
	'recovery_sent' => '邮件已发送，请确认修改密码.',
	'recovery_expired' => '密码找回的token已过期，请重试.',
	'password_reset' => '密码修改成功，现在可以登录了!',

	// 密码 recovery email
	'recovery_subject' => '重置密码',
	'recovery_message' => '你要重置密码.' .
		'继续按下面的链接.' . PHP_EOL . '%s',

);