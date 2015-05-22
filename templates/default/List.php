<?

$ListType = "";

switch ( $cmsValues['type'] ) {
  case "numbered":
  case "ordered":
  case "ol":
    $ListType = "ol";
    break;

  case "bullet":
  case "unordered":
  case "ul":
  default:
    $ListType = "ul";
    break;
}

?>
<div<?= CMS::Property($cmsValues, 'id'); ?><?= CMS::Property($cmsValues, 'class'); ?>>
<? if ( isset($cmsValues['title']) ) { ?>
  <h2><? if ( isset($cmsValues['link']) ) { ?><a href="<?= $cmsValues['link']; ?>"><? } ?><?= $cmsValues['title']; ?><? if ( isset($cmsValues['link']) ) { ?></a><? } ?></h2>
<? } ?>
<? if ( isset($cmsValues['image']) ) { ?>
  <? if ( isset($cmsValues['link']) ) { ?><a href="<?= $cmsValues['link']; ?>"><? } ?><img src="<?= $cmsValues['image']; ?>" alt="<?= $cmsValues['title']; ?>" class="promo" /><? if ( isset($cmsValues['link']) ) { ?></a><? } ?>
<? } ?>
  <<?= $ListType; ?>>
<?= CMS::Indent($childData, 2); ?>
  </<?= $ListType; ?>>
</div>
