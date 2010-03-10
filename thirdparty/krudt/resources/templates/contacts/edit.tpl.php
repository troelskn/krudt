<h2>Edit <?php e($contact->display_name()); ?></h2>
<?php print krudt_html_form_tag('put', url('', array('edit'))); ?>
<?php print krudt_errors($contact); ?>
<?php include('form.tpl.php'); ?>
<?php print krudt_form_tag_end(); ?>
