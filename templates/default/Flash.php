<object<?= CMS::Property($cmsValues, 'id'); ?><?= CMS::Property($cmsValues, 'class'); ?><?= CMS::Property($cmsValues, 'style'); ?> type="application/x-shockwave-flash" data="<?= $cmsValues['src']; ?>" width="<?= $cmsValues['width']; ?>" height="<?= $cmsValues['height']; ?>">
  <param name="movie" value="<?= $cmsValues['src']; ?>" />
  <a href="http://www.macromedia.com/go/getflash" title="Get Flash!">Visit Macromedia to download the Flash player.</a>
</object>