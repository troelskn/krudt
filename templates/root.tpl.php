  <h1>Root</h1>
  <ul>
<?php foreach ($modules as $name => $classname) : ?>
  <li><a href="<?php e(url($name)); ?>"><?php e($name); ?></a></li>

<?php endforeach; ?>
  </ul>