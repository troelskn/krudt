<h2>New contact</h2>
<?php print html_form_tag('post', url('', array('new'))); ?>
<?php print form_errors($contact); ?>
<?php include('form.tpl.php'); ?>
<?php print html_form_tag_end(); ?>
