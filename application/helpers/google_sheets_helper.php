<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Google_sheets_helper {
  protected $api_key;
  protected $data = [];
  protected $data_names = [];

  public function __construct() {
    $this->api_key = '';
  }

  public function load_sheet($url) {
    $doc = new DOMDocument();
    $doc->loadHtmlFile($url);

    $xml = simplexml_import_dom($doc);

    return $xml->xpath('body/div/div/div/table/tbody/tr');
  }

  public function load_published_sheet($url) {
    $rows = $this->load_sheet($url);

    $this->simplify($rows);
  }

  public function load_stat($url) {
    $rows = $this->load_sheet($url);

    $this->stat_to_array($rows);
  }

  public function d($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
  }

  public function simplify($rows) {
    $head = [];
    for ($i = 0; $i < count($rows[0]->td); $i++) {
      array_push($head, $rows[0]->td[$i]->__toString());
    }
    
    for ($i = 1; $i < count($rows); $i++) {
      $d = $rows[$i]->td;
      $a = [];

      for ($j = 0; $j < count($d); $j++) {
        $key = strtolower($head[$j]);
        $val = '';

        if ($key != '') {
          if (isset($d[$j]->a)) {
            $val = $d[$j]->a->__toString();  
          } else if (isset($d[$j]->div)) {
            if (isset($d[$j]->div->a)) {
              $val = $d[$j]->div->a->__toString();
            } else {
              $val = $d[$j]->div->__toString();
            }
          } else {
            $val = $d[$j]->__toString(); 
          }
  
          $a[$key] = $val;
          if ($key == 'name') {
            array_push($this->data_names, $val);
          }
        }
      }
      array_push($this->data, $a);
    }
  }

  public function stat_to_array($rows) {
    $header = $rows[0]->xpath('td');
    $data = $rows[1]->xpath('td');

    for ($i = 0; $i < count($header); $i++) {
      $this->data[$header[$i]->__toString()] = $data[$i]->__toString();
    }
  }

  public function get_loaded_data() {
    return $this->data;
  }

  public function filter($keyword, $function) {
    $f = $function == 'sales' || $function == 'service' || $function == 'sparepart' ? 
      $function : 'all';

    $filtered = [];
    $matches = preg_grep('/' . $keyword . '/i', $this->data_names);
    foreach ($matches as $k => $v) {
      if ($f != 'all') {
        if (strtolower($this->data[$k][$f]) == 'v') {
          array_push($filtered, $this->data[$k]);
        }
      } else {
        array_push($filtered, $this->data[$k]);
      }
    }

    return $filtered;
  }

  public function filter_function($q) {
    $filtered = [];
    foreach ($this->data as $k) {
      if (strtolower($k[$q]) === 'v') { 
        array_push($filtered, $k);
      }
    }

    return $filtered;
  }
}