<?php

namespace Form;

class FormActions
{
  private $create;
  private $update;
  private $delete;

  public function __construct($basePath)
  {
    $this->create = $basePath . "/create";
    $this->update = $basePath . "/update/";
    $this->delete = $basePath . "/delete/";
  }

  public function create() {
    return $this->create;
  }

  public function update($id) {
    return $this->update . $id;
  }
  
  public function delete($id) {
    return $this->delete . $id;
  }
}
