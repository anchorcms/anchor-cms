<?php

class Comment extends Base {

	public static $table = 'comments';

	public function notify() {
		$uri = Uri::full('admin/comments/edit/' . $this->id);
		$host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST) ?: 'localhost';
		$from = 'notifications@' . $host;

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'From: ' . $from . "\r\n";

		$message = '<html>
			<head>
				<title>' . __('comments.notify_subject') . '</title>
				<style>
					body {font: 15px/25px "Helvetica Neue", "Open Sans", "DejaVu Sans", "Arial", sans-serif;}
					table {margin: 1em 0; border-collapse: collapse;}
					table td {padding: 6px 8px; border: 1px solid #ccc;}
					h2, p {margin: 0 0 1em 0;}
				</style>
			</head>
			<body>
				<h2>' . __('comments.nofity_heading') . '</h2>

				<table>
					<tr>
						<td>' . __('comments.name') . '</td>
						<td>' . $this->name . '</td>
					</tr>
					<tr>
						<td>' . __('comments.email') . '</td>
						<td>' . $this->email . '</td>
					</tr>
					<tr>
						<td>' . __('comments.text') . '</td>
						<td>' . $this->text . '</td>
					</tr>
				</table>

				<p><a href="' . $uri . '">' . __('comments.view_comment') . '</a></p>
			</body>
		</html>';

		// notify administrators
		foreach(User::where('role', '=', 'administrator')->get() as $user) {
			$to = $user->real_name . ' <' . $user->email . '>';
			mail($to, __('comments.notify_subject'), $message, $headers);
		}
	}

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('date', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/comments'));
	}

	public static function spam($comment) {
		$parts = explode('@', $comment['email']);
		$domain = array_pop($parts);

		// check domain
		$query = static::where('email', 'like', '%' . $domain)
			->where('status', '=', 'spam');

		if($query->count()) {
			// duplicate spam
			return true;
		}

		// check keywords
		$badwords = Config::get('meta.comment_moderation_keys');

		if($badwords) {
			$words = explode(',', $badwords);

			foreach($words as $word) {
				$word = preg_quote($word, '#');
				$pattern = "#$word#i";

				foreach($comment as $part) {
					if(preg_match($pattern, $part)) return true;
				}
			}
		}

		return false;
	}

}
