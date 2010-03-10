<?php

function krudt_validate_slug($entry, $field = 'slug') {
  if (!$entry->{$field}()) {
    $entry->errors[$field] = "Missing $field";
  } elseif (!preg_match('/^[a-z0-9-]+$/', $entry->{$field}())) {
    $entry->errors[$field] = "$field must be all lower case characters or numbers (dash allowed)";
  }
}

function krudt_validate_email($entry, $field = 'email') {
  if (!$entry->{$field}()) {
    $entry->errors[$field] = "Missing $field";
  } elseif (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $entry->{$field}())) {
    $entry->errors[$field] = "$field must be a valid email adress";
  }
}

class krudt_view_CollectionWidget {
  protected $collection;
  protected $context;
  protected $fields;
  protected $rowlink;
  protected $row_actions;
  protected $collection_actions;
  protected $sort_columns = false;
  protected $paginate = null;
  protected $slug;
  function __construct($context, $collection, $slug) {
    $this->collection = $collection;
    $this->context = $context;
    $this->slug = $slug;
  }
  function escape($phrase) {
      return htmlentities($phrase);
  }
  function rowlink() {
    $this->rowlink = true;
    return $this;
  }
  function fields($fields) {
    $this->fields = $fields;
    return $this;
  }
  function row_actions($row_actions = array('edit', 'delete')) {
    $this->row_actions = $row_actions;
    return $this;
  }
  function collection_actions($collection_actions) {
    $this->collection_actions = $collection_actions;
    return $this;
  }
  function sort_columns() {
    $this->sort_columns = true;
    return $this;
  }
  function paginate($size = 10) {
    $this->paginate = is_null($size) ? null : (integer) $size;
    return $this;
  }
  function __toString() {
    try {
    $sort = null;
    $sort_direction = null;
    if ($this->sort_columns) {
      $sort_direction = strtolower($this->context->query('direction')) === 'desc' ? 'desc' : 'asc';
      if ($this->context->query('sort')) {
        $sort = $this->context->query('sort');
      }
    }
    $collection = $this->collection;
    if ($this->paginate) {
      $limit = $this->paginate;
      $offset = ($this->context->query('page', 1) - 1) * $this->paginate;
    } else {
      $limit = null;
      $offset = null;
    }
    $selection = $collection->select($limit, $offset, $sort, $sort_direction);
    if (count($selection) === 0) {
      return "";
    }
    $fields = $this->fields;
    $row_actions = $this->row_actions;
    $collection_actions = $this->collection_actions;
    if (is_null($fields)) {
      $fields = $collection->getListableColumns();
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
        $html .= "\n" . '    <a href="' . $this->escape($this->context->url('', array($action))) . '">' . $this->escape($action) . '</a>';
      }
      $html .= "\n" . '  </caption>';
    }
    $html .= "\n" . '  <thead>' . "\n";
    $html .= '    <tr>' . "\n";
    $last_field = $fields[count($fields)-1];
    foreach ($fields as $field) {
      $is_sort_field = $this->sort_columns && $this->context->query('sort') === $field;
      if ($field === $last_field && $has_row_actions) {
        $html .= '      <th colspan="2"';
      } else {
        $html .= '      <th';
      }
      if ($is_sort_field) {
        $html .= ' class="sort-' . $sort_direction . '"';
      }
      $html .= '>';
      if ($this->sort_columns) {
        if ($is_sort_field) {
          $direction = $sort_direction === 'desc' ? 'asc' : 'desc';
        } else {
          $direction = null;
        }
        $html .= '<a href="' . $this->escape($this->context->url('', array('sort' => $field, 'direction' => $direction))) . '">';
      }
      $html .= $this->escape($field);
      if ($this->sort_columns) {
        $html .= '</a>';
      }
      $html .=  '</th>' . "\n";
    }
    $html .= '    </tr>' . "\n";
    $html .= '  </thead>' . "\n";
    $html .= '  <tbody>' . "\n";
    $cycle = 0;
    foreach ($selection as $entry) {
      $slug = is_array($entry) ? $entry[$this->slug] : $entry->{$this->slug}();
      $class = $cycle++ % 2 === 0 ? 'even' : 'odd';
      if ($this->rowlink) {
        $class .= " rowlink";
      }
      $html .= '    <tr class="' . $class . '">' . "\n";
      foreach ($fields as $field) {
        $is_sort_field = $this->sort_columns && $this->context->query('sort') === $field;
        $value = is_array($entry) ? $entry[$field] : $entry->{$field}();
        $html .= '      <td';
        if ($is_sort_field) {
          $html .= ' class="sort-' . $sort_direction . '"';
        }
        if ($this->rowlink) {
          $html .= '><a href="' . $this->escape($this->context->url($slug)) . '" class="rowlink">' . $this->escape($value) . '</a></td>' . "\n";
        } else {
          $html .= '>' . $this->escape($value) . '</td>' . "\n";
        }
      }
      if ($has_row_actions) {
        $html .= '      <td class="actions">';
        foreach ($row_actions as $action) {
          $html .= "\n" . '        <a href="' . $this->escape($this->context->url($slug, array($action))) . '">' . $this->escape($action) . '</a>';
        }
        $html .= "\n" . '      </td>' . "\n";
      }
      $html .= '    </tr>' . "\n";
    }
    $html .= '  </tbody>' . "\n";
    if ($this->paginate) {
      $html .= '  <tfoot>' . "\n";
      $html .= '    <tr>' . "\n";
      $html .= '      <td colspan="' . $colspan . '">';
      $html .= krudt_paginate($this->context, $collection, $this->paginate);
      $html .= "\n" . '      </td>' . "\n";
      $html .= '    </tr>' . "\n";
      $html .= '  </tfoot>' . "\n";
    }
    $html .= '</table>' . "\n" . '</div>' . "\n";
    return $html;
    } catch (Exception $ex) {
      return "<pre>" . $ex->getMessage() . "\n" . $ex->getTraceAsString() . "</pre>";
    }
  }
}

/**
 * Just a very simple pagination widget.
 * You might want to have a look at PEAR::Pager for some more elaborate alternatives.
 */
class krudt_view_SimplePaginateWidget {
  protected $collection;
  protected $size;
  protected $context;
  function __construct($context, $collection, $size) {
    $this->collection = $collection;
    $this->size = (integer) $size;
    if ($this->size < 1) {
      throw new Exception("Can't paginate with size < 1");
    }
    $this->context = $context;
  }
  function escape($phrase) {
      return htmlentities($phrase);
  }
  function __toString() {
    $page_size = $this->size;
    $count = $this->collection->count();
    $last_page = (integer) ceil($count / $page_size);
    if ($last_page === 1) {
      return "";
    }
    $page = $this->context->query('page', 1);
    if ($page > $last_page) {
      $page = $last_page;
    }
    if ($page < 1) {
      $page = 1;
    }
    $html = "\n" . '<div class="pagination">';
    for ($ii = 1; $ii <= $last_page; ++$ii) {
      if ($ii == $page) {
        $html .= "\n" . '  <span class="current">' . $ii . '</span>';
      } else {
        $html .= "\n" . '  <a href="' . $this->escape($this->context->url('', array('page' => $ii))) . '">' . $ii . '</a>';
      }
    }
    $html .= "\n" . '</div>';
    return $html;
  }
}
