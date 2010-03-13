<?php
/**
 * Adds a snippet of javascript to be executed on page load.
 */
function add_onload($js) {
  $GLOBALS['k_current_context']->document()->addOnload($js);
}

/**
 */
function add_stylesheet($url) {
  $GLOBALS['k_current_context']->document()->addStyle($url);
}

/**
 */
function add_javascript($url) {
  $GLOBALS['k_current_context']->document()->addScript($url);
}

/**
 * Generates a link/anchortag
 */
function html_link($url, $title = null, $options = array()) {
  if ($title === null) {
    $title = $url;
  }
  $options['href'] = $url;
  $html = "<a";
  foreach ($options as $k => $v) {
    if ($v !== null) {
      $html .= ' ' . escape($k) . '="' . escape($v) . '"';
    }
  }
  $html .= ">".escape($title)."</a>";
  return $html;
}

/**
 * Generates an opening html `<form>` tag.
 */
function html_form_tag($method = 'post', $action = null, $options = array()) {
  $method = strtolower($method);
  $html = '<form';
  $options['action'] = $action ? $action : $GLOBALS['k_current_context']->url();
  $options['method'] = $method === 'get' ? 'get' : 'post';
  foreach ($options as $k => $v) {
    if ($v !== null) {
      $html .= ' ' . escape($k) . '="' . escape($v) . '"';
    }
  }
  $html .= ">\n";
  if ($method !== 'get' && $method !== 'post') {
    $html .= '<input type="hidden" name="_method" value="' . escape($method) . '" />
';
  }
  return $html;
}

/**
 * Genereates an opening form closing tag
 */
function html_form_tag_end() {
  return '</form>';
}

/**
 * Renders a html text input element.
 */
function html_text_field($name, $value = null, $options = array()) {
  $html = '<input type="text"';
  $options['name'] = $name;
  $options['value'] = $value;
  foreach ($options as $k => $v) {
    if ($v !== null) {
      $html .= ' ' . escape($k) . '="' . escape($v) . '"';
    }
  }
  return $html . " />\n";
}

/**
 * Renders a html password input element.
 */
function html_password_field($name, $options = array()) {
  $html = '<input type="password"';
  $options['name'] = $name;
  $options['value'] = null;
  foreach ($options as $k => $v) {
    if ($v !== null) {
      $html .= ' ' . escape($k) . '="' . escape($v) . '"';
    }
  }
  return $html . " />\n";
}

/**
 * Renders a html hidden input element.
 */
function html_hidden_field($name, $value = null, $options = array()) {
  $html = '<input type="hidden"';
  $options['name'] = $name;
  $options['value'] = $value;
  foreach ($options as $k => $v) {
    if ($v !== null) {
      $html .= ' ' . escape($k) . '="' . escape($v) . '"';
    }
  }
  return $html . " />\n";
}

/**
 * Renders a html `<textarea>` input element.
 */
function html_text_area($name, $value = null, $options = array()) {
  $html = '<textarea';
  $options['name'] = $name;
  foreach ($options as $k => $v) {
    if ($v !== null) {
      $html .= ' ' . escape($k) . '="' . escape($v) . '"';
    }
  }
  return $html . ">" . escape($value) . "</textarea>\n";
}

/**
 * Renders a html radio input element.
 */
function html_radio($name, $value = null, $checked = false, $options = array()) {
  $html = "";
  if (isset($options['label'])) {
    $label = $options['label'];
    $options['label'] = null;
    $html .= '<label class="radio-button">';
  }
  $html .= '<input type="radio"';
  $options['name'] = $name;
  $options['value'] = $value;
  $options['checked'] = $checked ? 'checked' : null;
  foreach ($options as $k => $v) {
    if ($v !== null) {
      $html .= ' ' . escape($k) . '="' . escape($v) . '"';
    }
  }
  $html .= ' />';
  if (isset($label)) {
    $html .= escape($label) . '</label>';
  }
  return $html . "\n";
}

/**
 * Renders a html checkbox input element.
 */
function html_checkbox($name, $checked = false, $options = array()) {
  $html = "";
  if (isset($options['label'])) {
    $label = $options['label'];
    $options['label'] = null;
    $html .= '<label>';
  }
  $html .= '<input type="checkbox"';
  $options['name'] = $name;
  $options['value'] = 'on';
  $options['checked'] = $checked ? 'checked' : null;
  foreach ($options as $k => $v) {
    if ($v !== null) {
      $html .= ' ' . escape($k) . '="' . escape($v) . '"';
    }
  }
  $html .= ' />';
  if (isset($label)) {
    $html .= escape($label) . '</label>';
  }
  return $html . "\n";
}

