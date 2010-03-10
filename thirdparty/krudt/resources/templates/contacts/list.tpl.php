<h1>Contacts</h1>
<?php
    $fields = null;
    $rowlink = true;
    $row_actions = array('edit', 'delete');
    $collection_actions = null;
    $sort_columns = true;
    $paginate = 10;
    $slug = 'slug';
    $sort = null;
    $sort_direction = null;
    if ($sort_columns) {
      $sort_direction = strtolower($context->query('direction')) === 'desc' ? 'desc' : 'asc';
      if ($context->query('sort')) {
        $sort = $context->query('sort');
      }
    }
    if ($paginate) {
      $limit = $paginate;
      $offset = ($context->query('page', 1) - 1) * $paginate;
    } else {
      $limit = null;
      $offset = null;
    }
    $selection = $tasks->select($limit, $offset, $sort, $sort_direction);
    if (count($selection) === 0) {
      return "";
    }
    if (is_null($fields)) {
      $fields = $tasks->getListableColumns();
    }
    if (is_null($row_actions)) {
      $row_actions = array();
    }
    if (is_null($collection_actions)) {
      $collection_actions = array('new');
    }
    $has_row_actions = count($row_actions) > 0;
    $has_collection_actions = count($collection_actions) > 0;
    $colspan = count($fields) + ($has_row_actions ? 1 : 0);
    $html = "";
    $html .= "\n" . '<table class="collection">';
    if ($has_collection_actions) {
      $html .= "\n" . '  <caption>';
      foreach ($collection_actions as $action) {
        $html .= "\n" . '    <a href="' . htmlentities($context->url('', array($action))) . '">' . htmlentities($action) . '</a>';
      }
      $html .= "\n" . '  </caption>';
    }
    $html .= "\n" . '  <thead>' . "\n";
    $html .= '    <tr>' . "\n";
    $last_field = $fields[count($fields)-1];
    foreach ($fields as $field) {
      $is_sort_field = $sort_columns && $context->query('sort') === $field;
      if ($field === $last_field && $has_row_actions) {
        $html .= '      <th colspan="2"';
      } else {
        $html .= '      <th';
      }
      if ($is_sort_field) {
        $html .= ' class="sort-' . $sort_direction . '"';
      }
      $html .= '>';
      if ($sort_columns) {
        if ($is_sort_field) {
          $direction = $sort_direction === 'desc' ? 'asc' : 'desc';
        } else {
          $direction = null;
        }
        $html .= '<a href="' . htmlentities($context->url('', array('sort' => $field, 'direction' => $direction))) . '">';
      }
      $html .= htmlentities($field);
      if ($sort_columns) {
        $html .= '</a>';
      }
      $html .=  '</th>' . "\n";
    }
    $html .= '    </tr>' . "\n";
    $html .= '  </thead>' . "\n";
    $html .= '  <tbody>' . "\n";
    $cycle = 0;
    foreach ($selection as $entry) {
      $this_slug = is_array($entry) ? $entry[$slug] : $entry->{$slug}();
      $class = $cycle++ % 2 === 0 ? 'even' : 'odd';
      if ($rowlink) {
        $class .= " rowlink";
      }
      $html .= '    <tr class="' . $class . '">' . "\n";
      foreach ($fields as $field) {
        $is_sort_field = $sort_columns && $context->query('sort') === $field;
        $value = is_array($entry) ? $entry[$field] : $entry->{$field}();
        $html .= '      <td';
        if ($is_sort_field) {
          $html .= ' class="sort-' . $sort_direction . '"';
        }
        if ($rowlink) {
          $html .= '><a href="' . htmlentities($context->url($this_slug)) . '" class="rowlink">' . htmlentities($value) . '</a></td>' . "\n";
        } else {
          $html .= '>' . htmlentities($value) . '</td>' . "\n";
        }
      }
      if ($has_row_actions) {
        $html .= '      <td class="actions">';
        foreach ($row_actions as $action) {
          $html .= "\n" . '        <a href="' . htmlentities($context->url($slug, array($action))) . '">' . htmlentities($action) . '</a>';
        }
        $html .= "\n" . '      </td>' . "\n";
      }
      $html .= '    </tr>' . "\n";
    }
    $html .= '  </tbody>' . "\n";
    if ($paginate) {
      $html .= '  <tfoot>' . "\n";
      $html .= '    <tr>' . "\n";
      $html .= '      <td colspan="' . $colspan . '">';
      $html .= krudt_paginate($context, $tasks, $paginate);
      $html .= "\n" . '      </td>' . "\n";
      $html .= '    </tr>' . "\n";
      $html .= '  </tfoot>' . "\n";
    }
    $html .= '</table>' . "\n" . '</div>' . "\n";
    echo $html;
?>