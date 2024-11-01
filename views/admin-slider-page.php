<?php
global $tile_sliders;
?><div class="tile-admin-nav">
<?php get_tile_slider_nav(); ?>
</div>
<?php if (empty($tile_sliders)) { ?>
    <span class="no-objects">No Sliders Found</span>
<?php } ?>
<?php foreach ($tile_sliders as $tile_slider) { ?>
    <div class="tile-admin-slider" id="tile-slider-<?php echo $tile_slider['id']; ?>">
        <h3>
            <?php echo $tile_slider['name']; ?>
            <span class="tile-slider-links"><a href="#" class="add-new-h2 edit-slider">Edit</a>
                <a href="#" class="add-new-h2 delete-slider">Delete</a>
                <input type="hidden" class="slider-id" value="<?php echo $tile_slider['id']; ?>"/>
            </span>
        </h3>
        <p class="tile-slide-count">
            <?php
            foreach ($tile_slider['slides'] as $slides) {
                if ($slides['type'] == 'tslide') {
                    ?>
                    <span class="tile-remove-rel">
                        <img src="<?php $img = wp_get_attachment_image_src($slides['img_url'], 'thumbnail');
            echo $img[0]; ?>" title="Title: <b><?php echo $slides['name'] ?></b><br/> Type - Image" class="tile-tip"/> 
                        <input type="hidden" value="<?php echo $slides['slide_id']; ?>,<?php echo $slides['slider_id'] ?>"/>
                    </span>
                    <?php
                } else {
                    $slide = get_post($slides['slide_id']);
                    ?>
                    <span class="tile-remove-rel">
                        <img src="<?php
                    $img = wp_get_attachment_image_src(get_post_thumbnail_id($slide->ID), 'thumbnail');
                    echo $img[0];
                    ?>" title="Title: <b><?php echo $slide->post_title; ?></b><br/> Type : Post" class="tile-tip"/> 
                        <input type="hidden" value="<?php echo $slide->ID; ?>,<?php echo $slides['slider_id'] ?>"/>
                    </span><?php
        }
                ?>

    <?php } ?>
        </p><code class="tile-slider-code">[tile-slider id=<?php echo $tile_slider['id']; ?> /]</code>

        <input type="hidden" class="slider-id" value="<?php echo $tile_slider['id']; ?>"/>
    </div>
<?php }; ?>