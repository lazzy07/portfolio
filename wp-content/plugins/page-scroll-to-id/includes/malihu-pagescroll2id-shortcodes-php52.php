<?php
/*
Plugin shortcodes for PHP 5.2
*/

$pl_shortcodes[$i]=create_function('$atts,$content=null','
    extract(shortcode_atts(array( 
        "i" => "'.$i.'",
        "shortcode_class" => "_'.$shortcode_class.'",
        "url" => "",
        "offset" => "",
        "id" => "",
        "target" => "",
        "class" => "",
    ), $atts));
    if($id!==""){
        if($content){
            return "<div id=\"".$id."\" data-ps2id-target=\"".sanitize_text_field($target)."\">".do_shortcode($content)."</div>";
        }else{
            return "<a id=\"".$id."\" data-ps2id-target=\"".sanitize_text_field($target)."\"></a>";
        }
    }else{
        $element_classes=$class!=="" ? $shortcode_class." ".$class : $shortcode_class;
        return "<a href=\"".esc_url_raw($url)."\" class=\"".$element_classes."\" data-ps2id-offset=\'".sanitize_text_field($offset)."\'>".do_shortcode($content)."</a>";
    }
');
add_shortcode($tag, $pl_shortcodes[$i]);
$pl_shortcodes_b[$i]=create_function('$atts,$content=null','
    extract(shortcode_atts(array( 
        "i" => "'.$i.'",
        "id" => "",
        "target" => "",
    ), $atts));
    if($id!==""){
        return "<div id=\"".$id."\" data-ps2id-target=\"".sanitize_text_field($target)."\">".do_shortcode($content)."</div>";
    }
');
add_shortcode($tag_b, $pl_shortcodes_b[$i]);
?>