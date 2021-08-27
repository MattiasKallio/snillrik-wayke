<?php
if (!defined('ABSPATH')) {
    exit;
}

new SNWK_DesignElements();

class SNWK_DesignElements
{

    public static function search_box($itemurl, $facets){

        $fields_list = [];
        $fields_list_translated = [
            "Manufacturer"=>"Tillverkare",
            "ModelSeries"=>"Modell",
            "FuelType"=>"Drivmedel",
            "GearboxType"=>"Växellåda",
            "Branch"=>"Företag",
            "Color"=>"Färg",
            "EnvironmentClass"=>"Miljöklass",
            "Segment"=>"Segment",
            "DrivingWheel"=>"Drivning",
            "Price"=>"Pris",
            "Mileage"=>"Mil",
            "Model Year"=>"År"
        ];
        $html_out = "";

        foreach($facets as $facet){
            $temp_fac = "";
            $is_selected_class = "";
            $name_out = isset($fields_list_translated[$facet->displayName]) ? $fields_list_translated[$facet->displayName] : $facet->displayName;
            foreach($facet->filters as $filter){
                $selected = $filter->selected ? "selected" : "";
                $name_out_filter = isset($fields_list_translated[$filter->displayName]) ? $fields_list_translated[$filter->displayName] : $filter->displayName;
                $temp_fac .= "<option $selected>".$name_out_filter."</option>"; 
                if($filter->selected)
                    $is_selected_class = "is_selected";
            }
            $html_out .= "<div class='snillrik-search-boxwrap $is_selected_class'>
                <select id='".$facet->id."' name='".$facet->id."'>
                    <option value=''>".$name_out."</option>
                    ".$temp_fac."
                </select></div>";
        }

        return "<form action='".$itemurl."' method='get' id='snillrik-search'>
        <div class='snillrik-search-main'>
        $html_out
        <input type='Submit' id='snillrik-search-submit' value='Sök' />
        <input type='Button' id='snillrik-search-reset' value='Återställ' />
        </div>
        </form>";

    }

    public static function standard_box($item = false)
    {
        if ($item) {
            //extract($content_array, EXTR_PREFIX_SAME, "wddx");
            //$item->title." ".$item->shortDescription;
            //print_r($item);
            //$image = $item->featuredImage->files[0]->url;
            //$image = $item->featuredImage->files[0]->formats[2]->url;
            $image = SNWK_DesignElements::get_imagesize_from_files($item->featuredImage->files[0],"770");
            $img_str = "<img src='".$image."' />";
            $ret_str = "<div class='snillrik-image'>$img_str</div>";
            $ret_str .= "<div class='snillrik-over-info'>".$item->branches[0]->name."</div>";
            $ret_str .= "<div class='snillrik-info'>
            <div class='snillrik-info-inner'><strong>" . $item->title . "</strong> ".$item->shortDescription . "</div>".
                "<div class='snillrik-info-details'>
                    <div class='snillrik-inforow'><div>Årsmodell</div><div>".$item->modelYear . "</div></div>".
                    "<div class='snillrik-inforow'><div>Mil</div><div>".$item->mileage . " mil</div></div>".
                    "<div class='snillrik-inforow'><div>Växellåda</div><div>".$item->gearboxType . "</div></div></div>".
            "</div>";
            $ret_str .= "<div class='snillrik-under-info'><span class='snillrik-price'>" .SNWK_DesignElements::format_price($item->price) . " kr</span>Pris:</div>";

            $itempage = get_option('snillrik_wayke_itempage', "");
            if(is_numeric($itempage)){
                $itemurl = get_permalink($itempage);
                return "<div class='snillrik-standardbox-item' data-bilurl='".$itemurl."?bilid=".$item->_id."'>
                    <div class='snillrik-standardbox-item-inner'>$ret_str</div>
                </div>";
            }
        }
        return "no item..."; 
    }

