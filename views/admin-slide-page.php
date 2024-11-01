<?php global $tile_slides, $tile_pages, $tile_curr_page; ?>
<div class="tile-admin-nav">
    <div class="tile-nav-item">
        <?php
        if ($tile_pages > 1) {
            for ($i = 0; $i < $tile_pages; $i++) {
                ?><span>
                    <a href="#" class="add-new-h2 tile-pagination <?php echo ($tile_curr_page == ($i + 1)) ? 'tile-active' : ''; ?>"><?php echo $i + 1; ?></a>
                    <input type="hidden" class="tpage" value="<?php echo ($i + 1); ?>"/>
                    <input type="hidden" class="titem" value="tile_slide_page"/></span>
                <?php
            }
        }
        ?>
    </div>
    <?php get_tile_slide_nav('slides'); ?>
</div>
<?php
if (is_array($tile_slides) && !empty($tile_slides)) {
    foreach ($tile_slides as $tile_slide) {
        ?>
        <div class="tile-three-col tile-slide-action" title="<b><?php echo $tile_slide['name']; ?></b><br/><?php echo $tile_slide['desc']; ?>">
            <img src="<?php $img=wp_get_attachment_image_src($tile_slide['img_url'],'thumbnail'); echo $img[0]; ?>" />
            <input type="hidden" class="slide-id" value="<?php echo $tile_slide['id']; ?>"/>
            <input type="hidden" class="slide-name" value="<?php echo $tile_slide['name']; ?>"/>
            <input type="hidden" class="slide-type" value="tslide"/>
            <div class="tile-delete-slide"></div>
        </div>
        <?php
    }
}
?>
           