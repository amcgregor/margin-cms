<?

// Margin-Cart Product Listing Setup

$categoryList = array_reverse($_GLOBALS['productPath']);
$currentCategoryLongName = $categoryList[0];

// Get the details about the current category - by name, ID, with the top
// category being a special case (it's always numeric zero).
if ( is_numeric($categoryList[0]) ) {
  if ( $categoryList[0] == 0 ) {
    $currentCategoryID = $categoryList[0];
    $currentCategoryShortName = "top";
    $currentCategoryLongName = $_GLOBALS['i18n'][sprintf("category.%s.title", $currentCategoryShortName)];
  } else {
    $sql = sprintf("SELECT `shortname` FROM `categories` WHERE `id`=%d", $categoryList[0]);
    $currentCategoryID = $categoryList[0];
    list($currentCategoryShortName) = mysql_fetch_row(mysql_query($sql));
    $currentCategoryLongName = $_GLOBALS['i18n'][sprintf("category.%s.title", $currentCategoryShortName)];
  }
} else {
  $sql = sprintf("SELECT `id` FROM `categories` WHERE `shortname`='%s'", $categoryList[0]);
  list($currentCategoryID) = mysql_fetch_row(mysql_query($sql));
  $currentCategoryShortName = $categoryList[0];
  $currentCategoryLongName = $_GLOBALS['i18n'][sprintf("category.%s.title", $currentCategoryShortName)];
}

// Get all (direct) children categories, sorted by sort order, into an assoc.
// array called $currentCategoryChildren with $id => $shortname.
$sql = sprintf("SELECT `id`, `shortname` FROM `categories` WHERE `parent`=%d ORDER BY `sort`", $currentCategoryID);
$lookup = mysql_query($sql);
$currentCategoryChildren = array();
while ( $category = mysql_fetch_assoc($lookup) )
  $currentCategoryChildren[$category['id']] = $category['shortname'];
mysql_free_result($lookup);

// Retrieve a listing (just a SQL result for now) of all items in this category.
$sql = sprintf("SELECT `products`.* FROM `products`, `categorylink` WHERE `products`.`id` = `categorylink`.`product` AND `categorylink`.`category` = %d AND `products`.`status`='' AND `products`.`available`<%d ORDER BY `shortname`", $currentCategoryID, time());
$products = mysql_query($sql);

// Check to see what type of listing we want to display - can be set in a
// GET variable, defaulted from XML, or use the system-settings default.
// Type 1 ("short"): Raw listing - the product's ID, name, cost, and "Add to Cart" buttons.
// Type 2 ("descriptive"): Two-line Listing - as above but with short description.
// Type 3 ("graphic"): Three-line Listing - as above, but with a small image.
$listingType = isset($_GET['lt']) ? $_GET['lt'] : ( isset($cmsValues['type']) ? $cmsValues['type'] : $_GLOBALS['Configuration']['products.listing.default'] );

?>

<div<?= CMS::Properties($cmsValues, array('id', 'class', 'style')); ?>>
  <h2><?= $currentCategoryLongName; ?></h2>
  
<? if ( !empty($currentCategoryChildren) ) { ?>
<?   if ( $currentCategoryID != 0 ) { ?>
  <h3>Sub-Categories</h3>
<? } ?>
  <ol>
<?   foreach ( $currentCategoryChildren as $id=>$shortname ) { ?>
    <li><a href="<?= $shortname; ?>/"><?= $_GLOBALS['i18n'][sprintf("category.%s.title", $shortname)]; ?></a></li>
<?   } ?>
  </ol>
<? } ?>

<? if ( mysql_num_rows($products) ) { ?>
  <h3>Products</h3>
<?   while ( $product = mysql_fetch_assoc($products) ) {
       // look up the title and description
       $sql = sprintf("SELECT `name`, `shortdescription` AS `description` FROM `product-i18n` WHERE `product`=%d AND `language`='%s' LIMIT 1",
                      $product['id'], $_GLOBALS['i18n']->CurrentLanguage());
       $product['strings'] = mysql_fetch_assoc(mysql_query($sql));
 ?>
  <div class="product">
    <img src="/images/objects/package.png" />
    <div class="productactions"><a href="/add/<?= $product['code']; ?>">Add to Cart</a></div>
    <div class="productprice">$<?= number_format($product['price']); ?></div>
    <div class="productcode"><?= $product['code']; ?>:</div>
    <div class="productname"><a href="/details/<?= $product['code']; ?>"><?= $product['strings']['name']; ?></a></div>
    <div class="description"><?= $product['strings']['description']; ?></div>
    <div style="clear: both;"></div>
  </div>
<?   } ?>
<? } else { ?>
<? } ?>

</div>
