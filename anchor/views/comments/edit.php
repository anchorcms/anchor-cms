<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('comments.editing_comment'); ?> &rarr; <?php $commented_post = Post::where('id', '=', $comment->post)->get(); echo $commented_post[0]->title; ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/comments/edit/' . $comment->id); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label for="label-name"><?php echo __('comments.name'); ?>:</label>
				<?php echo Form::text('name', Input::previous('name', $comment->name), array('id' => 'label-name')); ?>
				<em><?php echo __('comments.name_explain'); ?></em>
			</p>

			<p>
				<label for="label-email"><?php echo __('comments.email'); ?>:</label>
				<?php echo Form::email('email', Input::previous('email', $comment->email), array('id' => 'label-email')); ?>
				<em><?php echo __('comments.email_explain'); ?></em>
			</p>

			<p>
				<label for="label-text"><?php echo __('comments.text'); ?>:</label>
				<?php echo Form::textarea('text', Input::previous('text', $comment->text), array('id' => 'label-text')); ?>
				<em><?php echo __('comments.text_explain'); ?></em>
			</p>

			<p>
				<label for="label-status"><?php echo __('comments.status', 'Status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::previous('status', $comment->status), array('id' => 'label-status')); ?>
				<em><?php echo __('comments.status_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.save'), array('type' => 'submit', 'class' => 'btn')); ?>

			<?php echo Html::link('admin/comments' , __('global.cancel'), array('class' => 'btn cancel blue')); ?>

			<?php echo Html::link('admin/comments/delete/' . $comment->id, __('global.delete'), array(
				'class' => 'btn delete red'
			)); ?>
		</aside>
	</form>
</section>

<?php echo $footer; ?>
