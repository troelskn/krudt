<h2>New contact</h2>
<?php print krudt_html_form_tag('post', url('', array('new'))); ?>
<?php print krudt_errors($contact); ?>
<?php include('form.tpl.php'); ?>
<?php print krudt_form_tag_end(); ?>
