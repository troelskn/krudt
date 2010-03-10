<h2>New contact</h2>
<form action="<?php e(url('', array('new'))); ?>" method="post">
<?php print $viewhelper->errors($contact); ?>
<?php include('form.tpl.php'); ?>
</form>
