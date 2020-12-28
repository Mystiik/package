<?php

namespace GN\GlbObjFunc;

class GetInBetweenString {
  public $str;
  public $strPos;
  public $strStart;
  public $strEnd;
  public $schemaStartValue;
  public $schemaEndValue;
  public $schemaStart;
  public $schemaEnd;

  public function __construct() {
    $this->str = "";
    $this->strStart = 0;
    $this->strEnd = 0;
    $this->strPos = 0;
    $this->schemaStartValue = 0;
    $this->schemaEndValue = 0;
    $this->schemaStart = "";
    $this->schemaEnd = "";
  }
}