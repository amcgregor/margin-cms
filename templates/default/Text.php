<div<?= CMS::Property($cmsValues, 'id'); ?><?= CMS::Property($cmsValues, 'class'); ?>>
<? if ( isset($cmsValues['title']) ) { ?>
  <h2><? if ( isset($cmsValues['link']) ) { ?><a href="<?= $cmsValues['link']; ?>"><? } ?><?= $cmsValues['title']; ?><? if ( isset($cmsValues['link']) ) { ?></a><? } ?></h2>
<? } ?>
<? if ( isset($cmsValues['image']) ) { ?>
  <? if ( isset($cmsValues['link']) ) { ?><a href="<?= $cmsValues['link']; ?>"><? } ?><img src="<?= $cmsValues['image']; ?>" alt="<?= $cmsValues['title']; ?>" class="promo" /><? if ( isset($cmsValues['link']) ) { ?></a><? } ?>
<? } ?>
<?= CMS::Indent($childData); ?>
</div>
