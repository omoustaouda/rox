<?php
$User = APP_User::login();
$words = new MOD_words();

$g = $gallery;

if ($deleted){ 
?>
<p class="note"><img src="images/misc/check.gif">&nbsp; &nbsp; <?php echo $words->getFormatted('GalleryImageDeleted'); ?>: <i><?php echo $g->title ?></i></p>
<?php } else { ?>
<p class="warning"><img src="images/misc/checkfalse.gif">&nbsp; &nbsp; <?php echo $words->getFormatted('GalleryImageNotDeleted'); ?>: <i><?php echo $g->title ?></i></p>
<?php } ?>