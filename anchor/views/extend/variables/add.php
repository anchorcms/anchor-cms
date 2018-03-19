<?php echo $header; ?>

  <header class="wrap">
    <h1><?php echo __('extend.create_variable'); ?></h1>
  </header>

  <section class="wrap">
    <form method="post" action="<?php echo Uri::to('admin/extend/variables/add'); ?>" novalidate>
      <input name="token" type="hidden" value="<?php echo $token; ?>">

      <fieldset class="split">
        <p>
          <label for="label-name"><?php echo __('extend.name'); ?>:</label>
            <?php echo Form::text('key', Input::previous('key'), ['id' => 'label-name']); ?>
          <em><?php echo __('extend.name_explain'); ?></em>
        </p>

        <p>
          <label for="label-value"><?php echo __('extend.value'); ?>:</label>
            <?php echo Form::textarea('value', Input::previous('value'), ['cols' => 20, 'id' => 'label-value']); ?>
          <em><?php echo __('extend.value_explain'); ?></em>
        </p>
      </fieldset>

      <aside class="buttons">
          <?php echo Form::button(__('global.save'), ['class' => 'btn', 'type' => 'submit']); ?>

          <?php echo Html::link('admin/extend/variables', __('global.cancel'), ['class' => 'btn cancel blue']); ?>
      </aside>
    </form>
  </section>

<?php echo $footer; ?>
