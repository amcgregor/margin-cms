<?
//
// Margin-CMS Common Functions 1.0-pre2
// Copyright (c) 2004-2005, Alice Bevan-McGregor.
// All Rights Reserved.
//
// This version of Margin-CMS requires PHP 5 and above.
//

class CMSErrors {
  public function CMSErrors() {
    set_error_handler(array(&$this, 'Handler'));
    return;
  }
  
  public function Handler($errno, $errstr, $errfile, $errline) {
    $errType = array (
              0    => "General Error",
              1    => "PHP Error",
              2    => "PHP Warning",
              4    => "Parsing Error",
              8    => "PHP Notice",
              16   => "Core Error",
              32   => "Core Warning",
              64   => "Compile Error",
              128  => "Compile Warning",
              256  => "PHP User Error",
              512  => "PHP User Warning",
              1024 => "PHP User Notice",
              -1   => "Unknown Error"
            );
    
    while ( ob_get_level() ) ob_end_clean();
    
    $info = array();

    if (($errNo & E_USER_ERROR) && is_array($arr = @unserialize($errMsg)))
      foreach ($arr as $k => $v) $info[$k] = $v;
        
    $trace = array();
    $traceText = "";

    if (function_exists('debug_backtrace')) {
        $trace = debug_backtrace();
        array_shift($trace);
        foreach ( $trace as $call ) {
          $traceText .= sprintf("        ...called from line %d of %s by %s.\n",
                                $call['line'], basename($call['file']), $call['function']);
        }
    }
    
    if ( !in_array($errno, $errType) ) $type = $errType[0];
    else $type = $errType[$errType];
    
    printf("<pre class=\"cmsError\">%s on line %d of %s:\n    <i>%s</i>%s</pre>\n\n",
           $type, $errline, $errfile, $errstr{strlen($errstr)} != '.' ? $errstr . "." : $errstr,
           !empty($traceText) ? "\n" . $traceText : "");
  }
}

class CMS {
  static function Version() { return MCMS_VERSION; }
  
  static function ParseURL() {
    //parse_url($_SERVER['REDIRECT_QUERY_STRING'])
  }
  
  static function GetMicrotime() {
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
  }
    
  static function TemplateInclude($objectName, $fileName = NULL, $cmsValues = NULL, $childData = "") {
    global $cmsGlobalXML;
    global $cmsSiteXML;
    global $cmsServerXML;
    global $cmsPageXML;
    global $_GLOBALS;
  
    $includeFile = sprintf("%s/templates/%s/%s",
                          $_SERVER["MCMS_BASEPATH"],
                          $cmsSiteXML->Layout['theme'],
                          $objectName);
  
    if ( !is_null($fileName) ) $includeFile .= sprintf("/%s", $fileName);
  
    $includeFile .= ".php";
  
    if ( file_exists($includeFile) ) return include($includeFile);
  
    return false;
  }
  
  static function Indent($text, $level = 1, $size = 2) {
    return str_repeat(" ", $level * $size) . str_replace("\n", "\n" . str_repeat(" ", $level * $size), trim($text)) . "\n";
  }
  
  static function Property(&$xmlData, $htmlProp, $xmlProp = NULL, $default = NULL) {
    global $cmsValues;
  
    if ( is_null($xmlProp) ) $xmlProp = $htmlProp;
  
    if ( isset($xmlData[$xmlProp])
          && ( is_numeric($xmlData[$xmlProp]) || !empty($xmlData[$xmlProp]) ) ) {
      return sprintf(" %s=\"%s\"", $htmlProp, $xmlData[$xmlProp]);
    } else if ( !is_null($default) ) {
      return sprintf(" %s=\"%s\"", $htmlProp, $default);
    }
  
    return "";
  }
  
  static function Properties(&$xmlData, $properties) {
    $builtString = "";
    foreach ( $properties as $property ) {
      $builtString .= CMS::Property($xmlData, $property);
    }
    return $builtString;
  }
  
  static function ob_tidyhandlercustom($buffer) {
    global $cmsGlobalXML;
  
    $tidy = new tidy;
    $tidy->parseString($buffer, $_SERVER["MCMS_BASEPATH"] . $cmsGlobalXML->General->TidyConfig);
    $tidy->CleanRepair();
  
    return $tidy;
  }
  
  static function IsTrue($value) {
    if ( is_string($value) ) {
      switch ( strtolower($string) ) {
        case "1":
        case "y":
        case "yes":
        case "true":
          return true;
          break;
          
        default:
          return false;
          break;
      }
    } else if ( is_int($value) || is_bool($value) ) {
      if ( $value ) return true;
    }
    
    return false;
  }
  
  static function RecurseDocument(&$xmlElement, $name = "") {
    $hasChildren = false;
  
    ob_start();
    foreach ( $xmlElement->children() as $childName => $childNode ) {
      CMS::RecurseDocument($childNode, $childName);
      $hasChildren = true;
    }
    $childContent = ob_get_clean();
  
    CMS::TemplateInclude($name, NULL, $xmlElement, $hasChildren ? $childContent : $xmlElement);
  }
  


}

?>