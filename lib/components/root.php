<?php
class components_Root extends k_Component {
  protected $templates;
  function __construct(k_TemplateFactory $templates) {
    $this->templates = $templates;
  }
  function map($name) {
    if (class_exists('components_' . $name . '_List')) {
      return 'components_' . $name . '_List';
    }
  }
  function execute() {
    return $this->wrap(parent::execute());
  }
  function wrapHtml($content) {
    $this->document->addStyle($this->url('krudt.css'));
    $t = $this->templates->create("document");
    return
      $t->render(
        $this,
        array(
          'content' => $content,
          'title' => $this->document->title(),
          'scripts' => $this->document->scripts(),
          'styles' => $this->document->styles(),
          'onload' => $this->document->onload()));
  }
  function renderHtml() {
    $t = $this->templates->create("root");
    $modules = array();
    foreach (scandir(dirname(__FILE__)) as $name) {
      if (class_exists('components_' . $name . '_List')) {
        $modules[$name] = 'components_' . $name . '_List';
      }
    }
    return $t->render($this, array('modules' => $modules));
  }
}
