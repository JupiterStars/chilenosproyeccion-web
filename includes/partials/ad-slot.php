<?php
$size = $size ?? 'banner';
$id = $id ?? 'ad-slot';
$label = $label ?? 'Publicidad';
?>
<div class="ad-slot" data-size="<?= e($size) ?>" id="<?= e($id) ?>">
  <span><?= e($label) ?></span>
</div>
