<?php
if (!defined('ABSPATH')) {
    exit;
}

new SNWK_shortcodes();

class SNWK_shortcodes
{

    public function __construct()
    {
        add_shortcode('wayke_list', array($this, 'wayke_list'));
        add_shortcode('wayke_item', array($this, 'wayke_item'));
        add_shortcode('wayke_price', array($this, 'wayke_price'));
    }

    public function wayke_price(){
        global $snillrik_price;
        $ret_str = "<div class='snillrik-under-info'><span class='snillrik-price'>" .SNWK_DesignElements::format_price($snillrik_price) . " kr</span>Pris:</div>";
        return $ret_str;
    }

    public function wayke_list($atts)
    { 
        global $wp;
        $thispage = home_url( $wp->request );
        $attributes = shortcode_atts(array(
            'hits' => 12,
        ), $atts);

        $str_out = "";
        $pageination_str = "";

        $api = new SNWK_API();
        $args = $_GET;
        $args["hits"] = $attributes['hits'];
        
        $call = $api->wayke_call("vehicles",$args);
        
        $str_out .= SNWK_DesignElements::search_box($thispage, $call->facets);

        if($call && isset($call->documentList->numberOfHits) && $call->documentList->numberOfHits>0){
            foreach($call->documentList->documents as $item){
                $str_out .= SNWK_DesignElements::standard_box($item);
            }
        }

        $pageination = $call->documentList->pagination->pages;

        foreach($pageination as $page){
            $page_str = "<a href='$thispage/?".$page->query."'>".$page->displayName."</a>";
            $pageination_str .= "<div class='pagination-number'>$page_str</div>";
        }

        return "<div class='snillrik-list-main'>".$str_out."<div class='snillrik-paginator'>$pageination_str</div></div>";
    }
    public function wayke_item($atts)
    { 
        $attributes = shortcode_atts(array(
            'rightcol' => true,
        ), $atts);
        
        $the_car_id = get_query_var("bilid", 0);
        $str_out = "";
        global $snillrik_price;
        $snillrik_price = 0;
        $api = new SNWK_API();
        //$call = $api->wayke_call("vehicle", ["id"=>"b88614ff-c004-41e8-8ff8-21fa0283318f"]);
        $call = $api->wayke_call("vehicle", ["id"=>$the_car_id]);
        
        $snillrik_price = $call->documentList->documents[0]->price;

       //$str_out = print_r($call,true);
       if($call && isset($call->documentList->numberOfHits) && $call->documentList->numberOfHits>0){
            $item = $call->documentList->documents[0];
            $str_out =  SNWK_DesignElements::main_box($item, $attributes['rightcol']);
        }

        return $str_out;
    }   
    
    

}

