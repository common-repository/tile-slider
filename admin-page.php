<?php
/*
 * Tile Slider Admin Page
 * 
 */
global $tile_slides, $tile_pages, $tile_curr_page;
?>
<div class="wrap">
    <div id="icon-tile" class="icon32"><br></div>
    <h2>Tile Slider</h2>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class="postbox-container" style="width:59%;">
                <div id="normal-sortables" class="meta-box-sortables"><div class="postbox " style="display: block; ">
                        <h3 class="tile-hndle"><span>Slides</span></h3>
                        <div class="inside" id="admin-slide-screen">
                            <div class="tile-admin-nav">
                                <div class="tile-nav-item">
                                    <?php
                                    if ($tile_pages > 1) {
                                        for ($i = 0; $i < $tile_pages; $i++) {
                                            ?>
                                            <span>
                                                <a href="#" class="add-new-h2 tile-pagination <?php echo ($tile_curr_page == ($i + 1)) ? 'tile-active' : ''; ?>"><?php echo $i + 1; ?></a>
                                                <input type="hidden" class="tpage" value="<?php echo ($i + 1); ?>"/>
                                                <input type="hidden" class="titem" value="tile_slide_page"/>
                                            </span>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <?php get_tile_slide_nav(); ?>
                            </div>
                            <?php
                            if (is_array($tile_slides) && !empty($tile_slides)) {
                                foreach ($tile_slides as $tile_slide) {
                                    ?>
                                    <div class="tile-three-col tile-slide-action" title="<b><?php echo $tile_slide['name']; ?></b><br/><?php echo $tile_slide['desc']; ?>">
                                        <img src="<?php $img = wp_get_attachment_image_src($tile_slide['img_url'], 'thumbnail');
                            echo $img[0]; ?>" />
                                        <input type="hidden" class="slide-id" value="<?php echo $tile_slide['id']; ?>"/>
                                        <input type="hidden" class="slide-name" value="<?php echo $tile_slide['name']; ?>"/>
                                        <input type="hidden" class="slide-type" value="tslide"/>
                                        <div class="tile-delete-slide"></div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>	</div><div class="postbox-container" style="width:39%;">
                <div id="side-sortables" class="meta-box-sortables"><div class="postbox ">
                        <h3 class="tile-hndle tile-slider-hndle"><span>Sliders</span></h3>
                        <div class="inside">
                            <div class="tile-admin-nav">
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


                        </div>
                    </div>

                </div>
            </div>

            <div class="clear"></div>
        </div>
    </div>