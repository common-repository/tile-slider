/*
 * jQuery File for admin interfaces such as ajax, tooltip , drag-and-drop etc.
 */
jQuery(document).ready(function($){
    //Set Ajax animation of dots
    window.setInterval("animate_tile_ajax()", 500);
    
    //show_tile_ajax();
    
    $(".tile-admin-ajax").die().live("click",function(){
        console.log("requesting for "+$(this).siblings(".tile-admin-action").val());
        var c_link=$(this);
        data={
            'action':c_link.siblings(".tile-admin-action").val()
        }
        $.post(ajaxurl,data,function(resp){
            c_link.parents(".inside").html(resp);
        })
    })
    $(".tile-pagination").die().live("click",function(){
        var c_link=$(this);
        data={
            'action':c_link.siblings(".titem").val(),
            'tpage':c_link.siblings(".tpage").val()
        }
        $.post(ajaxurl,data,function(resp){
            c_link.parents(".inside").html(resp);
        })
    })
    $(".edit-slider").die().live('click',function(evt){
        evt.preventDefault();
        var sid=$(this).siblings(".slider-id").val();
        var data={
            'action':'tile_slider_form',
            'slider_id':sid
        };
        c_link=$(this);
        $.post(ajaxurl,data,function(resp){
            console.log(resp);
            c_link.parents(".inside").html(resp);
        })
    });
    $(".tile-slide-edit").die().live('click',function(evt){
        evt.preventDefault();
        var sid=$(this).siblings(".slide-id").val();
        var data={
            'action':'tile_slide_form',
            'slide_id':sid
        };
        $.post(ajaxurl,data,function(resp){
           
            console.log(resp,$("#admin-slide-screen").length);
            $("#admin-slide-screen").html(resp);
        })
    });
    $(".delete-slider").die().live('click',function(){
        var hndle=$(this);
        apprise('Are you sure to delete this slider?', {
            'confirm':true
        },function(r){
            if(r)
            { 
                var data={
                    'item-type':'slider',
                    'item-id':hndle.siblings(".slider-id").val(),
                    'action':'tile_delete_item'
                };
                $.post(ajaxurl,data,function(resp){
                    $("#tile-sliders").trigger("click");
                })
            }
        });
    })
    $(".tile-delete-slide").die().live('click',function(evt){
        evt.preventDefault();
        var hndle=$(this).parents('.tile-slide-action');
        apprise('Are you sure to delete this slide?', {
            'confirm':true
        },function(r){
            if(r)
            { 
                var data={
                    'item-type':'slide',
                    'item-id':hndle.find(".slide-id").val(),
                    'action':'tile_delete_item'
                };
                $.post(ajaxurl,data,function(resp){
                    $("#tile-slides").trigger("click");
                })
            }
        });
    })
});
/*
 *Custom jQuery function mostly for ajax complete event
 *
 */