/**
 * Renders a html `<select>` input element.
 */
function html_select($name, $values = array(), $value = null, $options = array()) {
  $html = '<select';
  $options['name'] = $name;
  foreach ($options as $k => $v) {
    if ($v !== null) {
      $html .= ' ' . escape($k) . '="' . escape($v) . '"';
    }
  }
  return $html . ">" . html_options($values, $value) . "</select>\n";
}

/**
 * Renders html `<option>` elements from an array.
 */
function html_options($values = array(), $value = null) {
  $html = "";
  foreach ($values as $key => $v) {
    $html .= '<option';
    if (!is_integer($key)) {
      $html .= ' value="' . escape($v) . '"';
    }
    if ($v == $value) {
      $html .= ' selected="selected"';
    }
    $html .= '>';
    if (is_integer($key)) {
      $html .= escape($v);
    } else {
      $html .= escape($key);
    }
    $html .= "</option>\n";
  }
  return $html;
}

/**
 * Generates cancel/submit panel for forms.
 */
function krudt_form_footer($submit_title = 'OK', $href_back = null) {
  $href_back = $href_back ? $href_back : url();
  $html = "";
  $html .= "\n" . '<p class="form-footer">';
  $html .= "\n" . '<a href="' . escape($href_back) . '">Cancel</a>';
  $html .= "\n" . ':';
  $html .= "\n" . '<input type="submit" value="' . escape($submit_title) . '" />';
  $html .= "\n" . '</p>';
  return $html;
}

/**
 * Renders global errors for an entity.
 */
function form_errors($entity) {
  $html = array();
  foreach ($entity->errors as $field => $error) {
    if (!is_string($field)) {
      $html[] = '<p style="color:red">' . escape($error) . '</p>';
    }
  }
  return implode("\n", $html);
}

/**
 * Renders errors for a single of an entity field,
 */
function form_errors_for($entity, $field) {
  if (isset($entity->errors[$field])) {
    return
      '<span style="display:block;color:red">' . "\n"
      . escape(implode(', ', is_array($entity->errors[$field]) ? $entity->errors[$field] : array($entity->errors[$field]))) . "\n"
      . '</span>' . "\n";
  }
}

/**
 * Creates a `<input type="text" />` for a record.
 */
function form_text_field($entry, $field, $label = null) {
  $label || $label = ucfirst(str_replace('_', ' ', $field));
  $html = '  <p class="krudt-form">
    <label for="field-' . escape($field) . '">' . escape($label) . '</label>
    <input type="text" id="field-' . escape($field) . '" name="' . escape($field) . '" value="' . escape($entry->{$field}()) . '" />
';
  if (isset($entry->errors[$field])) {
    $html .= '    <span style="display:block;color:red">' . escape($entry->errors[$field]) . ' </span>
';
  }
  $html .= "  </p>\n";
  return $html;
}

/**
 * Creates a `<textarea />` for a record.
 */
function form_text_area($entry, $field, $label = null) {
  $label || $label = ucfirst(str_replace('_', ' ', $field));
  $html = '  <p class="form">
    <label for="field-' . escape($field) . '">' . escape($label) . '</label>
    <textarea id="field-' . escape($field) . '" name="' . escape($field) . '">' . escape($entry->{$field}()) . '</textarea>
';
  if (isset($entry->errors[$field])) {
    $html .= '    <span style="display:block;color:red">' . escape($entry->errors[$field]) . ' </span>
';
  }
  $html .= "  </p>\n";
  return $html;
}

/**
 * Creates a pagination widget for a collection.
 */
function collection_paginate($collection, $size = 10) {
  $page_size = $size;
  $count = $collection->count();
  $last_page = (integer) ceil($count / $page_size);
  if ($last_page === 1) {
    return "";
  }
  $page = $GLOBALS['k_current_context']->query('page', 1);
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
      $html .= "\n" . '  <a href="' . escape($GLOBALS['k_current_context']->url('', array('page' => $ii))) . '">' . $ii . '</a>';
    }
  }
  $html .= "\n" . '</div>';
  return $html;
}