    public static function main_box($item, $rightcol = true){
        $manufacturer = $item->manufacturer;
        $modelName = $item->modelName;
        $description = $item->shortDescription;

        $str_out = "<div class='snillrik-item-head'><h1>$manufacturer - $description</h1></div>";
        //print_r($item);
        //Utrustning
        $options_str = "";
        $options = $item->options;
        foreach($options as $option){
            if($option!="")
                $options_str .= "<div class='snillrik-item-optionbox'><div class='snillrik-item-optionbox-inner'>".$option."</div></div>";
        }

        $str_out .= "<h2>Utrustning</h2><div class='snillrik-item-options'>$options_str</div>";

        //Biluppgifter
        $uppgifter_str = "";

        $length = isset($item->properties->length) ? $item->properties->length : "l";
        $width = isset($item->properties->width) ? $item->properties->width : "b";
        $height = isset($item->properties->height) ? $item->properties->height : "h";

        $info_array = [
            "Märke"=>$manufacturer,
            "Modell"=>$modelName,
            "Växellåda"=>$item->gearbox,
            "Miltal"=>$item->mileage == 0 ? "0 mil" : $item->mileage." mil",
            "Drivmedel"=>$item->fuelType,
            "Årsmodell"=>$item->manufactureYear,
            "Reg.nummer"=>$item->registrationNumber,
            "Skattevikt"=>$item->properties->trailerTotalWeightB,
            "Längd/bredd/höjd"=>$length."/".$width."/".$height
        ];

        foreach($info_array as $name=>$infoitem){
            $uppgifter_str .= "<div class='snillrik-item-infobox'><div class='snillrik-item-infobox-inner'><h4>$name</h4>$infoitem</div></div>";
        }

        $str_out .= "<h2>Biluppgifter</h2><div class='snillrik-item-infos'>$uppgifter_str</div>";

        
        $swipe_str = SNWK_DesignElements::wayke_swipe($item->media, []);

        $right_str = "";
    
        if($rightcol=="true" || $rightcol===true){
            $right_str = "<div class='snillrik-item-right'>
                <div class='snillrik-box snillrik-box-flex snillrik-pricebox'><div class='snillrik-box-left'>Pris</div><div class='snillrik-box-right'>".SNWK_DesignElements::format_price($item->price) ." kr</div></div>
                <div class='snillrik-box snillrik-adressbox'>
                    <h4>".$item->branches[0]->name ."</h4>
                    ".$item->position->street ."<br />
                    ".$item->position->zip ." ".$item->position->city ."<br />
                </div>
                <div class='snillrik-box snillrik-mapbox'>
                    <iframe width='100%' src = 'https://maps.google.com/maps?q=".$item->position->location->lat.",".$item->position->location->lon."&hl=es;z=14&amp;output=embed'></iframe>
                </div>                
            </div>";

            return "<div class='snillrik-item-main'>
            <div class='snillrik-item-left'> $swipe_str $str_out </div>
                $right_str
            </div>";
        }
        else{
            return "<div class='snillrik-item-main'>
                <div class='snillrik-item-full'> $swipe_str $str_out </div>
            </div>";
        }



    }

    public static function format_price($price){
        return number_format($price, 0, ',', ' ');
    }

    public static function get_imagesize_from_files($files,$size="800"){
        foreach($files->formats as $format){
            if(strpos($format->format,$size)>-1)
                return $format->url;
        }
        return $files->url;
    }

    public static function wayke_swipe($images, $atts)
    {
        $attributes = shortcode_atts(array(
            'type' => 'normal',
            'slidesperview' => 1,
            'effect' => 'coverflow', //slide,cube, flip, fade, coverflow
        ), $atts);

        $js_params = array(
            "slidesperview" => wp_is_mobile() ? 1 : $attributes["slidesperview"],
            "effect" => $attributes["effect"],
        );

        //print_r($atts);
        wp_enqueue_script('snillrik-slider-main-script');
        wp_localize_script('snillrik-slider-main-script', 'swipeparams', $js_params);
        
        //$flashifyitem_metanum[] = array();
        $counter = 1;
        $type_num = 1;
        $swiper_out = '';

        foreach ($images as $image) {
            //die(print_r($image->files[0]->url));
            //$tumme = esc_url($image->files[0]->url);
            $tumme  = SNWK_DesignElements::get_imagesize_from_files($image->files[0],"770");
            
            //$content = preg_replace("/\[caption.*\[\/caption\]/", '', $image->post_content);
 
            $swiper_out .= "
            <div class='swiper-slide'>
                <div class='snillrik_sliderbox'>
                    <div class='snillrik_sliderbox_inner' style='background-image:url($tumme)'>
                    </div>
                </div>
            </div>";
        }

        //return "<div class='swiper-container'>$swiper_out</div>";

        return '<div class="swiper-container">
        <div class="swiper-scrollbar"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-wrapper">
    ' . $swiper_out . '
    </div>
    <div class="swiper-pagination"></div>
    </div>';
    }
}
