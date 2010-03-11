<h1>Contacts</h1>
<?php
    $fields = $contacts->getListableColumns();
    $rowlink = true;
    $row_actions = array('edit', 'delete');
    $collection_actions = array('new');
    $page_size = 10;
    $slug = 'slug';
    $sort_direction = strtolower($context->query('direction')) === 'desc' ? 'desc' : 'asc';
    $sort = $context->query('sort');
    $offset = ($context->query('page', 1) - 1) * $page_size;
    $selection = $contacts->select($page_size, $offset, $sort, $sort_direction);
?>
<?php if (count($selection) > 0): ?>
<?php
      $has_row_actions = count($row_actions) > 0;
      $has_collection_actions = count($collection_actions) > 0;
      $colspan = count($fields) + ($has_row_actions ? 1 : 0);
?>
<table class="collection">
<?php if ($has_collection_actions): ?>
  <caption>
<?php foreach ($collection_actions as $action): ?>
    <?php echo krudt_html_link($context->url('', array($action)), $action); ?>
<?php endforeach; ?>
  </caption>
<?php endif; ?>
  <thead>
    <tr>
<?php $last_field = $fields[count($fields)-1]; ?>
<?php foreach ($fields as $field): ?>
<?php
        $is_sort_field = $context->query('sort') === $field;
        if ($is_sort_field) {
          $direction = $sort_direction === 'desc' ? 'asc' : 'desc';
        } else {
          $direction = null;
        }
?>
<?php if ($field === $last_field && $has_row_actions): ?>
      <th colspan="2"
<?php else: ?>
      <th
<?php endif; ?>
<?php if ($is_sort_field): ?>
          class="sort-<?php e($sort_direction) ?>"
<?php endif; ?>
      ><?php echo krudt_html_link($context->url('', array('sort' => $field, 'direction' => $direction)), $field); ?></th>
<?php endforeach; ?>

    </tr>
  </thead>
  <tbody>
<?php $cycle = 0; ?>
<?php foreach ($selection as $entry): ?>
<?php
        $this_slug = is_array($entry) ? $entry[$slug] : $entry->{$slug}();
        $class = $cycle++ % 2 === 0 ? 'even' : 'odd';
        if ($rowlink) {
          $class .= " rowlink";
        }
?>
    <tr class="<?php e($class) ?>">
<?php foreach ($fields as $field): ?>
<?php
        $is_sort_field = $context->query('sort') === $field;
        $value = is_array($entry) ? $entry[$field] : $entry->{$field}();
?>
      <td
<?php if ($is_sort_field): ?>
          class="sort-<?php e($sort_direction) ?>"
<?php endif; ?>
<?php if ($rowlink): ?>
      ><?php echo krudt_html_link($context->url($this_slug), $value, array('class' => 'rowlink')); ?>
</td>
<?php else: ?>
      ><?php e($value) ?></td>
<?php endif; ?>
<?php endforeach; ?>
<?php if ($has_row_actions): ?>
      <td class="actions">
<?php foreach ($row_actions as $action): ?>
  <?php echo krudt_html_link($context->url($this_slug, array($action)), $action); ?>
<?php endforeach; ?>
      </td>
<?php endif; ?>
    </tr>
<?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="<?php e($colspan) ?>">
<?php echo krudt_paginate($context, $contacts, $page_size) ?>
      </td>
    </tr>
  </tfoot>
</table>
<?php endif; ?>