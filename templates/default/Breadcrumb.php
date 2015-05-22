<div<?= CMS::Properties($cmsValues, array('id', 'class', 'style')); ?>>
<? /* $categoryList = array_reverse($productPath); */ ?>
  <ol>
    <label>Navigation</label>
<?

$depth = count($_GLOBALS['productPath']) - 1;
foreach ( $_GLOBALS['productPath'] as $categoryID ) {
  if ( is_numeric($categoryID) && ( $categoryID == 0 ) ) {
    printf("    <li><a href=\"%s\">Top</a></li>", str_repeat("../", $depth));
  } else {
    // Retrieve category title, catching numeric or textual identifiers.
    if ( is_numeric($categoryID) ) {
      $sql = sprintf("SELECT `shortname` FROM `categories` WHERE `id`=%d LIMIT 1", $categoryID);
      list($langString) = mysql_fetch_row(mysql_query($sql));
    } else $langString = $categoryID;
    $langString = sprintf("category.%s.title", $langString);
    
    if ( $categoryID == $_GLOBALS['productPath'][sizeof($_GLOBALS['productPath']) - 1] ) {
      printf("    <li>%s</li>", $_GLOBALS['i18n'][$langString]);
    } else {
      printf("    <li><a href=\"%s\">%s</a></li>", "../" . str_repeat("../", $depth) . $categoryID . "/", $_GLOBALS['i18n'][$langString]);
    }
  }
  $depth--;
}
     
?>
  </ol>
</div>
