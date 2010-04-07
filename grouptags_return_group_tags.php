<?php

function buildDivforTags(){

    $tagNames="";
    $groupName = $_POST['groupName'];
    $groupID = fetch_groupID($groupName);
    $tags = fetch_group_terms($groupID);

    $response_tags = "";

    //Generate a tag table if there are tags to display. Otherwise return a blank response.
    if (sizeof($tags)>0){
        $response = '<div class="wrap"><table class="widefat page fixed"><thead><tr><th width="30%" style="" class="page fixed" id="cb" scope="col"><h3>Current Tag Assignments For Group (' . $groupName . ')</h3></th></tr></thead><tbody><tr>';

        $i=0;

        foreach ($tags as $tag){
            $response_tags .= "&nbsp;" . $tag->name;
            $i++;

            if ($i < sizeof($tags)){
                 $response_tags .= ",";
            }
        }
        $response .= $response_tags . "</tr></tbody></table></div>";
        }
    else
    {
        $response="";
    }

	echo $response;

    die();
}

