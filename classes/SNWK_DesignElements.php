<?php
if (!defined('ABSPATH')) {
    exit;
}

new SNWK_DesignElements();

class SNWK_DesignElements
{

    public static function search_box($itemurl, $facets)
    {

        $fields_list = [ //which facets to show.
            "manufacturer",
            "modelSeries",
            "fuelType",
            "gearboxType",
            //"branch",
            "color",
            "environmentClass",
            //"properties.segment",
            "segment",
            "drivingWheel",
            "price",
            "mileage",
            "modelYear",
        ];
        $fields_list_translated = [
            "Manufacturer" => "Varumärke",
            "ModelSeries" => "Modell",
            "FuelType" => "Drivmedel",
            "GearboxType" => "Växellåda",
            "Branch" => "Företag",
            "Color" => "Färg",
            "EnvironmentClass" => "Miljöklass",
            "Segment" => "Segment",
            "DrivingWheel" => "Drivning",
            "Price" => "Pris",
            "Mileage" => "Miltal",
            "Model Year" => "År",
        ];

        $rangenumbers = [
            "price"=>["min"=>0,"max"=>500000,"step"=>1000],
            "mileage"=>["min"=>0,"max"=>10000,"step"=>10]
        ];
        $html_out = "";
        $html_out2 = "";

        foreach ($facets as $facet) {
            $temp_fac = "";
            $is_selected_class = "";

            if (in_array($facet->id, $fields_list)) {
                $name_out = isset($fields_list_translated[$facet->displayName]) ? $fields_list_translated[$facet->displayName] : $facet->displayName;
                $prev_value = "";
                if ($facet->id == "price" || $facet->id == "mileage") {

                    //print_r($facet);
                   
                    $args = isset($_GET) ? $_GET : array();
                    $args = array_filter($args, function($val) {
                        return esc_attr($val);
                    });

                    $min_value = isset($args[$facet->id."_min"]) ? $args[$facet->id."_min"] : $rangenumbers[$facet->id]["min"];
                    $max_value = isset($args[$facet->id."_max"]) ? preg_replace('/\D/', '',$args[$facet->id."_max"]) : $rangenumbers[$facet->id]["max"];
                    $max_value_str = isset($args[$facet->id."_max"]) ? $args[$facet->id."_max"] : $rangenumbers[$facet->id]["max"]."+";
                    $step_value = $rangenumbers[$facet->id]["step"];
                    $facet_id_min = $facet->id."_min";
                    $facet_id_max = $facet->id."_max";

                    $temp_fac .= "<div class='snillrik-waykesearch-rangewrap-min'>
                    <h4>$name_out min</h4>
                        <input id='$facet_id_min' name='$facet_id_min' value='$min_value'><br />
                        <input type='range' min='".$rangenumbers[$facet->id]["min"]."' max='".($rangenumbers[$facet->id]["max"]-1)."' step=$step_value value='$min_value' /></div>";
                    $temp_fac .= "<div class='snillrik-waykesearch-rangewrap-max'>
                    <h4>$name_out max</h4>
                        <input id='$facet_id_max' name='$facet_id_max' value='$max_value_str'><br />
                        <input type='range' min='".$rangenumbers[$facet->id]["min"]."' max='".$rangenumbers[$facet->id]["max"]."' step=$step_value value='".$max_value."' /></div><br />";
                    
                    $html_out2 .= "<div class='snillrik-waykesearch-rangewrap $is_selected_class'>$temp_fac</div>";
                } else {
                    foreach ($facet->filters as $filter) {
                        $selected = $filter->selected ? "selected" : "";
                        $name_out_filter = isset($fields_list_translated[$filter->displayName]) ? $fields_list_translated[$filter->displayName] : $filter->displayName;
                        $name_out_filter_str = is_numeric($name_out_filter) ? round($name_out_filter) : $name_out_filter;
                        $prev_value_str = is_numeric($prev_value) ? round($prev_value) . " - " : "";
                        $temp_fac .= "<option $selected value='$name_out_filter'>" . $prev_value_str . " " . $name_out_filter_str . "</option>";

                        //$temp_fac .= "<option $selected>". $name_out_filter . "</option>";
                        if ($filter->selected) {
                            $is_selected_class = "is_selected";
                        }
                        $prev_value = $name_out_filter_str;
                    }
                    $html_out .= "<div class='snillrik-waykesearch-boxwrap $is_selected_class'>
                <select id='" . $facet->id . "' name='" . $facet->id . "'>
                    <option value=''>" . $name_out . "</option>
                    " . $temp_fac . "
                </select></div>";
                }
            }
        }

        return "<form action='" . $itemurl . "' method='get' id='snillrik-waykesearch'>
        <div class='snillrik-waykesearch-main snillrik-waykesearch-main-full'>
            <div class='snillrik-waykesearch-boxwrap'> <input type='text' id='q' name='q' placeholder='Fritext' /></div>
        </div>
        <div class='snillrik-waykesearch-main'>
        $html_out
        $html_out2
        <input type='Submit' id='snillrik-waykesearch-submit' value='Sök' />
        <input type='Button' id='snillrik-waykesearch-reset' value='Återställ' />
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
            $image = SNWK_DesignElements::get_imagesize_from_files($item->featuredImage->files[0], "770");
            $img_str = "<img src='" . $image . "' />";
            $ret_str = "<div class='snillrik-waykeimage'>$img_str</div>";
            $ret_str .= "<div class='snillrik-waykeover-info'>" . $item->branches[0]->name . "</div>";
            $ret_str .= "<div class='snillrik-waykeinfo'>
            <div class='snillrik-waykeinfo-inner'><strong>" . $item->title . "</strong> " . $item->shortDescription . "</div>" .
            "<div class='snillrik-waykeinfo-details'>
                    <div class='snillrik-waykeinforow'><div>Årsmodell</div><div>" . $item->modelYear . "</div></div>" .
            "<div class='snillrik-waykeinforow'><div>Mil</div><div>" . $item->mileage . " mil</div></div>" .
            "<div class='snillrik-waykeinforow'><div>Växellåda</div><div>" . $item->gearboxType . "</div></div></div>" .
                "</div>";
            $ret_str .= "<div class='snillrik-waykeunder-info'><span class='snillrik-waykeprice'>" . SNWK_DesignElements::format_price($item->price) . " kr</span>Pris</div>";

            $itempage = get_option('snillrik_wayke_itempage', "");
            if (is_numeric($itempage)) {
                $itemurl = get_permalink($itempage);
                return "<div class='snillrik-waykestandardbox-item' data-bilurl='" . $itemurl . "?bilid=" . $item->_id . "'>
                    <div class='snillrik-waykestandardbox-item-inner'>$ret_str</div>
                </div>";
            }
        }
        return "no item...";
    }

    public static function main_box($item, $rightcol = true)
    {
        $manufacturer = $item->manufacturer;
        $modelName = $item->modelSeries;
        $description = $item->shortDescription;

        $str_out = "<div class='snillrik-waykeitem-head'><h1><strong>$manufacturer $modelName</strong> - $description</h1></div>";
        //print_r($item);
        //Utrustning
        $options_str = "";
        $options = $item->options;

        foreach ($options as $key=>$option) {
            if ($option != "") {
                $options_str .= "<div class='snillrik-waykeitem-optionbox'>
                <div class='snillrik-waykeitem-optionbox-inner'>" . $option . "</div></div>";
            }

        }

        $str_out .= "<h2>Utrustning</h2><div class='snillrik-waykeitem-options'>$options_str</div>";

        //Biluppgifter
        $uppgifter_str = "";

        $length = isset($item->properties->length) ? $item->properties->length : "l";
        $width = isset($item->properties->width) ? $item->properties->width : "b";
        $height = isset($item->properties->height) ? $item->properties->height : "h";

        $info_array = [
            "Märke" => $manufacturer,
            "Modell" => $modelName,
            "Växellåda" => $item->gearbox,
            "Miltal" => $item->mileage == 0 ? "0 mil" : $item->mileage . " mil",
            "Drivmedel" => $item->fuelType,
            "Årsmodell" => $item->manufactureYear,
            "Reg.nummer" => $item->registrationNumber,
            "Skattevikt" => $item->properties->trailerTotalWeightB,
            "Längd/bredd/höjd" => $length . "/" . $width . "/" . $height,
        ];

        foreach ($info_array as $name => $infoitem) {
            $uppgifter_str .= "<div class='snillrik-waykeitem-infobox'><div class='snillrik-waykeitem-infobox-inner'><h4>$name</h4>$infoitem</div></div>";
        }

        $str_out .= "<h2>Biluppgifter</h2><div class='snillrik-waykeitem-infos'>$uppgifter_str</div>";

        $swipe_str = SNWK_DesignElements::wayke_swipe($item->media, []);

        $right_str = "";

        if ($rightcol == "true" || $rightcol === true) {
            $right_str = "<div class='snillrik-waykeitem-right'>
                <div class='snillrik-waykebox snillrik-waykebox-flex snillrik-waykepricebox'><div class='snillrik-waykebox-left'>Pris</div><div class='snillrik-waykebox-right'>" . SNWK_DesignElements::format_price($item->price) . " kr</div></div>
                <div class='snillrik-waykebox snillrik-waykeadressbox'>
                    <h4>" . $item->branches[0]->name . "</h4>
                    " . $item->position->street . "<br />
                    " . $item->position->zip . " " . $item->position->city . "<br />
                </div>
                <div class='snillrik-waykebox snillrik-waykemapbox'>
                    <iframe width='100%' src = 'https://maps.google.com/maps?q=" . $item->position->location->lat . "," . $item->position->location->lon . "&hl=es;z=14&amp;output=embed'></iframe>
                </div>
            </div>";

            return "<div class='snillrik-waykeitem-main'>
            <div class='snillrik-waykeitem-left'> $swipe_str $str_out </div>
                $right_str
            </div>";
        } else {
            return "<div class='snillrik-waykeitem-main'>
                <div class='snillrik-waykeitem-full'> $swipe_str $str_out </div>
            </div>";
        }

    }

    public static function format_price($price)
    {
        return number_format($price, 0, ',', ' ');
    }

    public static function get_imagesize_from_files($files, $size = "800")
    {
        foreach ($files->formats as $format) {
            if (strpos($format->format, $size) > -1) {
                return $format->url;
            }

        }
        return $files->url;
    }

    public static function wayke_swipe($images, $atts)
    {
        $attributes = shortcode_atts(array(
            'type' => 'normal',
            'slidesperview' => 1,
            'effect' => 'fade', //slide,cube, flip, fade, coverflow
        ), $atts);

        $js_params = array(
            "slidesperview" => wp_is_mobile() ? 1 : $attributes["slidesperview"],
            "effect" => $attributes["effect"],
        );

        //print_r($atts);
        wp_enqueue_script('snillrik-waykeslider-main-script');
        wp_localize_script('snillrik-waykeslider-main-script', 'swipeparams', $js_params);

        //$flashifyitem_metanum[] = array();
        $counter = 1;
        $type_num = 1;
        $swiper_out = '';

        foreach ($images as $image) {
            //die(print_r($image->files[0]->url));
            //$tumme = esc_url($image->files[0]->url);
            $tumme = SNWK_DesignElements::get_imagesize_from_files($image->files[0], "770");

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
