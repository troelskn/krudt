<h2><?php e($entry->slug()); ?></h2>
<dl>
  <dt>First Name</dt>
  <dd><?php e($entry->first_name()); ?></dd>
  <dt>Last Name</dt>
  <dd><?php e($entry->last_name()); ?></dd>
  <dt>Email</dt>
  <dd><?php e($entry->email()); ?></dd>
</dl>
<p>
  <a href="<?php e(url('', array('edit'))); ?>">Edit entry</a>
  :
  <a href="<?php e(url('', array('delete'))); ?>">Delete entry</a>
</p>
