<?php global $tile_slides, $tile_pages, $tile_curr_page,$tile_posts; ?>
<div class="tile-admin-nav">
    <div class="tile-nav-item">
        <?php
        if ($tile_pages > 1) {
            for ($i = 0; $i < $tile_pages; $i++) {
                ?><span>
                    <a href="#" class="add-new-h2 tile-pagination <?php echo ($tile_curr_page == ($i + 1)) ? 'tile-active' : ''; ?>"><?php echo $i + 1; ?></a>
                    <input type="hidden" class="tpage" value="<?php echo ($i + 1); ?>"/>
                    <input type="hidden" class="titem" value="tile_slide_posts"/></span>
                <?php
            }
        }
        ?>
    </div>
    <?php get_tile_slide_nav('posts'); ?>
</div>
<?php
while ($tile_posts->have_posts()) : $tile_posts->the_post();
    ?>
    <div class="tile-three-col tile-slide-action" title="<b><?php the_title(); ?></b><br/><?php the_excerpt(); ?>">
        <img src="<?php $img= wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID(),'thumbnail')); echo $img[0]; ?>"/>
        <input type="hidden" class="slide-id" value="<?php the_ID(); ?>"/>
        <input type="hidden" class="slide-name" value="<?php the_title(); ?>"/>
        <input type="hidden" class="slide-type" value="tpost"/>
    </div>
    <?php
endwhile;
?>
           