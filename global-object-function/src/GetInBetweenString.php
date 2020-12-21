<?php

namespace GN\GlbObjFunc;

class GetInBetweenString {
  public $str;
  public $strStart;
  public $strEnd;
  public $strPos;
  public $type;

  public function __construct() {
    $this->str = "";
    $this->strStart = 0;
    $this->strEnd = 0;
    $this->strPos = 0;
    $this->type = "";
  }
}