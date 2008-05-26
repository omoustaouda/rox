﻿<?php
$User = APP_User::login();
$request = PRequest::get()->request;
//$Gallery = new Gallery;
if ($User) {
    //$callbackId = $Gallery->editGalleryProcess($gallery);
    //$vars =& PPostHandler::getVars($callbackId);
    $R = MOD_right::get();
    $GalleryRight = $R->hasRight('Gallery');
}
if (!isset($vars['errors'])) {
    $vars['errors'] = array();
}
$i18n = new MOD_i18n('date.php');
$format = $i18n->getText('format');
$words = new MOD_words();

$g = $gallery;
$g->user_handle = MOD_member::getUsername($g->user_id_foreign);
?>

<div class="float_left">
<h2 id="g-title"><?=$g->title ?></h2>
<?php if ($User && $User->getId() == $g->user_id_foreign) {
?>
<script type="text/javascript">
new Ajax.InPlaceEditor('g-title', 'gallery/ajax/set/', {
        callback: function(form, value) {
            return '?item=<?=$g->id?>&title=' + decodeURIComponent(value)
        },
        ajaxOptions: {method: 'get'}
    })
</script>
<?php } ?>

<?php 
    if (!$g->text == 0) {echo '<p id="g-text">'.$g->text.'</p>';}
    else { ?>
        <p id="g-text"><?php echo $words->getFormatted('GalleryAddDescription'); ?></p>
<?php    } 
    if ($User && $User->getId() == $g->user_id_foreign) {
?>
        <script type="text/javascript">
        new Ajax.InPlaceEditor('g-text', 'gallery/ajax/set/', {
                callback: function(form, value) {
                    return '?item=<?=$g->id?>&text=' + decodeURIComponent(value)
                },
                ajaxOptions: {method: 'get'}
            })
        </script>
<?php 
}

echo '<p class="small"> '.$cnt_pictures.' '.$words->getFormatted('GalleryImagesTotal').' </p>';
echo '
    <div class="floatbox" style="padding-top: 30px;">
        '.MOD_layoutbits::PIC_30_30($g->user_handle,'',$style='float_left').'
    <p class="small">'.$words->getFormatted('GalleryUploadedBy').': <a href="bw/member.php?cid='.$g->user_handle.'">'.$g->user_handle.'</a>.<br /></p>
    <p class="small"><a href="gallery/show/user/'.$g->user_handle.'/galleries">'.$words->getFormatted('GalleryAllGalleriesBy').' <img src="images/icons/images.png"></a></p>
    </div>
    ';

if ($User && (($User->getId() == $g->user_id_foreign) || ($GalleryRight > 1)) ) {
    echo '
    <div class="floatbox" style="padding-top: 30px;">
    <p class="small"><a style="cursor:pointer" href="gallery/show/galleries/'.$g->id.'/delete" class="button" onclick="return confirm(\''. $words->getFormatted("confirmdeletegallery").'\')"> Delete gallery </a></p>
    </div>';
}

?>
<p style="padding-top: 30px;"></p>
</div>
