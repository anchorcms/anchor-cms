<?php foreach($fields as $field): ?>
  <p>
    <label for="extend_<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
    <?php echo Extend::html($field); ?>
  </p>
<?php endforeach; ?>