<?php
/*********************************************************************************
    Query table wp_group_term_groups and get all group names.
    Returns an array of group names.
**********************************************************************************/

function fetch_groupNames(){
    global $wpdb;
    $table_name = $wpdb->prefix . "group_term_groups";

    $sql = "SELECT $table_name.groupName FROM $table_name";
    $groups = $wpdb->get_col($sql,0);

    return $groups;
}

/*********************************************************************************
    Query table wp_group_term_groups and get all groups.
    Returns an array of groups.
**********************************************************************************/

function fetch_groups(){
    global $wpdb;
    $table_name = $wpdb->prefix . "group_term_groups";

    $sql = "SELECT * FROM $table_name";
    $groups = $wpdb->get_results($sql);

    return $groups;
}

/*********************************************************************************
    Query table wp_group_term_groups and get a specific group.
**********************************************************************************/

function fetch_groupID($groupName){
    global $wpdb;
    $table_name = $wpdb->prefix . "group_term_groups";

    $sql = "SELECT * FROM " . $table_name . " WHERE groupName = '" . $groupName . "'";

    $groupID = $wpdb->get_row($sql, ARRAY_N);
    foreach ($groupID as $group){
        $groupID = $group;
    }

    return $groupID;
}

/*********************************************************************************
    get all tags.
**********************************************************************************/

