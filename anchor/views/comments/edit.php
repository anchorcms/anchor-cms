<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('comments.editing_comment'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/comments/edit/' . $comment->id); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label><?php echo __('comments.name'); ?>:</label>
				<?php echo Form::text('name', Input::previous('name', $comment->name)); ?>
				<em><?php echo __('comments.name_explain'); ?></em>
			</p>

			<p>
				<label><?php echo __('comments.email'); ?>:</label>
				<?php echo Form::email('email', Input::previous('email', $comment->email)); ?>
				<em><?php echo __('comments.email_explain'); ?></em>
			</p>

			<p>
				<label><?php echo __('comments.text'); ?>:</label>
				<?php echo Form::textarea('text', Input::previous('text', $comment->text)); ?>
				<em><?php echo __('comments.text_explain'); ?></em>
			</p>

			<p>
				<label><?php echo __('comments.status', 'Status'); ?>:</label>
				<?php echo Form::select('status', $statuses, Input::previous('status', $comment->status)); ?>
				<em><?php echo __('comments.status_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.save'), array('type' => 'submit', 'class' => 'btn')); ?>

			<?php echo Html::link('admin/comments/delete/' . $comment->id, __('global.delete'), array(
				'class' => 'btn delete red'
			)); ?>
		</aside>
	</form>
</section>

<?php echo $footer; ?>