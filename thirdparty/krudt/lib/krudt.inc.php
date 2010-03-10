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