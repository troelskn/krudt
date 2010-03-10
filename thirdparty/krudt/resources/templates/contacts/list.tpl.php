<h2>Contacts</h2>

<?php print krudt_collection($context, $this, $contacts, 'slug')->sort_columns()->row_actions()->paginate()->rowlink(); ?>

