<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Google_sheets_helper {
  protected $api_key;

  public function __construct() {
    $this->api_key = '';
  }

  public function load_sheet($spreadsheet_id, $range) {
    $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheet_id}/values/{$range}?key={$this->api_key}";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    return $data;
  }

  public function load_published_sheet($url) {
    $doc = new DOMDocument();
    $doc->loadHtmlFile($url);
    $xml = simplexml_import_dom($doc);

    $rows = $xml->body->div[1]->div->div->table->tbody->tr;

    return $this->simplify($rows);
  }

  public function d($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
  }

  public function simplify($rows) {
    $head = [];
    $data = [];
    for ($i = 0; $i < count($rows[0]->td); $i++) {
      array_push($head, $rows[0]->td[$i]->__toString());
    }
    
    for ($i = 1; $i < count($rows); $i++) {
      $d = $rows[$i]->td;
      $a = [];

      for ($j = 0; $j < count($d); $j++) {
        $key = strtolower($head[$j]);
        if (isset($d[$j]->a)) {
          $a[$key] = $d[$j]->a->__toString();  
        } else if (isset($d[$j]->div)) {
          if (isset($d[$j]->div->a)) {
            $a[$key] = $d[$j]->div->a->__toString();
          } else {
            $a[$key] = $d[$j]->div->__toString();
          }
        } else {
          $a[$key] = $d[$j]->__toString(); 
        }
      }
      array_push($data, $a);
    }

    return $data;
  }
}