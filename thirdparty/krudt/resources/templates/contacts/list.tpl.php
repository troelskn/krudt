<h2>Contacts</h2>

<?php print $viewhelper->collection($contacts)->sort_columns()->row_actions()->paginate()->rowlink(); ?>

