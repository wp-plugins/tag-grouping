<?php
/*
Plugin Name: Tag Grouping
Plugin URI: http://www.croutonsoflife.com/wordpress/tag-grouping-plug-in-documentation-for-wordpress
Description: Create and maintain groups of commonly used tags for posts. Add these groups to posts without having to add them individually.
Version: 1.4.3
Author: Michael Gunnett
Author URI: http://www.croutonsoflife.com

2010  Michael Gunnett  (mike@croutonsoflife.com)

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

$update = new updatePosts();

//Include files
include("grouptags_install.php");
include("grouptags_db.php");
include("grouptags_page_create_group.php");
include("grouptags_page_group_tags.php");
include("grouptags_page_delete_groups.php");
include("grouptags_rules.php");
include("grouptags_add_create_tag_functions.php");
include("grouptags_return_group_tags.php");
include("grouptags_display_elements.php");

global $post_ID;

register_activation_hook(__FILE__, 'taggroups_install');

/* Actions */
add_action ( 'admin_menu', 'render_tag_post_box' );
add_action ( 'admin_menu', 'group_tag_admin_menu' );
add_action ( 'save_post', Array(&$update, 'update_group_posts') );
add_action ( 'init', 'wp_load_scripts' );

/* Ajax Actions */
add_action ( 'wp_ajax_my-special-action', 'displayDivwithExistingTags' );
add_action ( 'wp_ajax_toggle_tag_checkbox', 'tagTableRowAction' );


/* Filter */
add_filter( 'the_content', 'retrieve_postID');

function render_tag_post_box()
{
    add_meta_box( 'taggroup_sectionID', 'Tag Groups', 'taggroups_post_box', 'post', 'side', 'low' );
}	

/*********************************************************************************
    Load javascript files for assorted pages.
**********************************************************************************/
function wp_load_scripts(){
    wp_enqueue_script('my_script_handle', plugin_dir_url( __FILE__ ) . 'grouptags_scripts.js');
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
        ?>
        <form method="post" name="group_selections"><?php
        foreach ($groups as $group)
        {
            $found = false;
            foreach ($checked_groups as $checked_group)
            {
                if (($checked_group->groupID == $group->groupID) && ($found == false))
                {
                    ?>
                    <BR><input type="checkbox" id="<?php echo $group->groupID ?>" name="<?php echo $group->groupName ?>" checked />
                    <?php
                    echo $group->groupName;
                    $found = true;
                }
            }
                if ($found <> true)
                {
                    ?>
                    <BR><input type="checkbox" id="<?php echo $group->groupID ?>" name="<?php echo $group->groupName ?>" />
                    <?php
                    echo $group->groupName;
                    $found = false;
                }
            }
        ?><BR><BR><?php
        echo "Enable the groups that contain tags you want to include with your post.";
        echo "</form>";
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

class updatePosts{
    function update_group_posts($post_ID){

        If ( Defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

        clear_group_posts();

        $groups = fetch_groups();
        foreach ($groups as $group)
        {
            $group1 = str_replace(" ", "_",$group->groupName);
            if(isset($_POST[$group1]))
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
    $post_ID = $post->ID;
    
    return $content;
}

function get_postID(){
    global $post_ID;
    $local_postID = $post_ID;
    return $local_postID;
}