function fetch_all_tags(){
    global $wpdb;
    $table_terms = $wpdb->prefix . "terms";
    $table_terms_tax = $wpdb->prefix . "term_taxonomy";

    $results = $wpdb->get_results("SELECT " . $table_terms . ".term_id, " . $table_terms . ".name,
    " . $table_terms_tax . ".term_id, " . $table_terms_tax . ".taxonomy FROM " . $table_terms . ", " . $table_terms_tax
        . " WHERE " . $table_terms . ".term_id = " . $table_terms_tax . ".term_id AND " . $table_terms_tax . ".taxonomy =
    'post_tag'");
    return $results;
}

/*********************************************************************************
    Get Tag ID.
**********************************************************************************/

function fetch_termID($tagName){
    global $wpdb;
    $table_terms = $wpdb->prefix . "terms";
    $i=0;

    $sql = "SELECT term_id FROM " . $table_terms . " WHERE " . $table_terms . ".name = '" . $tagName ."'";

    $results = $wpdb->get_var($sql);

    return $results;
}

/*********************************************************************************
    Query table wp_group_posts and get all tags for a specific post.
**********************************************************************************/

function fetch_post_tags_by_group($postID){
    global $wpdb;
    $table_group = $wpdb->prefix . "group_posts";
    $table_components = $wpdb->prefix . "group_components";
    $table_terms = $wpdb->prefix . "terms";

    $sql = "SELECT * FROM " . $table_group . " JOIN (" . $table_components . "," .
    $table_terms . ") ON (" . $table_group . ".postID = " . $postID . " AND " .
    $table_components . ".groupID = " . $table_group . ".groupID AND " .
    $table_terms . ".term_id = " . $table_components . ".termID)";

    $results = $wpdb->get_results($sql);
    return $results;
}

/*********************************************************************************
    Retrieve all tags associated with the specified group.
**********************************************************************************/

function fetch_group_terms($groupID){
    global $wpdb;
    $table_terms = $wpdb->prefix . "terms";
    $table_components = $wpdb -> prefix . "group_components";

    $sql = "SELECT name FROM " . $table_terms . " JOIN (" . $table_components . ") ON ("
            . $table_terms . ".term_id = " . $table_components . ".termID AND "
            . $table_components . ".groupID = " . $groupID .")";

    $results = $wpdb->get_results($sql);

    return $results;
}
/*********************************************************************************
    Prior to calling write_group_posts, this function should be called to
    clear the groups that are associated with the working post. If it is not
    called, there is a possibility that groups will be associated multiple
    times per post.
**********************************************************************************/

function clear_group_posts(){
    global $wpdb;
    $local_post_ID = get_postID();

    $table_name = $wpdb->prefix . "group_posts";

    $sql = "DELETE FROM " . $table_name . " WHERE postID = $local_post_ID";
    $results = $wpdb->query($sql);
}

/*********************************************************************************
    Prior to calling write_group_tags, this function should be called to
    clear the tags that are associated with the working group. If it is not
    called, there is a possibility that tags will be associated multiple
    times per group.
**********************************************************************************/

function clear_group_tags($groupID){
    global $wpdb;

    $table_name = $wpdb->prefix . "group_components";

    $sql = "DELETE FROM " . $table_name . " WHERE groupID = $groupID";
    $results = $wpdb->query($sql);
}
/*********************************************************************************
    Updates the database table wp_group_posts with all groups associated with
    the working post.
**********************************************************************************/

function write_group_posts($groupID){
    global $wpdb;
    $local_post_ID = get_postID();

    $table_name = $wpdb->prefix . "group_posts";
    $sql = "INSERT INTO " . $table_name . " VALUES ($groupID,  $local_post_ID)";
    $results = $wpdb->query($sql);

}
/*********************************************************************************
    Retrieve all groups that have been associated with a post.
    Returns an array of results.
**********************************************************************************/

function get_post_groups(){
    global $wpdb;
    $local_post_ID = get_postID();

    $results ="";

    $table_name = $wpdb->prefix . "group_posts";
    $sql = "SELECT * FROM " . $table_name . " WHERE postID = $local_post_ID";
    $results = $wpdb->get_results($sql);

    return $results;
}

/*********************************************************************************
 * Perform error handling and write the group to the database if it passes
**********************************************************************************/

function write_group($groupName){
    global $wpdb;
    $safe_groupName = $wpdb->escape($groupName);

    $result = create_group_rules_check($safe_groupName);
    if ($result == false){
        $table_name = $wpdb->prefix . "group_term_groups";
        $results = $wpdb->insert($table_name, array('groupName' => $safe_groupName));
    } else{
        echo $result;
    }
    return $result;
}

/*********************************************************************************
 * This inserts the tag id and group id into the group components table.
**********************************************************************************/

function write_group_tags($groupID, $termIDs){
    global $wpdb;
    $groupID = $groupID[0];

    $table_name = $wpdb->prefix . "group_components";

    foreach($termIDs as $term){
        $results = $wpdb->get_results("INSERT INTO " . $table_name . " SET termID = " . $term . ", groupID = " . $groupID);
    }
}

/*********************************************************************************
 * This inserts the tag id and group id into the group components table.
**********************************************************************************/

function write_group_tag($groupID, $term){
    if (($term != "") && ($term != NULL)){
        global $wpdb;

        $table_name = $wpdb->prefix . "group_components";

        $sql = "INSERT INTO " . $table_name . " SET termID = " . $term . ", groupID = " . $groupID;

        $results = $wpdb->query($sql);
    }
}

/*********************************************************************************
 * This is called when a user adds a group to a post while editting.
**********************************************************************************/

function update_term_relationships_from_post($groupID){
    global $wpdb;
    $local_post_ID = get_postID();

    $table_group_components = $wpdb->prefix . "group_components";

    $sql = "SELECT " . $table_group_components . ".termID, " . $wpdb->term_taxonomy . ".term_taxonomy_id FROM " .
    $table_group_components . ", " . $wpdb->term_taxonomy . " WHERE " . $table_group_components . ".groupID = " . $groupID .
     " AND " . $wpdb->term_taxonomy . ".term_id = " . $table_group_components . ".termID";

    $results = $wpdb->get_results($sql);

    foreach ($results as $result){
        $run = $wpdb->query("INSERT IGNORE INTO " . $wpdb->term_relationships . " SET " .
         "object_id = ". $local_post_ID . ", " .
         "term_taxonomy_id = " . $result->term_taxonomy_id .
         ", term_order = 0");
    }
}

/*********************************************************************************
 * This is called when a user adds tags to a group allowing existing posts that
 * utilize the group selected to be updated with the new tags.
**********************************************************************************/

function update_term_relationships_from_group($groupID, $tagArray){
    global $wpdb;
    $local_post_ID = get_postID();

    $tag_string="";

    $table_group_posts = $wpdb->prefix . "group_posts";

    foreach ($tagArray as $tag){
        if (end($tagArray) != $tag){
            $tag_string += $table_group_posts . ".termID = " . $tag . " AND " ;
        }
        else
        {
            $tag_string += $table_group_posts . ".termID = " . $tag;
        }
    }

    $sql = "SELECT " . $table_group_posts . ".postID FROM " . $table_group_posts . " WHERE "
    . $table_group_posts . ".groupID = " . $groupID . " AND " . $tag_string;

    $results = $wpdb->get_results($sql);

    foreach ($results as $result){
        $run = $wpdb->query("INSERT IGNORE INTO " . $wpdb->term_relationships . " SET " .
         "object_id = ". $local_post_ID . ", " .
         "term_taxonomy_id = " . $result->term_taxonomy_id .
         ", term_order = 0");
    }
}

/*********************************************************************************
 * This is called when a user chooses to delete an existing group or groups.
**********************************************************************************/

function delete_groups($groups){
    global $wpdb;

    $table_group_posts = $wpdb->prefix . "group_posts";
    $table_group_components = $wpdb->prefix . "group_components";
    $table_group_term_groups = $wpdb->prefix . "group_term_groups";

    foreach ($groups as $group){
        $sql = "DELETE FROM " . $table_group_posts . " WHERE groupID = " . $group;
        $results = $wpdb->query($sql);

        $sql = "DELETE FROM " . $table_group_components . " WHERE groupID = " . $group;
        $results = $wpdb->query($sql);

        $sql = "DELETE FROM " . $table_group_term_groups . " WHERE groupID = " . $group;
        $results = $wpdb->query($sql);
    }
}

/*********************************************************************************
 * This is called when a user adds new tags while creating or editting a group.
**********************************************************************************/

function write_array_of_tags($tags, $slugs){
    global $wpdb;
    $table_terms = $wpdb->prefix . "terms";
    $table_term_taxonomy = $wpdb->term_taxonomy;
    $i=0;

    if (count($tags) > 1){
        foreach ($tags as $tag){
            //Dont write out a blank tag.
            if ($tag != ""){
                //Insert the new tags made by the user.
                $results = $wpdb->query("INSERT IGNORE INTO " . $table_terms . " SET name = '" . $tag . "', slug = '" . $slugs[$i] . "', term_group = 0");
                //Get the ID for the new tags.
                $termID = $wpdb->get_results("SELECT term_id FROM " . $table_terms . " WHERE name = '" . $tag . "'");
                //Update the taxonomy table to show that these are post tags.
                foreach ($termID as $term){
                    $results = $wpdb->query("INSERT IGNORE INTO " . $table_term_taxonomy . " SET term_id = " . $term->term_id . ", taxonomy = 'post_tag', description = '', parent = 0, count = 0");
                }
                $i++;
            }
        }
    }
    else if ((count($tags) == 1) && ($tags != "")){
        //Insert the new tags made by the user.
        $results = $wpdb->query("INSERT IGNORE INTO " . $table_terms . " SET name = '" . $tags . "', slug = '" . $slugs . "', term_group = 0");
        //Get the ID for the new tags.
        $termID = $wpdb->get_results("SELECT term_id FROM " . $table_terms . " WHERE name = '" . $tags . "'");
        //Update the taxonomy table to show that these are post tags.
        foreach ($termID as $term){
            $results = $wpdb->query("INSERT IGNORE INTO " . $table_term_taxonomy . " SET term_id = " . $term->term_id . ", taxonomy = 'post_tag', description = '', parent = 0, count = 0");
        }
    }
}