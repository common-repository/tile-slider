<?php
global $tile_slider;
?>
<div class="tile-admin-nav">
    <?php get_tile_slider_nav(); ?>

</div>
<form id="tile-slider-form">
    <table class="form-table">
        <tbody><tr class="form-field">
                <th scope="row"><label for="slider_title">Slider Name </label></th>
                <td><input name="slider_title" type="text" id="slider_title" value="<?php echo (isset($tile_slider['name']) ? $tile_slider['name'] : ''); ?>" /></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="slider_width">Slider Width </label></th>
                <td><input name="slider_width" type="text" id="slider_width" value="<?php echo (isset($tile_slider['slider_width']) ? $tile_slider['slider_width'] : '800'); ?>"></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="slider_height">Slider Height </label></th>
                <td><input name="slider_height" type="text" id="slider_height" value="<?php echo (isset($tile_slider['slider_height']) ? $tile_slider['slider_height'] : '500'); ?>"></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="animation_speed">Animation Speed </label></th>
                <td><input name="animation_speed" type="text" id="animation_speed" value="<?php echo (isset($tile_slider['animation_speed']) ? $tile_slider['animation_speed'] : '2000'); ?>"></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="tile_duration">Tile Animation Speed </label></th>
                <td><input name="tile_duration" type="text" id="tile_duration" value="<?php echo (isset($tile_slider['tile_duration']) ? $tile_slider['tile_duration'] : '1500'); ?>"></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="animation_dir">Animation Direction </label></th>
                <td>
                    <select id="animation_dir" name="animation_dir">
                        <option value="1" <?php echo (isset($tile_slider['animation_dir']) ? ($tile_slider['animation_dir']=='1'?'selected="selected"':'') : ''); ?>>Top</option>
                        <option value="2" <?php echo (isset($tile_slider['animation_dir']) ? ($tile_slider['animation_dir']=='2'?'selected="selected"':'') : ''); ?>>Left</option>
                        <option value="3" <?php echo (isset($tile_slider['animation_dir']) ? ($tile_slider['animation_dir']=='3'?'selected="selected"':'') : ''); ?>>Right</option>
                        <option value="4" <?php echo (isset($tile_slider['animation_dir']) ? ($tile_slider['animation_dir']=='4'?'selected="selected"':'') : ''); ?>>Bottom</option>
                        <option value="5" <?php echo (isset($tile_slider['animation_dir']) ? ($tile_slider['animation_dir']=='5'?'selected="selected"':'') : ''); ?>>TopLeft</option>
                        <option value="6" <?php echo (isset($tile_slider['animation_dir']) ? ($tile_slider['animation_dir']=='6'?'selected="selected"':'') : ''); ?>>BottomLeft</option>
                        <option value="7" <?php echo (isset($tile_slider['animation_dir']) ? ($tile_slider['animation_dir']=='7'?'selected="selected"':'') : ''); ?>>TopRight</option>
                        <option value="8" <?php echo (isset($tile_slider['animation_dir']) ? ($tile_slider['animation_dir']=='8'?'selected="selected"':'') : ''); ?>>BottomRight</option>
                        <option value="9" <?php echo (isset($tile_slider['animation_dir']) ? ($tile_slider['animation_dir']=='9'?'selected="selected"':'') : ''); ?>>random</option>
                    </select>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="animation">Animation</label></th>
                <td>
                    <select id="animation" name="animation">
                        <option value="tileProgressive" <?php echo (isset($tile_slider['animation']) ? ($tile_slider['animation']=='tileProgressive'?'selected="selected"':'') : ''); ?>>tileProgressive</option>
                        <option value="tileDiagonal" <?php echo (isset($tile_slider['animation']) ? ($tile_slider['animation']=='tileDiagonal'?'selected="selected"':'') : ''); ?>>tileDiagonal</option>
                        <option value="slice" <?php echo (isset($tile_slider['animation']) ? ($tile_slider['animation']=='slice'?'selected="selected"':'') : ''); ?>>Vertical Bars</option>
                        <option value="hslice" <?php echo (isset($tile_slider['animation']) ? ($tile_slider['animation']=='hslice'?'selected="selected"':'') : ''); ?>>Horizontal Bars</option>
                        <option value="skin" <?php echo (isset($tile_slider['animation']) ? ($tile_slider['animation']=='skin'?'selected="selected"':'') : ''); ?>>GrowNFade</option>
                        <option value="random" <?php echo (isset($tile_slider['animation']) ? ($tile_slider['animation']=='random'?'selected="selected"':'') : ''); ?>>Random</option>
                    </select>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="interval">Interval</label></th>
                <td><input name="interval" type="text" id="interval" value="<?php echo (isset($tile_slider['interval']) ? $tile_slider['interval'] : '0'); ?>"></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="tile_width">Tile Width</label></th>
                <td><input name="tile_width" type="text" id="tile_width" value="<?php echo (isset($tile_slider['tile_width']) ? $tile_slider['tile_width'] : '75'); ?>"></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="tile_height">Tile Height</label></th>
                <td><input name="tile_height" type="text" id="tile_height" value="<?php echo (isset($tile_slider['tile_height']) ? $tile_slider['tile_height'] : '75'); ?>"></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="thumb_width">Thumbnail Width</label></th>
                <td><input name="thumb_width" type="text" id="thumb_width" value="<?php echo (isset($tile_slider['thumb_width']) ? $tile_slider['thumb_width'] : '150'); ?>"></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="thumb_height">Thumbnail Height</label></th>
                <td><input name="thumb_height" type="text" id="thumb_height" value="<?php echo (isset($tile_slider['thumb_height']) ? $tile_slider['thumb_height'] : '150'); ?>"></td>
            </tr>
        </tbody>
    </table>

    <?php if (isset($tile_slider['id'])) { ?>
        <input type="hidden" name="slider_id" value="<?php echo $tile_slider['id']; ?>"/>
        <input name="add-slide" type="submit" id="tile-add-slider" value="Save Slider" class="button-primary"/>

    <?php } else { ?>
        <input name="add-slide" type="submit" id="tile-add-slider" value="Add Slider" class="button-primary"/>
    <?php } ?>
    <input name="action" type="hidden" value="add_tile_slider"/>
</form>