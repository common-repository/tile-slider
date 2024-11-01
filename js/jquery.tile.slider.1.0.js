/*
 * jQuery Tile Slider v 1.0
 * http://kumarz.in/tile
 *
 * Copyright 2011, Rajesh Kumar Sharma
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * March 2010
 */
(function( $ ){
    /**
     * Loads and setsup a tile slider on element passed.
     * Also saves slider data on the element using .data() method
     * @tileSlider
     * @param {(String|Object)} elem Element to setup tile slider on
     * @param {?Object}  options Options for tile Slider
     */
    var tileSlider=function(elem,options){
        /**
         *Calculates dimentions and animation speeds for slider
         *@param {tileData} slider Tile Slider's Settings Data 
         */
        var tileDimentions=function(slider){
            slider.rows=Math.floor(slider.sliderWidth/slider.tileWidth);
            slider.cols=Math.floor(slider.sliderHeight/slider.tileHeight);
            slider.sliderWidth=slider.rows*slider.tileWidth;
            slider.sliderHeight=slider.cols*slider.tileHeight;
            slider.diff=(slider.tansitionSpeed/(slider.rows*slider.cols)/2);
            return slider;
        }
        /**
         * creates slider elments and sets up events required for Tile Slider
         * @param {tileData} slider Tile Slider's Settings Data 
         */
                
                
                
        var tileSetup=function(slider,options){
            /*HTML Setup Start*/
            slider.e.html('');
            slider.e.prepend('<div class="arrow left"></div>');//left Arrow
            slider.e.append('<div class="arrow right"></div>');//Right Arrow
            slider.e.append('<div class="caption"></div>');
            slider.e.append('<div class="tile-nav">\
                    </div>');//Navigation
            slider.e.append('<div class="tile-navthumb">\
                        <div class="navthumb-outer">\
                            <div class="navthumb-inner">\
                            </div>\
                        </div>\
                    </div>');
            slider.nav=slider.e.find(".tile-nav");
            slider.navth=slider.e.find(".tile-navthumb");
            slider.navin=slider.e.find(".navthumb-inner");
            for(var i=0;i<slider.count;i++){
                slider.e.find(".tile-nav").append('<span class="bullet"><a href="#"></a></span>');
                slider.e.find(".navthumb-inner").append('<div class="preview"><img class="nav-img" src="'+slider.images[i]+'" width="140" height="70"></div>');
            }
            slider.e.find(".tile-nav").append('<span class="clear"></span>');
            slider.e.find(".navthumb-inner").append('<span class="clear"></span>');
            /*HTML Setup End*/
            /*Events setup Start
             *@todo
             *Use Global Variables here*/
            slider.e.find(".tile-nav").find(".bullet a").die().live("click",function(e){
                e.preventDefault();
                if(slider.current.stat){
                    clearInterval(auto);
                    $(this).parent().siblings(".active").removeClass("active");
                    $(this).parent().addClass("active");
                    var idx=slider.nav.find(".bullet").index($(this).parent());
                    tileTransition(idx+1,slider);
                }
            });
            slider.e.find(".tile-nav").find(".bullet a").live("mouseenter",function(){
                var idx=slider.nav.find(".bullet").index($(this).parent());
                var npos=$(this).parent().offset();
                nleft=npos.left-(slider.navth.width()/2);
                ntop=npos.top-(slider.navth.height());
                mpos=slider.e.offset();
                slider.navth.stop(true,true).css({
                    left:nleft-mpos.left,
                    top:ntop-mpos.top-15
                });
                slider.navin.stop(true,true).animate({
                    
                    marginLeft:"-"+idx*140+"px"
                },500);
                slider.navth.css({
                    visibility:'visible'
                });
            });
            //Hide preview on mouseleave
            slider.e.find(".tile-nav").live("mouseleave",function(){
                slider.navth.css({
                    visibility:'hidden'
                });
           
            })
            //Left Click event
            $(".arrow.left",slider.e).live("click",function(){
                clearInterval(auto);
                tilePrev(slider);
            });
            //Left Click event
            $(".arrow.right",slider.e).live("click",function(){
                clearInterval(auto);
                tileNext(slider);
            });
            /*Events setup End*/
            return true;
        //tileAutoStart(slider.e,options);
        }
        $(window).resize(function() {
            slider.e.css('width','auto');
            scaleTiles(slider)
        });
        /* Matches original width with current width and scales tiles based on that */
        var scaleTiles=function(slider){
            if(slider.sliderWidth!=slider.e.width()){
                nwidth=Math.floor(slider.tileWidth/(slider.sliderWidth/slider.e.width()));
                swidth=Math.floor(slider.sliderWidth/(slider.tileWidth/nwidth));
                slider.e.width(swidth);
                slider.e.find("div.tile").each(function(){
                    $(this).width(nwidth);
                    //set background position of underlying a tag
                    var elem=$(this).find('.tile');
                    var pat_i_j=/tile tile\-(\d*)\-(\d*)/;
                    var pat_i=/htile tile tile\-(\d*)/;
                    var pat_j=/vtile tile tile\-(\d*)/;
                    if(pat_i.test(elem.attr("class"))){
                        $ind=elem.attr("class").match(pat_i);
                        elem.css({
                            'background-position':'0px '+(-1*$ind[1]*(slider.tileHeight))+'px'
                            });
                    }
                    if(pat_i_j.test(elem.attr("class"))){
                        $ind=elem.attr("class").match(pat_i_j);
                        elem.css({
                            'background-position':(-1*$ind[2]*nwidth)+'px '+(-1*$ind[1]*slider.tileHeight)+'px'
                            });
                    }
                    if(pat_j.test(elem.attr("class"))){
                        $ind=elem.attr("class").match(pat_j);
                        elem.css({
                            'background-position':(-1*$ind[1]*(nwidth))+'px 0px'
                            });

                    }
                })
            }
        }
        /**
         * Creates HTML for individual slides based on animation requirements
         * @param {Object} slide object with rows, cols and image property
         * @param {tileData} slider Tile Slider's Settings 
         */
        var tileMakeSlide=function(slide,slider){
            var elem = $([]),tmp;
            if(slider.sliderWidth!=slider.e.width()){
                width=Math.floor(slider.tileWidth/(slider.sliderWidth/slider.e.width()));
            }else{
                width=slider.tileWidth;
            }
            if(slider.current.anim!="slice"){
                for(var i=0;i<slider.cols;i++)
                {
                    for(var j=0;j<slider.rows;j++)
                    {
                        if(slider.current.anim!="hslice"){
                            tmp = '<div class="tile" style="width:'+width+'px;height:'+slider.tileHeight+'px;"><a class="tile tile-'+i+'-'+j+'" '+(slider.links[slide.image]!=''?'href="'+slider.links[slide.image]+'" target="_blank"':'')+' style="background:#555 url('+slide.image+') no-repeat '+(-j*width)+'px '+(-i*slider.tileHeight)+'px ;"></a></div>';
                            elem = elem.add(tmp);
                        }
                    }
                    if(slider.current.anim=="hslice"){
                        tmp='<div class="tile" style="width:'+slider.sliderWidth+'px;height:'+slider.tileHeight+'px;"><a '+(slider.links[slide.image]!=''?'href="'+slider.links[slide.image]+'" target="_blank"':'')+' class="htile tile tile-'+i+'" style="background:#555 url('+slide.image+') no-repeat 0px '+(-i*(slider.tileHeight))+'px ; height:'+slider.tileHeight+'px;"></a></div>';
                        elem=elem.add(tmp);
                    }
                    elem = elem.add('<div class="clear"></div>');
                }
            }else{
                for(j=0;j<slider.rows;j++){
                    tmp = '<div class="tile" style="width:'+width+'px;height:'+slider.sliderHeight+'px;"><a '+(slider.links[slide.image]!=''?'href="'+slider.links[slide.image]+'" target="_blank"':'')+' class="vtile tile tile-'+j+'" style="background:#555 url('+slide.image+') no-repeat '+(-j*(width))+'px 0px ; ;height:'+slider.sliderHeight+'px;"></a></div>';
                    elem = elem.add(tmp);
                }
                elem = elem.add('<div class="clear"></div>');
            }
            return elem;
            $(".tile").css({
                width:width,
                height:slider.tileHeight
            });
        }
        /**
         * Method to create slide dynamically and set next animation style
         * @param {number} id Slider Array ID of slide to be displayed
         * @param {tileData} slider Tile Slider's Settings 
         */
        var tileTransition=function(id,slider){
            if(!slider.images[id-1]) return false;
            if(slider.current.id)
            {
                if(slider.current.id == id) return false;
                slider.current.layer.removeClass("new-strip-slide");
                slider.current.layer.css('z-index',40);
                $('.tile-slide',slider.e).not(slider.current.layer).remove();
            }
            slider.e.find(".displayed").fadeOut('slow',function(){
                $(this).removeClass('displayed').html('');
            });
            slider.nav.find(".active").removeClass("active");
            slider.nav.find(".bullet").eq(id-1).addClass("active");
            /*Check and set next animation and generate tiles for current image*/
            if(slider.animation=="random"){
                slider.current.anim=Math.floor(Math.random() * (5 - 1+ 1)) + 1;
            }else{
                slider.current.anim=slider.animation;
            }
            //slider.current.anim="slice"
            switch(slider.current.anim){
                case 1:
                    slider.current.anim="tprog";
                    break;
                case 2:
                    slider.current.anim="tdia";
                    break;
                case 3:
                    slider.current.anim="slice";
                    break;
                case 4:
                    slider.current.anim="skin";
                    break;
                case 4:
                    slider.current.anim="hslice";
                    break;
                
            }
            var newLayer={};
            //slider.current.anim="slice";
            newLayer = $('<div class="tile-slide">').html(tileMakeSlide({
                image:slider.images[id-1]
            },slider));
            scaleTiles(slider);
            if(slider.current.anim=="tdia"||slider.current.anim=="tprog"){
                newLayer.addClass("new-slide");
            }
            if(slider.current.anim=="slice"){
                newLayer.addClass("new-slice-slide");
            }
            newLayer.css('z-index',75);
            if(slider.current.anim=="skin"){
                newLayer.css('z-index',15);
            }
            slider.e.append(newLayer);
            if(id==1&&slider.e.find(".tile-slide").length==1){
                newLayer.removeClass("new-slide");
                newLayer.removeClass("new-slice-slide");
            }
            tileAnimate(slider, newLayer);
            slider.current.id = id;
            slider.current.layer = newLayer;
            slider.nav.find(".active").removeClass("active");
            slider.nav.find(".bullet").eq(id-1).addClass("active");
            clearInterval(auto);
            auto=setInterval(function(){
                tileNext(slider);
            },slider.interval);
            tileTitleShow(slider);
            return true;
        }
        /**
         * Loads Next Slide
         * @param {Object} elem Element Object to add HTML Elements to
         * @param {tileData} slider Tile Slider's Settings 
         */
        var tileNext=function(slider){
            if(slider.current.id&&slider.current.stat)
            {
                tileTransition(slider.current.id%slider.images.length+1,slider);
            }	
        }
        /**
         * Loads Prev Slide
         * @param {Object} elem Element Object to add HTML Elements to
         * @param {tileData} slider Tile Slider's Settings 
         */
        var tilePrev=function(slider){
            if(slider.current.id&&slider.current.stat)
            {
                tileTransition((slider.current.id+(slider.images.length-2))%slider.images.length+1,slider);
            }
            
        }
        /* Method to Display Titles
         * @param {tileData} slider Tile Slider's Settings 
         *
         */
        var tileTitleShow=function(slider){
            var curr=slider.images[slider.current.id-1];
            if(slider.captions[curr]!== undefined){
                slider.e.find(".caption").addClass('displayed').css('display','none').html(slider.captions[curr]);
                slider.e.find(".caption").fadeIn('slow', function(){
                });
            }
        }
        
        
        
        /**
         *var get easing
         *returns easing to be used in a function
         */
        var tileGetEasing=function(easing){
            var jq_easing=['swing','easeOutQuad','easeOutCubic','easeOutQuart','easeOutQuint','easeOutSine','easeOutExpo','easeOutCirc'];
            if(easing=="random"||jq_easing.hasOwnProperty(easing)){
                return jq_easing[Math.floor(Math.random() * jq_easing.length)];
            }else{
                return easing;
            }
        }
        /**
         * Method to execute animation
         * 
         * @param {tileData} slider Tile Slider's Settings 
         * @param {HTMLObject} newLayer Newly Added Layer
         */
        var tileAnimate=function(slider,newLayer){
            if(slider.current.layer)
            {
                var direction=null;
                var diff=slider.diff;
                var c_easing="";
                if(slider.enable_easing&&slider.easing){
                    c_easing=tileGetEasing(slider.easing);
                }else{
                    c_easing=false;
                }
                if(slider.current.stat){
                    if(slider.current.anim=="skin"){
                        if(slider.current.layer.hasClass("tile-slice"))
                        {
                            slider.current.layer.html(tileMakeSlide({
                                image:slider.images[slider.current.id-1]
                            },slider));
                           
                        }
                    }
                    slider.current.stat=false;
                    if(slider.current.anim=="slice"||slider.current.anim=="hslice"){
                        /*
                         *Animation Logic for hslice Transition
                         */
                        if(slider.current.anim=="hslice"){
                            direction=Math.floor(Math.random() * (6 - 1+ 1)) + 1;
                            //direction=1;
                            curr=0;
                            for(var j=0;j<(slider.cols);j++)
                            {
                                var duration=0;
                                switch(direction){
                                    case 1:
                                        duration=2*j*diff*slider.cols;//TopLeft
                                        break;
                                    case 2:
                                        duration=2*(slider.cols-j)*diff*slider.cols;//TopRight
                                        break;
                                    case 3:
                                        duration=2*j*diff*slider.cols;//BottomLeft
                                        break;
                                    case 4:
                                        duration=2*(slider.cols-j)*diff*slider.cols;//BottomRight
                                        break;
                                    case 5:
                                        duration=2*j*diff*slider.cols;//ZigZag- Similar to TopLeft
                                        break;
                                    case 6:
                                        duration=2*(slider.cols-j)*diff*slider.cols;//TopRight-ZigZag
                                        break;
                                
                                }
                                obj=newLayer.find(".tile-"+j);
                                num=newLayer.children().not(".clear").length;
                                if(direction==1||direction==3){
                                    obj.css('left',slider.sliderWidth+"px");
                                }
                                if(direction==2||direction==4){
                                    obj.css('left','-'+slider.sliderWidth+"px");
                                }
                                if(direction==5||direction==6){
                                    //zigzag
                                    if(j%2==0){
                                        obj.css('left',slider.sliderWidth+"px");
                                    }else{
                                        obj.css('left','-'+slider.sliderWidth+"px");
                                    }
                                }
                                obj.css('opacity',0);
                                css_obj={
                                    left:'0px',
                                    opacity:1
                                };
                                var tileSliceCallback=function(){
                                    curr++;
                                    if(curr==num){
                                        slider.current.stat=true;
                                        curr=0;
                                        newLayer.removeClass("new-slice-slide");
                                        newLayer.addClass("tile-slice");
                                        tileTitleShow(slider);
                                    }
                                }
                                if(c_easing){
                                    obj.delay(duration/2).animate(css_obj,slider.tileDuration,c_easing,tileSliceCallback);
                                }else{
                                    obj.delay(duration/2).animate(css_obj,slider.tileDuration,tileSliceCallback);
                                }

                            }
                        }else{
                            /*
                             *Animation Logic for Strip Transition
                             */
                            direction=Math.floor(Math.random() * (6 - 1+ 1)) + 1;
                            //direction=2;
                            curr=0;
                            for(var j=0;j<(slider.rows*2);j++)
                            {
                                var duration=0;
                                switch(direction){
                                    case 1:
                                        duration=j*diff*slider.cols;//TopLeft
                                        break;
                                    case 2:
                                        duration=(slider.rows-j)*diff*slider.cols;//TopRight
                                        break;
                                    case 3:
                                        duration=j*diff*slider.cols;//BottomLeft
                                        break;
                                    case 4:
                                        duration=(slider.rows-j)*diff*slider.cols;//BottomRight
                                        break;
                                    case 5:
                                        duration=j*diff*slider.cols;//ZigZag- Similar to TopLeft
                                        break;
                                    case 6:
                                        duration=(slider.rows-j)*diff*slider.cols;//TopRight-ZigZag
                                        break;
                                
                                }
                                obj=newLayer.find(".tile-"+j);
                                num=newLayer.children().not(".clear").length;
                                if(direction==1||direction==3){
                                    obj.css('top','-'+slider.sliderHeight+"px");
                                }
                                if(direction==2||direction==4){
                                    obj.css('top',slider.sliderHeight+"px");
                                }
                                if(direction==5||direction==6){
                                    //zigzag
                                    if(j%2==0){
                                        obj.css('top',slider.sliderHeight+"px");
                                    }else{
                                        obj.css('top','-'+slider.sliderHeight+"px");
                                    }
                                }
                                obj.css('opacity',0);
                                css_obj={
                                    top:'0px',
                                    opacity:1
                                };
                                var tileSliceCallback=function(){
                                    curr++;
                                    if(curr==num){
                                        slider.current.stat=true;
                                        curr=0;
                                        newLayer.removeClass("new-slice-slide");
                                        newLayer.addClass("tile-slice");
                                        tileTitleShow(slider);
                                    }
                                }
                                if(c_easing){
                                    obj.delay(duration/2).animate(css_obj,slider.tileDuration,c_easing,tileSliceCallback);
                                }else{
                                    obj.delay(duration/2).animate(css_obj,slider.tileDuration,tileSliceCallback);
                                }
                            }
                        }
                    }else{
                        //Direction is either - tprog, skin, tdia
                        if(slider.current.anim=="tprog"){
                            direction=Math.floor(Math.random() * (8 - 5+ 1)) + 5;
                        }else{
                            direction=Math.floor(Math.random() * (4 - 1+ 1)) + 1;
                        }
                        curr=0;
                        for(var i=0;i<slider.cols;i++)
                        {
                            for(var j=0;j<slider.rows;j++)
                            {
                                diff=(slider.diff*slider.rows);
                                duration=0;
                                switch(direction){
                                    case 1:
                                        duration=i*diff+j*diff;//TopLeft - diagonal
                                        break;
                                    case 2:
                                        duration=i*diff+(9-j)*diff;//TopRight -diagonal
                                        break;
                                    case 3:
                                        duration=(4-i)*diff+j*diff;//BottomLeft -diagonal
                                        break;
                                    case 4:
                                        duration=(4-i)*diff+(9-j)*diff;//BottomRight -diagonal
                                        break;
                                    case 5:
                                        duration=(4-i)*diff*2+(j*diff)/2;//Bottom Left - progressive
                                        break;
                                    case 6:
                                        duration=(i)*diff*2+(j*diff/2);//topleft - progressive
                                        break;
                                    case 7:
                                        duration=(i)*diff*2+((9-j)*diff/2);//topright -progressive
                                        break;
                                    case 8:
                                        duration=(4-i)*diff*2+((9-j)*diff)/2;// rightbottom -progressive
                                        break;
                                }
                                duration=duration/2;
                                if(slider.current.anim=="skin"){
                                    obj=slider.current.layer.find(".tile-"+i+"-"+j);
                                    num=slider.current.layer.children().not(".clear").length;
                                    css_obj={
                                        width:slider.tileWidth*2+"px", 
                                        height:slider.tileHeight*2+"px",
                                        marginLeft:'-'+slider.tileWidth/2+"px",
                                        marginRight:'-'+slider.tileHeight/2+"px"
                                    };
                                    var tileSkinCallback=function(){
                                        curr++;
                                        if(curr==num){
                                            slider.current.stat=true;
                                            tileTitleShow(slider);
                                            curr=0;
                                        }
                                    }
                                    if(c_easing){
                                        obj.delay(duration).animate(css_obj,slider.tileDuration,c_easing,tileSkinCallback);
                                    }else{
                                        obj.delay(duration).animate(css_obj,slider.tileDuration,tileSkinCallback);
                                    }
                                    obj.parent().delay(duration).fadeOut(slider.tileDuration,function(){
                                        $(this).css({
                                            visibility:'hidden',
                                            display:'block'
                                        });
                                    });
                                }else{
                                    obj=newLayer.find(".tile-"+i+"-"+j);
                                    num=newLayer.children().not(".clear").length;
                                    css_obj={
                                        width:slider.tileWidth, 
                                        height:slider.tileHeight, 
                                        marginLeft:'0px',
                                        marginTop:'0px'
                                    };
                                    var tileDiaCallback=function(){
                                        curr++;
                                        if(curr==num){
                                            slider.current.stat=true;
                                            tileTitleShow(slider);
                                            curr=0;
                                        }
                                    }
                                    obj.delay(duration).animate(css_obj,slider.tileDuration/4,tileDiaCallback);

                                    obj.parent().css({
                                        visibility:'visible',
                                        opacity:0
                                    });
                                    obj.parent().delay(duration).animate({
                                        opacity:1
                                    },slider.tileDuration,function(){
                                        $(this).css({
                                            display:'block'
                                        });
                                    });
                                }
                            
                         
                            }
                        }
                    }
                }
            }
        }
        jQuery.fx.interval = 50;
        var curr,obj,num;
        var slider= $.extend({}, $.fn.tileSlider.defaults, options);
        slider.e=$(elem);
        /* Check and filter slider data for display*/
        slider=tileDimentions(slider);
        slider.current={
            stat:true
        };
        if(jQuery.easing["jswing"]){
            slider.enable_easing=1;
        }
        slider.count=slider.e.find("img").length;//total slides
        slider.images=Array();//Images Array
        slider.links=Array();
        slider.captions=Array();
        slider.e.find("img").each(function(i){
            slider.images.push($(this).attr("src"));
            if($(this).attr("title")){
                if($($(this).attr("title")).length){
                    slider.captions[$(this).attr("src")]=$($(this).attr("title")).html();
                }else{
                    slider.captions[$(this).attr("src")]='<h2>'+$(this).attr("title")+'</h2>';
                }
            }
            if($(this).parent().is('a')){
                slider.links[$(this).attr("src")]=$(this).parent().attr("href");
            }else{
                slider.links[$(this).attr("src")]='';
            }
        })
        /*Preload all images of slider*/
        for(var i=0;i<slider.images.length;i++)
        {
            (new Image()).src=slider.images[i];
        }
        var auto;
        tileSetup(slider,options);
        tileTransition(1,slider);
    }
    /*
         * Main loader of tile slider
         */
    $.fn.tileSlider = function(options) {
        /* Loop through the HTML elements*/
        return this.each(function(key, value){
            var element = $(this);
            // Return early if this element already has a plugin instance
            if (element.data('tileSlider')) return element.data('tileSlider');
            // Pass options to plugin constructor
            var tileslider = new tileSlider(this, options);
            // Store plugin object in this element's data
            element.data('tileSlider', tileslider);
        });
    };
    /*
	 *Default settings on tileSlider
         */
    $.fn.tileSlider.defaults={
        'tileWidth':80,             //Height of a Tile in pixels
        'tileHeight':80,            //Height of a Tile in pixels
        'tileDuration':1000,        //Individual tile animation duration
        'tansitionSpeed':2000,      //Duration of transitions
        'direction':'random',       //topLeft, topRight, bottomLeft, bottomRight ,zigZag(only for slice animation)
        'animation':'random',       //tileProgressive, tileDiagonal, strip, skin random
        'sliderWidth':970,          //Width of the slider
        'sliderHeight':400,         //height of the slider
        'thumbHeight':125,          //Height of the thumbnail
        'thumbWidth':125,           //Width of the thumbnail
        'autoPlay':true,            //Autoplay
        'interval':5000,               //delay between autoplay
        'easing':'random'           //Only applied if easing plugin is loaded
    };
})(jQuery);
