<?php
/*
Plugin Name: Spotted Koi WPMU All Blogs Post Count
Plugin URI: http://spottedkoi.com/plugins/spotted-koi-wordpress-mu-all-blogs-post-count-plugin/
Description: Outputs the total number of posts across all WordPress Mu blogs.
Version: 1.0
Author: Matt Bernier
Author URI: http://spottedkoi.com
*/
add_action('wp_dashboard_setup', 'sk_display_blog_count_dashboard');

function sk_display_blog_count_dashboard() {
   
   global $wpdb;
   $blogCount = $wpdb->get_var("select COUNT(blog_id) from wp_blogs");
   
   wp_add_dashboard_widget('sk_count_all_posts', 'Total posts across all '.$blogCount.' blogs', 'sk_display_blog_count', null);
}

function sk_display_blog_count() {
   //get current blog id
   global $blog_id;
   global $wpdb;
   
   //set a temp var for current blog
   $tempBlogId = $blog_id;
   
   //get all blog ids in the system as array
   $res = $wpdb->get_results('select blog_id from wp_blogs', ARRAY_A);

   $total = 0;
   
   //loop through each blog, get the post counts
   foreach ($res as $result) {
      $wpdb->set_blog_id($result['blog_id']);
      $val = (int)$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_date_gmt < '" . gmdate("Y-m-d H:i:s",time()) . "'");
      $total += $val;
   }
   
   //reset just in case
   $wpdb->set_blog_id($tempBlogId);
   
   //return the total count of all posts
   echo "There are ".$total." total posts across all ".count($res)." of your blogs";
}
?>