<?

class Translation implements ArrayAccess {
  protected $LanguageName;
  protected $LanguageCode;
  protected $BaseLanguage;
  protected $LanguageCache = array();
  protected $LanguageIterator;

  // TODO: Update to use global 'default language'.
  public function __construct($language = "en", $base = "en", $cache = true) {
    $sql = sprintf("SELECT `language` FROM `languages` WHERE `id`='%s' LIMIT 1", DB::Quote($language));
    $result = mysql_query($sql);
    if ( mysql_num_rows($result) ) {
      $this->BaseLanguage = $base;
      $this->LanguageCode = $language;
      list($this->LanguageName) = mysql_fetch_row($result);
      mysql_free_result($result);
    } else {
      // TODO: Emit a warning saying the chosen language is invalid, defaulting to English.
      $this->BaseLanguage = 'en';
      $this->LanguageCode = 'en';
      $this->LanguageName = 'English';
    }
  
    if ( $cache ) {
      // Load the fallback language strings.  English is the default.
      $result = mysql_query(sprintf("SELECT `name` as `key`,`value` FROM `i18n` WHERE `language`='%s' AND `cache`=1", DB::Quote($this->BaseLanguage)));
      while ( $row = mysql_fetch_assoc($result) ) $this->LanguageCache[$row['key']] = $row['value'];

      // Load the selected language, overwriting the English ones.      
      $result = mysql_query(
                    sprintf("SELECT `name`,`value` FROM `i18n` WHERE `language`='%s' AND `cache`=1",
                            DB::Quote($this->LanguageCode)));
      while ( $row = mysql_fetch_assoc($result) ) $this->LanguageCache[$row['name']] = $row['value'];
    }
  }

  public function offsetExists($index, $strict = false) {
    // Check the cache first...
    if ( isset($this->LanguageCache[$index]) ) return true;
    
    // If it's not there, hope is not lost!  It might not be cached...
    $result = mysql_query(sprintf("SELECT `name` FROM `i18n` WHERE `name`='%s' AND (`language`='%s'%s) LIMIT 1", DB::Quote($index), DB::Quote($this->LanguageCode), $strict ? "" : sprintf(" OR `language`='%s'", DB::Quote($this->BaseLanguage))));
    if ( mysql_num_rows($result) ) return true;
    
    return false;
  }

  public function offsetGet($index) {
    if ( isset($this->LanguageCache[$index]) ) return $this->LanguageCache[$index];
    
    // If it's not there, hope is not lost!  It might not be cached...
    $result = mysql_query(sprintf("SELECT `value` FROM `i18n` WHERE `name`='%s' AND `language`='%s' LIMIT 1", DB::Quote($index), DB::Quote($this->LanguageCode)));
    if ( mysql_num_rows($result) ) {
      list($this->LanguageCache[$index]) = mysql_fetch_row($result);
      return $this->LanguageCache[$index]; // Now it's cached.
    } else {
      // Try again with the system default language... a translator somewhere isn't doing his job.
      $result = mysql_query(sprintf("SELECT `value` FROM `i18n` WHERE `name`='%s' AND `language`='en' LIMIT 1", DB::Quote($index)));
      if ( mysql_num_rows($result) ) {
        list($this->LanguageCache[$index]) = mysql_fetch_row($result);
        return $this->LanguageCache[$index]; // Now it's cached.
      } else {
        // TODO: Raise a warning about an invalid string.  Should never get here.
      }
    }
    
    // TODO: Raise an error of some kind...
    return "";
  }

  public function offsetSet($index, $value) {
    // Unfortunately, we can only manipulate entries in the current language this way.
    // Use the "AddTranslation" and "ModifyTranslation" methods for full control.
    if ( $this->offsetExists($index, true) ) {
      // Update an existing language entry.
      $this->UpdateTranslation($this->LanguageCode, $index, $value);
    } else {
      // Create a new language entry.
      $this->AddTranslation($this->LanguageCode, $index, $value);
    }
    return;
  }

  public function offsetUnset($index) {
    // TODO: Raise a warning - unsetting is not allowed.
    return;
  }
  
  public function AddTranslation($language, $index, $value, $cached = false) {
    if ( $this->offsetExists($index) ) return; // TODO: Raise a warning...
    $sql = sprintf("INSERT INTO `i18n` SET `language`='%s', `name`='%s', `value`='%s', `cache`=%d",
                   DB::Quote($language),
                   DB::Quote($index),
                   DB::Quote($value),
                   $cached ? 1 : 0);
    return mysql_query($sql);
  }
  
  public function UpdateTranslation($language, $index, $value) {
    if ( !$this->offsetExists($index) ) return; // TODO: Raise a warning...
    $sql = sprintf("UPDATE `i18n` SET `value`='%s' WHERE `name`='%s'",
                   DB::Quote($value),
                   DB::Quote($index));
    return mysql_query($sql);
  }
  
  public function StartIterator() {
    $this->LanguageIterator = mysql_query("SELECT * FROM `languages` ORDER BY `sort`, `language`");
  }
  
  public function Language() {
    return mysql_fetch_assoc($this->LanguageIterator);
  }
  
  public function Languages() {
    return mysql_num_rows($this->LanguageIterator);
  }
  
  public function ResetIterator() {
    return mysql_data_seek($this->LanguageIterator, 0);
  }
  
  public function AvailableLanguages() {
    $Languages = array();
    
    $this->StartIterator();
    while ( $Language = $this->Language() )
      $Languages[$Language['id']] = $Language['language'];
    
    return $Languages;
  }
  
  public function CurrentLanguage() {
    return $this->LanguageCode;
  }
}

?>