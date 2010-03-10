<h2>Contacts</h2>

<?php print krudt_collection($context, $contacts, 'slug')->sort_columns()->row_actions()->paginate()->rowlink(); ?>

