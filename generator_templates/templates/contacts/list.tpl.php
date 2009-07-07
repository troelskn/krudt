<h2><?php e(get_class($collection)); ?></h2>

<?php print $this->collection($collection)->sort_columns()->row_actions()->paginate()->rowlink(); ?>

