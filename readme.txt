=== Plugin Name ===
Contributors: blakerdev
Tags: meta boxes, create
Requires at least: 2.0.2
Tested up to: 3.8
Stable tag: trunk

== Description ==

This plugin lets you create meta boxes by creating and calling a function but without needing to reuse all of the same WordPress functions for doing so. 

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. (Optional) Create a meta-boxes.php file and include it from your functions.php file. Since the meta box functions can get long this will help keep your functions.php cleaner and keep all your meta boxes together.

== Frequently Asked Questions ==

Q. How are labels generated?
A. When you create a title in the titles array and it is passed into the makeTitles function the name of your meta box gets appended to the begining of the title along with an underscore. Thus if your neta boxe name is 'my_meta_box' and one of your titles is 'My Checkbox' the label for that input will become '_my_meta_box_my_checkbox'.

== Screenshots ==


== Changelog ==

= 1.1.0 =
* Changed type from string to array to support showing a meta boxes in multiple post types. Variable name has changed from $type to $types.

= 1.0.1 =
* Renamed the class of the container around the meta boxes

= 1.0 =
* First version

== Upgrade Notice ==


== Arbitrary section ==

Here is an example meta box function:

<?php

//Meta box for page settings
function pageSettings() {
  
  //Name of the meta box (lowercase without spaces)
  $name = 'page_settings';
  
  //Enter the titles for each one of your boxes
  $titles = array('Section Headline');
  
  //This will run the titles through a function that converts them into lables for the form fields. 
  $boxes = makeTitles($name,$titles);
  
  //Description of meta box
  $desc = 'Page Settings';
  
  //Show on page type:
  $types = array('page');

  //Pull in existing meta field value
  $setHeadline = get_post_meta($_GET['post'],"_page_settings_section_headline",true);
  
  $content = "<fieldset><label>Page Headline:</label><input type\"text\" name=\"section_headline\" value=\"$setHeadline\"/></fieldset>";
  $content .= "<input type=\"hidden\" name=\"ID\" value=\"".$_GET['post']."\"/>";
  
  //Run the function
  $config = array(
    'name'=>$name,
    'boxes'=>$boxes,
    'desc'=>$desc,
    'type'=>$type,
    'content'=>$content
  );
  
  //Create the object
  newMetaBox($config);
}

//Run it
pageSettings();

?>