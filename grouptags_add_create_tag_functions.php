<?php

/*********************************************************************************
    Display element for users to input tags.
**********************************************************************************/
function createTagDisplayObject(){

    $html = "Add Tags: <BR><input type='text' value='' autocomplete='off' size='16' class='newtag form-input-tip' name='newtag' id='newtag'>";
    $html .= "<p class='howto'>Separate tags with commas.</p>";

    return $html;
}

/*********************************************************************************
    Insert new tags into the database. Then associate them with the group.
**********************************************************************************/

function updateTagsFromText($groupID, $listoftags, $checkedTags){
    if (strlen($listoftags) > 0){
        $tags = parseTags($listoftags);

        $newTags = getNewTags($tags);
        $existingTags = getExistingTags($tags);

        if (($existingTags != NULL) && (count($existingTags) > 0)){
            $existingTags = removeCheckedTags($existingTags, $checkedTags);
        }

        if (count($newTags) > 0){
            writeNewTags($newTags);
        }

        foreach($tags as $tag){
            $termID = fetch_termID($tag);
            write_group_tag($groupID, $termID);
        }
    }
}

/*********************************************************************************
    Take users input and put the tags into an array of strings for further processing.
**********************************************************************************/

function parseTags($listoftags){
    $listoftags .= ",";
    $tags = explode(",", $listoftags);

    return $tags;
}

/*********************************************************************************
    Remove the checked values from the input values so duplicates are not inserted.
**********************************************************************************/

function removeCheckedTags($existingTags, $checkedTags){

    $results = "";
    if (count($existingTags) > 1){
        foreach ($existingTags as $tag){
            if (!in_array($tag, $checkedTags)){
                $results[] = $tag;
            }
        }
    }

    return $results;
}
/*********************************************************************************
    Determine the new tags that were supplied by the user.
**********************************************************************************/

function getNewTags($tags){
    $existing_tags = fetch_all_tags();

    $i = 0;
    $results = NULL;

    foreach ($tags as $tag){
        if (!in_array($tag, $existing_tags)){
            $results[$i] = $tag;
            $i++;
        }
    }

    return ($results);
}

/*********************************************************************************
    Determine the existing tags that were supplied by the user.
**********************************************************************************/

function getExistingTags($tags){
    $existing_tags = fetch_all_tags();
    $i = 0;
    $results ='';

    foreach ($tags as $tag){
        if (array_key_exists($tag, $existing_tags)){
            $results[$i] = $tag;
            $i++;
        }
    }
    return ($results);
}

/*********************************************************************************
    Write the new tags to the terms table.
**********************************************************************************/

function writeNewTags($tags){
    if (sizeof($tags) > 0){
        $slugs = createTagSlugs($tags);
        write_array_of_tags($tags, $slugs);
    }
}

/*********************************************************************************
    Create slugs for the new terms.
**********************************************************************************/

function createTagSlugs($tags){
    if (count($tags) > 1){
        foreach ($tags as $tag){
            preg_replace("/ /", "-", $tag);
        }
    }
    return $tags;
}