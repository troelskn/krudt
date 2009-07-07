<h2>New: <?php e(get_class($entry)); ?></h2>
<?php print $this->html_form_tag('post', url('', array('new'))); ?>
<?php print $this->errors($entry); ?>
<?php include('form.tpl.php'); ?>
</form>
