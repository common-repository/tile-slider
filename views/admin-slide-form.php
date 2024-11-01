<?php
global $tile_slide;
?><div class="tile-admin-nav">
    <div class="tile-nav-item">
        
    </div>
    <?php get_tile_slide_nav('addnew'); ?>
</div>
<form id="tile-slide-form">
    <table class="form-table">
        <tbody>
            <tr class="form-field form-required">
                <th scope="row"><label for="slide_name">Slide Title <span class="description">(required)</span></label></th>
                <td><input name="slide_name" type="text" id="slide_name" value="<?php echo (isset($tile_slide['name'])?$tile_slide['name']:''); ?>" /></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="slide_picture">Slide Picture <span class="description">(required)</span></label></th>
                <td>
                    <?php if (isset($tile_slide['img_url'])) { ?>
                    <span class="tile-image-slected">
                        <img src="<?php echo $tile_slide['img_url']; ?>" class="tile-form-image"/>
                        <input type="hidden" name="slide_image" value="<?php echo $tile_slide['img_url']; ?>"/>
                    </span><br/>
                    <?php } ?>
                    <input id="tile-image-upload" type="button" value="Upload Image" />
                </td>


            </tr>
            <tr class="form-field">
                <th scope="row"><label for="slide_desc">Slide Description </label></th>
                <td><textarea name="slide_desc"><?php echo (isset($tile_slide['desc'])?$tile_slide['desc']:''); ?></textarea></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><label for="slide_link">Slide Link </label></th>
                <td><input type="text" name="slide_link" value="<?php echo (isset($tile_slide['link'])?$tile_slide['link']:''); ?>"/></td>
            </tr>
        </tbody>
    </table>
   
    <?php if (isset($tile_slide['id'])) { ?>
        <input type="hidden" name="slide_id" class="slide-id" value="<?php echo $tile_slide['id']; ?>"/>
        <input name="add-slide" type="submit" id="tile-submit-slide" value="Save Slide" class="button-primary"> 
        <input name="delete-slide" type="submit" id="tile-delete-slide" value="Delete Slide" class="button-secondary"> 

    <?php } else { ?>
        <input name="add-slide" type="submit" id="tile-submit-slide" value="Add Slide" class="button-primary"> 
    <?php } ?>
    <input type="hidden" name="action" value="add_tile_slide" />
</form>