jQuery(document).bind("tile_interface_init",function(){
    jQuery(".tile-tip").unbind("hover").tipTip();
    jQuery(".tile-slide-action").unbind("hover").tipTip({
        keepAlive:true
    });
    if(jQuery( ".tile-three-col" ).length){
        jQuery( ".tile-three-col" ).draggable({
            revert: true
        });
        jQuery(".tile-three-col").bind("dragstart",function(event,ui){
            jQuery("#tiptip_holder").hide();
            if(!jQuery(this).find("img").hasClass("tile-picked")){
                jQuery(this).find("img").addClass("tile-picked");
            }
            jQuery(this).removeClass("tile-tip");
        });
        jQuery(".tile-three-col").bind("drag",function(event,ui){
            jQuery("#tiptip_holder").hide();

        });
        jQuery(".tile-three-col").bind("dragstop",function(event,ui){
            jQuery(this).find("img").removeClass("tile-picked");
            jQuery(this).addClass("tile-tip");

        });
    }
    if(jQuery( ".tile-admin-slider" ).length){
        jQuery( ".tile-admin-slider" ).droppable({
            activeClass:"tile-greedy-slider",
            hoverClass:"tile-slider-drop",
            drop: function( event, ui ) {
                var data={
                    'action':'add_tile_rel',
                    'slider':jQuery(this).find(".slider-id").val(),
                    'slide':ui.draggable.find(".slide-id").val(),
                    'slide_type':ui.draggable.find(".slide-type").val()
                };
                var c_slider=jQuery(this);
                jQuery.post(ajaxurl,data,function(resp){
                    var part=c_slider.parents(".inside");
                    var c_slider_id=c_slider.attr("id");
                    c_slider.parents(".inside").html(resp);
                    c_slider=jQuery("#"+c_slider_id);
                    status=part.find(".ajax-stat").val();
                    console.log(status);
                    if(status!="added"){
                        //console.log("added");
                        c_slider.addClass("tile-drop-error"); 
                        setTimeout(function(){
                            c_slider.removeClass("tile-drop-error");
                        },500);
                    //c_slider.delay(800).removeClass("tile-drop-error");
                    }else{
                        //console.log("not added");
                        c_slider.addClass("tile-drop-success"); 
                        setTimeout(function(){
                            c_slider.removeClass("tile-drop-success");
                        },500);
                    //c_slider.delay(800).removeClass("tile-drop-success");
                    }
                })
            }
        });
    }
    var curr_input=0;
    jQuery('#tile-image-upload').die().live("click",function() {
        curr_input = jQuery(this);
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });
    window.send_to_editor = function(html) {
        imgurl = jQuery('img',html).attr('src');
        var re_attach_id=/wp-image-(\d*)/;
        var res=html.match(re_attach_id);
        if(curr_input.siblings(".tile-image-slected").length)
        {
            curr_input.siblings(".tile-image-slected").remove();
            curr_input.siblings("br").remove();
        }
        curr_input.before('<span class="tile-image-slected"><img src="'+imgurl+'" class="tile-form-image"/><input type="hidden" name="slide_image" value="'+res[1]+'"/></span><br/>');
        tb_remove();
    }
    /*
     * Ajax Form serialization
     */
    jQuery("#tile-slide-form").die().live("submit",function(){
        var data=jQuery(this).serialize();
        jQuery.post(ajaxurl,data,function(resp){
            resp=jQuery.parseJSON(resp);
            if(resp.success=="success"){
                jQuery("#tile-slides").trigger('click').die();
            }
            else
            {
                console.log("Opps!",resp);
            }
        })
        return false;
    });
    /*
     * Serialize and send slider form to create a slider
     */
    jQuery("#tile-slider-form").die().live("submit",function(){
        var data=jQuery(this).serialize();
        jQuery.post(ajaxurl,data,function(resp){
            resp=jQuery.parseJSON(resp);
            if(resp.success=="success"){
                jQuery("#tile-sliders").trigger('click');
            }
            else
            {
                console.log("Opps!",resp);
            }
        })
        return false;
    });
    /*
     * Remove a Slide from slider screen
     */
    jQuery(".tile-remove-rel img").die().live('click',function(){
        var post={
            'action':'remove_tile_rel',
            'rel':jQuery(this).siblings("input").val()
        }
        var c_rel=jQuery(this).parent();
        jQuery.post(ajaxurl,post,function(resp){
            console.log(resp);
            c_rel.remove();
            
        });
    })
    
})
function show_tile_ajax(){
    if(!jQuery("body").find(".tile-loading").length){
        jQuery("body").append('<span class="tile-loading">Loading</span>')
    }
    
}
var ata_count=0;
function animate_tile_ajax(){
    
    if(jQuery("body").find(".tile-loading").length){
        console.log("animating");
        var html=jQuery(".tile-loading").html();
        if(ata_count<3){
            html+='.';
            ata_count++;
        }else{
            ata_count=0;
            html=html.replace("...", "");
        }
        jQuery(".tile-loading").html(html);
    }
}