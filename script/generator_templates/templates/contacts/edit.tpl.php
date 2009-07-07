<h2>Edit: <?php e($entry->display_name()); ?></h2>
<?php print $this->html_form_tag('put', url('', array('edit'))); ?>
<?php print $this->errors($entry); ?>
<?php include('form.tpl.php'); ?>
</form>
