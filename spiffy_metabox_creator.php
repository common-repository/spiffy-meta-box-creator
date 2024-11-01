<?php
/*
Plugin Name: Spiffy Meta Box Creator
Plugin URI: http://www.blakerdesign.com
Description: Create meta boxes by creating and calling a function but without needing to reuse all of the same WordPress functions for doing so. 
Author: Jeremy Blaker / Blaker Design
Version: 1.1.0
Author URI: http://www.blakerdesign.com
*/

class metaBox {
  
  public $name;
  public $boxes;
  public $description;
  public $types;
  public $content;
  
  public function init_metaBox() {
    add_action('add_meta_boxes', array( &$this, 'add_metaBox'));
    add_action('save_post', array( &$this, 'save_metaBox' ));
  }
  
  public function add_metaBox() {
    $name = $this->name;
    $desc = $this->description;
    $types = $this->types;
    
    foreach($types as $type) {
      add_meta_box( "$name", __( "$desc", "$name" ), 
                  array(&$this, 'create_metaBox'), "$type", 'normal', 'high' );
    }
  }
  
  public function create_metaBox() {
    // Use nonce for verification
    wp_nonce_field( plugin_basename(__FILE__), $this->name.'_nonce');
    $content = '<div class="spiffy_meta">'; 
    $content .= $this->content;
    $content .= '</div>';
    echo $content;
  }
  
  public function save_metaBox() {
    
    $page_id = $_POST["ID"];
         
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
        
    if ( !wp_verify_nonce( $_POST[$this->name.'_nonce'], plugin_basename(__FILE__) )) {
      return $page_id;
    }
  
    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
      return $page_id;
  
    
    // Check permissions
    if ( 'page' == $_POST['post_type'] ) {
      if ( !current_user_can( 'edit_page', $post_id ) )
        return $page_id;
    } else {
      if ( !current_user_can( 'edit_post', $post_id ) )
        return $page_id;
    }
   
    foreach($this->boxes as $box) {
      $metaValue = $_POST["$box"];
      $metaName = "_".$box;
      //echo $metaName, $metaValue;exit;
      add_post_meta($page_id, $metaName, $metaValue, true) or update_post_meta($page_id, $metaName, $metaValue);
    }

  }
  
}

function newMetaBox($config) {
  $$name = new metaBox;
  $$name->name = $config["name"];
  $$name->boxes = $config["boxes"];
  $$name->description = $config["desc"];
  $$name->types = $config["types"];
  $$name->content = $config["content"];
  $$name->init_metaBox();
}

function makeTitles($name = '',$titles) {
  
  $boxes = array();
    
  foreach($titles as $title) {
    $box = strip_tags($title);
    $box = strtolower($title);
    $box = str_replace(" ","_",$box);
    $box = $name.'_'.$box;
    array_push($boxes,$box);
  }
  
  return $boxes;
  
}

?>