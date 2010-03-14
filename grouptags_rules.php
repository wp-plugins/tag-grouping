<?php

/*********************************************************************************
    Function to call when inserting a group into the database. This ensures that
 * all error conditions are handled. Errors are returned in html format.
**********************************************************************************/

function create_group_rules_check($groupName){
    $duplicate = check_duplicate($groupName);

    if ($duplicate == false){
        $blank = check_blank($groupName);
    }
    else
    {
        return $duplicate;
    }

    if ($blank == false){

    }
    else
    {
        return $blank;
    }

    return false;
}

/*********************************************************************************
    Function to call when updating a group in the database. This ensures that
 * all error conditions are handled. Errors are returned in html format.
**********************************************************************************/

function update_group_rules($tags){
    if (sizeof($tags) < 1){
        $error = "<div id='error' class='error'>You did not select any tags.</div>";
    }
    else
    {
        return false;
    }

    return $error;
}

/*********************************************************************************
    Function to call when updating a group in the database. This ensures that
 * all error conditions are handled. Errors are returned in html format.
**********************************************************************************/

function find_unique_tags($postID){
    $postTags = wp_get_post_tags($postID);
    $tags = fetch_post_tags_by_group($postID);

    foreach ($tags as $tag){
        $found = false;
        foreach($postTags as $postTag){
            if ($tag->term_id == $postTag->term_id){
                $found = true;
            }
        }

        if ($found == false){
            $postTags[] = $tag;
        }
    }

    return $postTags;
}

/*********************************************************************************
    This function checks to ensure that no group name exists that matches the
 * one input by the user.
**********************************************************************************/

function check_duplicate($groupName){
    foreach(fetch_groups() as $group){
        if ($groupName == $group){
            return "<div id='error' class='error'>Group Name already exists. Please try another.</div>";
        }
    }
    return false;
}

/*********************************************************************************
    This function checks to ensure that the user input some value in the create
 * group field.
**********************************************************************************/

function check_blank($groupName){
    if (($groupName =="") || ($groupName == NULL)){
        return "<div id='error' class='error'>Group Name cannot be blank. Please try another.</div>";
    }
    return false;
}