<?php
/*
Plugin Name: Tag Grouping
Plugin URI: http://www.croutonsoflife.com/wordpress/tag-grouping-plug-in-documentation-for-wordpress
Description: Create and maintain groups of commonly used tags for posts. Add these groups to posts without having to add them individually.
Version: 1.0
Author: Michael Gunnett
Author URI: http://www.croutonsoflife.com

Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Include files
include("grouptags_install.php");
include("grouptags_db.php");
include("grouptags_page_create_group.php");
include("grouptags_page_group_tags.php");
include("grouptags_page_delete_groups.php");
include("grouptags_rules.php");

global $post_ID;

register_activation_hook(__FILE__, 'taggroups_install');

/* Actions */
add_action ( 'admin_menu', 'render_tag_post_box' );
add_action ( 'admin_menu', 'group_tag_admin_menu' );
add_action ( 'save_post', 'update_group_posts' );

/* Filter */
add_filter( 'the_content', 'retrieve_postID');

function render_tag_post_box()
{
    add_meta_box( 'taggroup_sectionID', 'Tag Groups', 'taggroups_post_box', 'post', 'side', 'low');
}	

/*********************************************************************************
    Generates the seletion box seen by the user when creating or modifying a
    Post. This box shows all groups and whether they are already enabled for
    the working Post.
**********************************************************************************/

function taggroups_post_box(){
    $local_post_ID = get_postID();

    $checked_groups = get_post_groups();

    $groups = fetch_groups();

    if (sizeof($groups) > 0){
        foreach ($groups as $group)
        {
            $found = false;
            foreach ($checked_groups as $checked_group)
            {
                if ($checked_group->groupID == $group->groupID)
                {
                    ?>
                    <BR><input type="checkbox" id="<?php echo $group->groupID ?>" name="<?php echo $group->groupName ?>" value="1" checked />
                    <?php
                    echo $group->groupName;
                    $found = true;
                }
            }
                if ($found <> true)
                {
                    ?>
                    <BR><input type="checkbox" id="<?php echo $group->groupID ?>" name="<?php echo $group->groupName ?>" value="1" />
                    <?php
                    echo $group->groupName;
                    $found = false;
                }
            }
        ?><BR><BR><?php
        echo "Enable the groups that contain tags you want to include with your post.";
        }
    else
        {
            ?>
            <a href="<?php $url ?>admin.php?page=create_group">Create a Group!</a>
            <?php
        }
}
/*********************************************************************************
Loop through and find all group checkboxes that are enabled.
Call write_group_posts to store these selections in the database.
**********************************************************************************/

function update_group_posts(){

$local_post_ID = get_postID();
    
if (wp_is_post_autosave($local_post_ID) || ($local_post_ID < 1) )
{

}
else
{
    clear_group_posts();

    $groups = fetch_groups();
    foreach ($groups as $group)
    {
        if(isset($_POST[$group->groupName]))
        {
            write_group_posts($group->groupID);
            update_term_relationships_from_post($group->groupID);
        }
    }
}
}

/*********************************************************************************
Stores the postID of the post the user is working on as a global variable
from inside the Wordpress loop for use later, when outside the loop.
**********************************************************************************/

function retrieve_postID($content){
    global $post;
    global $post_ID;
    $post_ID = $post;

    return $content;
}

function get_postID(){
    global $post_ID;
    $local_postID = $post_ID;
    return $local_postID;
}