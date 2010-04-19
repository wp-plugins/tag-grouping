<?php

function displayDivwithExistingTags(){

    $tagNames="";
    $groupName = $_POST['groupName'];
    $groupID = fetch_groupID($groupName);
    $tags = fetch_group_terms($groupID);

    $response_tags = "";

    //Generate a tag table if there are tags to display. Otherwise return a blank response.
    if (sizeof($tags)>0){
        $response = '<div class="wrap"><table class="widefat page fixed"><thead><tr><th width="30%" style="" class="page fixed" id="cb" scope="col"><h3>Current Tag Assignments For Group (' . $groupName . ')</h3></th></tr></thead><tbody>';

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

    $checkedAndUnCheckedTagDiv = checkExistingTags();

    $responseArray = array('existingTags' => $response, 'checkedAndUnCheckedTags' => $checkedAndUnCheckedTagDiv);

    echo json_encode($responseArray);

    die();
}

function checkExistingTags(){
    $groupName = $_POST['groupName'];
    $groupID = fetch_groupID($groupName);
    $termIDs = fetch_group_term_ids($groupID);
    $tags = fetch_all_tags();

    $response ="";
    $CB_tagGroupName = "TG_CheckBox";
    $found = false;

    $taglisting_header = '<table class="widefat page fixed">';
    $taglisting_header .= '<thead><tr><th scope="col" id="cb" class="widefat page fixed" style="" width=30%>';
    $taglisting_header .= '<input type="button" class="button-secondary" style="width:100px" value="Check all Tags"';
    $taglisting_header .= 'onClick="toggleCheckboxes(\'parent_box\')"></th></tr></thead>';
    $taglisting_header .= '<tbody id="parent_box" style="height: 250px; overflow: auto; background-color: #ddd">';

    $taglisting_footer = '</tbody><tfoot><tr><th scope="col" id="cb" class="widefat page fixed" style="" width=30%>';
    $taglisting_footer .= '</th></tr></tfoot></table>';

    foreach ($tags as $tag){
            foreach ($termIDs as $termID){ 
                if (($termID->name == $tag->name) && ($found != true)){
                    $found = true;
                    $response .= '<tr value ="'.$tag->name.'" id="'.$tag->term_id.'" ';
                    $response .= 'onmouseover="this.style.cursor=\'pointer\';this.style.opacity=1;this.filters.alpha.opacity=100;" ';
                    $response .= 'onmouseout="this.style.opacity=0.8;this.filters.alpha.opacity=40" ';
                    $response .= ' style="background-color: #fff;opacity:0.8" >';

                    $response .= "<td value ='". $tag->name ."' id='taggroup". $tag->term_id . "' onClick='toggleCheckbox(\"".$CB_tagGroupName.$tag->term_id."\")'>";
                    $response .= checkboxTagElement($tag->term_id,$tag->name,'true');
                    $response .= "</td></tr>";
                }
            }
            if ($found != true){
                $response .= '<tr value ="'.$tag->name.'" id="'.$tag->term_id.'" ';
                $response .= 'onmouseover="this.style.cursor=\'pointer\';this.style.opacity=1;this.filters.alpha.opacity=100;" ';
                $response .= 'onmouseout="this.style.opacity=0.8;this.filters.alpha.opacity=40" ';
                $response .= ' style="background-color: #fff;opacity:0.8" >';
                $response .= "<td value ='". $tag->name ."' id='taggroup". $tag->term_id . "' onClick='toggleCheckbox(\"".$CB_tagGroupName.$tag->term_id."\")'>";
                $response .= checkboxTagElement($tag->term_id,$tag->name,'false');
                $response .= "</td></tr>";
            }
    $found = false;
    }

    return $taglisting_header . $response . $taglisting_footer;
}