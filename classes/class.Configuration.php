<?

class ConfigurationFilterDate {
  static function Get($value) {
    return $value;
  }

  static function Form($value) {
    return $value;
  }

  static function Display($value) {
    return date("F j, Y", $value);
  }
}

class ConfigurationFilterTime {
  static function Get($value) {
    return $value;
  }

  static function Form($value) {
    return $value;
  }

  static function Display($value) {
    return date("g:i a", $value);
  }
}

class ConfigurationFilterDateTime {
  static function Get($value) {
    return $value;
  }

  static function Form($value) {
    return $value;
  }

  static function Display($value) {
    return date('g:i a &\m\d\a\s\h; F j, Y', $value);
  }
  
  static function Update($value) {
    return $value;
  }
}

class Configuration implements ArrayAccess {
  protected $Cached;
  protected $Cache = array();

  public function __construct($cached = true) {
    $this->Cached = $cached;
  }
  
  private function DisplayFilter($row) {
    if ( empty($row['filter']) ) return $row['value'];
    
    
    $filter = "ConfigurationFilter" . $row['filter'];
    return call_user_func(array($filter, 'Display'), $row['value']);
  }

  public function offsetExists($index) {
    $result = mysql_query(sprintf("SELECT * FROM `configuration` WHERE `key`='%s' LIMIT 1", mysql_real_escape_string($index)));
    if ( mysql_num_rows($result) ) return true;
    
    return false;
  }

  public function offsetGet($index) {
    if ( !$this->offsetExists($index) ) return NULL;
    
    if ( $this->Cached && isset($this->Cache[$index]) ) return $this->DisplayFilter($this->Cache[$index]);
  
    $result = mysql_query(sprintf("SELECT `value`, `filter` FROM `configuration` WHERE `key`='%s' LIMIT 1", mysql_real_escape_string($index)));
    $row = mysql_fetch_assoc($result);
    
    if ( $this->Cached ) $this->DisplayFilter[$index] = $row;
    
    return $this->DisplayFilter($row);
  }

  public function offsetSet($index, $value) {
    if ( $this->offsetExists($index) ) {
      if ( $this->Cached ) $this->Cache[$index]['value'] = $value;
      
      mysql_query(sprintf("UPDATE `configuration` SET `value`='%s' WHERE `key`='%s' LIMIT 1", mysql_real_escape_string($value), mysql_real_escape_string($index)));
    } else {
      // TODO: Raise an warning about attempting to set an unsettable option.
    }
    return;
  }

  public function offsetUnset($index) {
    // TODO: Raise an warning about attempting to delete an option.
    return;
  }
}

?>