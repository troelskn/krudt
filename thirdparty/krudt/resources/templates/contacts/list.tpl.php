<h2>Contacts</h2>

<?php print $viewhelper->collection($this, $context, $contacts)->sort_columns()->row_actions()->paginate()->rowlink(); ?>

