<h2>New contact</h2>
<form action="<?php e(url('', array('new'))); ?>" method="post">
<?php print krudt_errors($contact); ?>
<?php include('form.tpl.php'); ?>
</form>
