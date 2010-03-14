<?php
/*********************************************************************************
	Runs every time the plugin is enabled by the admin of the wordpress site.
**********************************************************************************/

function taggroups_install () {
   global $wpdb;

/* Create the table that holds your Tag Group names and ID's */

   $table_name = $wpdb->prefix . "group_term_groups";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
	{
		$sql = "CREATE TABLE " . $table_name . " (
		  groupName TEXT NOT NULL,
		  groupID INT NOT NULL AUTO_INCREMENT PRIMARY KEY
		);";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	}

/* Create the table that holds your tags(terms) that are associated with groups */

   $table_name = $wpdb->prefix . "group_components";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
	{
		$sql = "CREATE TABLE " . $table_name . " (
		  groupID INT,
		  termID INT
		);";

	dbDelta($sql);
	}

/* Create the table that holds the groups that are associated with your posts */

   $table_name = $wpdb->prefix . "group_posts";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
	{
		$sql = "CREATE TABLE " . $table_name . " (
		  groupID INT,
		  postID INT
		);";

	dbDelta($sql);
	}
}