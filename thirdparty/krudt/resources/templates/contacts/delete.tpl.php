<h2>Delete <?php e($contact->display_name()); ?></h2>
<?php print html_form_tag('delete', url('', array('delete'))); ?>
<?php print krudt_form_footer(); ?>
<?php print html_form_tag_end(); ?>
