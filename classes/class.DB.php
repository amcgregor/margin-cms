<?

class DB {
  public static function Connect($host, $user, $password, $db) {
    $link = mysql_connect($host, $user, $password);
    mysql_select_db($db);
    return $link;
  }
  
  public static function Quote($string) {
    return mysql_real_escape_string($string);
  }
}

?>