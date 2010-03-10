<?php
  /**
   * Genereates an opening form tag
   */
  function krudt_html_form_tag($method = 'post', $action = null) {
    $method = strtolower($method);
    $action = $action ? $action : url();
    $html = "";
    $html .= '<form method="' . e($method === 'get' ? 'get' : 'post') . '" action="' . e($action) . '">';
    if ($method !== 'get' && $method !== 'post') {
      $html .= '<input type="hidden" name="_method" value="' . $method . '" />';
    }
    return $html;
  }

  /**
   * Genereates an opening form closing tag
   */
  function krudt_form_end_tag()
  {
      return '</form>';
  }

  /**
   * Generates cancel/submit panel for forms.
   */
  function krudt_form_footer($submit_title = 'OK', $href_back = null) {
    $href_back = $href_back ? $href_back : url();
    $html = "";
    $html .= "\n" . '<p class="form-footer">';
    $html .= "\n" . '<a href="' . e($href_back) . '">Cancel</a>';
    $html .= "\n" . ':';
    $html .= "\n" . '<input type="submit" value="' . e($submit_title) . '" />';
    $html .= "\n" . '</p>';
    return $html;
  }

  /**
   * Renders global errors for an entity.
   */
  function krudt_errors($entity) {
    $html = "";
    foreach ($entity->errors as $field => $error) {
      if (!is_string($field)) {
        $html .= "\n" . '<p style="color:red">' . e($error) . '</p>';
      }
    }
    return $html;
  }

  /**
   * Creates a `<input type="text" />` for a record.
   */
  function krudt_html_text_field($entry, $field, $label = null) {
    $label || $label = ucfirst(str_replace('_', ' ', $field));
    $html = '  <p class="krudt-form">
    <label for="field-' . e($field) . '">' . e($label) . '</label>
    <input type="text" id="field-' . e($field) . '" name="' . e($field) . '" value="' . e($entry->{$field}()) . '" />
';
    if (isset($entry->errors[$field])) {
      $html .= '    <span style="display:block;color:red">' . e($entry->errors[$field]) . ' </span>
';
    }
    $html .= "  </p>\n";
    return $html;
  }

  /**
   * Creates a `<table>` containing a collection.
   */
  function krudt_collection($context, $view, $collection, $slug, $fields = null, $row_actions = null, $collection_actions = null) {
    return new krudt_view_CollectionWidget($collection, $view, $context, $slug);
  }

  /**
   * Creates a pagination widget for a collection.
   */
  function krudt_paginate($context, $view, $collection, $size = 10) {
    return new krudt_view_SimplePaginateWidget($collection, $size, $view, $context);
  }