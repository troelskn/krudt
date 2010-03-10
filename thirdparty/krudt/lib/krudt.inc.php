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

class krudt_view_ViewHelper {
  protected $context;
  function __construct($context) {
      $this->context = $context;
  }

  /**
   * Escapes input
   */
  function escape($phrase) {
      return htmlentities($phrase);
  }

  /**
   * Genereates an opening form tag
   */
  function html_form_tag($method = 'post', $action = null) {
    $method = strtolower($method);
    $action = $action ? $action : $this->context->url();
    $html = "";
    $html .= '<form method="' . $this->escape($method === 'get' ? 'get' : 'post') . '" action="' . $this->escape($action) . '">';
    if ($method !== 'get' && $method !== 'post') {
      $html .= '<input type="hidden" name="_method" value="' . $method . '" />';
    }
    return $html;
  }

  /**
   * Generates cancel/submit panel for forms.
   */
  function form_footer($submit_title = 'OK', $href_back = null) {
    $href_back = $href_back ? $href_back : $this->context->url();
    $html = "";
    $html .= "\n" . '<p class="form-footer">';
    $html .= "\n" . '<a href="' . $this->escape($href_back) . '">Cancel</a>';
    $html .= "\n" . ':';
    $html .= "\n" . '<input type="submit" value="' . $this->escape($submit_title) . '" />';
    $html .= "\n" . '</p>';
    return $html;
  }

  /**
   * Renders global errors for an entity.
   */
  function errors($entity) {
    $html = "";
    foreach ($entity->errors as $field => $error) {
      if (!is_string($field)) {
        $html .= "\n" . '<p style="color:red">' . $this->escape($error) . '</p>';
      }
    }
    return $html;
  }

  /**
   * Creates a `<input type="text" />` for a record.
   */
  function html_text_field($entry, $field, $label = null) {
    $label || $label = ucfirst(str_replace('_', ' ', $field));
    $html = '  <p class="krudt-form">
    <label for="field-' . $this->escape($field) . '">' . $this->escape($label) . '</label>
    <input type="text" id="field-' . $this->escape($field) . '" name="' . $this->escape($field) . '" value="' . $this->escape($entry->{$field}()) . '" />
';
    if (isset($entry->errors[$field])) {
      $html .= '    <span style="display:block;color:red">' . $this->escape($entry->errors[$field]) . ' </span>
';
    }
    $html .= "  </p>\n";
    return $html;
  }

  /**
   * Creates a `<table>` containing a collection.
   */
  function collection($collection, $fields = null, $row_actions = null, $collection_actions = null) {
    return new krudt_view_CollectionWidget($collection, $this, $this->context);
  }

  /**
   * Creates a pagination widget for a collection.
   */
  function paginate($collection, $size = 10) {
    return new krudt_view_SimplePaginateWidget($collection, $size, $this, $this->context);
  }
}

class krudt_view_CollectionWidget {
  protected $collection;
  protected $view;
  protected $context;
  protected $fields;
  protected $rowlink;
  protected $row_actions;
  protected $collection_actions;
  protected $sort_columns = false;
  protected $paginate = null;
  function __construct($collection, $context) {
    $this->collection = $collection;
    $this->view = $view;
    $this->context = $context;
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
        $html .= "\n" . '    <a href="' . $this->view->escape($this->context->url('', array($action))) . '">' . $this->view->escape($action) . '</a>';
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
        $html .= '<a href="' . $this->view->escape($this->context->url('', array('sort' => $field, 'direction' => $direction))) . '">';
      }
      $html .= $this->view->escape($field);
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
      $slug = is_array($entry) ? $entry['slug'] : $entry->slug();
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
          $html .= '><a href="' . $this->view->escape($this->context->url($slug)) . '" class="rowlink">' . $this->view->escape($value) . '</a></td>' . "\n";
        } else {
          $html .= '>' . $this->view->escape($value) . '</td>' . "\n";
        }
      }
      if ($has_row_actions) {
        $html .= '      <td class="actions">';
        foreach ($row_actions as $action) {
          $html .= "\n" . '        <a href="' . $this->view->escape($this->context->url($slug, array($action))) . '">' . $this->view->escape($action) . '</a>';
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
      $html .= $this->view->paginate($collection, $this->paginate);
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
  protected $view;
  protected $context;
  function __construct($collection, $size, $view, $context) {
    $this->collection = $collection;
    $this->size = (integer) $size;
    if ($this->size < 1) {
      throw new Exception("Can't paginate with size < 1");
    }
    $this->view = $view;
    $this->context = $context;
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
        $html .= "\n" . '  <a href="' . $this->view->escape($this->context->url('', array('page' => $ii))) . '">' . $ii . '</a>';
      }
    }
    $html .= "\n" . '</div>';
    return $html;
  }
}
