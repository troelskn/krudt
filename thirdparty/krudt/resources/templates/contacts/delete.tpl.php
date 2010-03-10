<h2>Delete <?php e($contact->display_name()); ?></h2>
<?php print $viewhelper->html_form_tag('delete', url('', array('delete'))); ?>
<?php print $viewhelper->form_footer(); ?>
</form>
