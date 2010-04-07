<?php

function group_tag_admin(){
    
    ?>
<div class="wrap nosubsub">
    <div class="icon32">
        <img src="<?php echo get_bloginfo('siteurl')?>/wp-content/plugins/tag-grouping/images/Group.png" height=36 width=36>
        </img>
    </div>
    <h2>Group Your Tags <input type="button" class="button-secondary" value="Instructions" onclick="displayDiv()" /></h2>
    <p id="instructions" class="popular-tags" style="display: none">
    <b>1. </b>Select the Group Name from the Group Name dropfield.
    <BR>
    <B>2. </B>To add tags to your Group, do one of the following:
    <BR>
    Select the tags you would like to assign to the Group by enabling the checkboxes below. Click "Check all Tags" to select all tags in the list.
    <BR><i> - OR - </i>
    <BR>
    Type the tags you wish to add to the Group into the textfield below, separated by commas.
    <BR>
    <b>3. </b>Click the Submit button when you are finished.</p>
</div>

<?php
$groups = fetch_groupNames();
$tags = fetch_all_tags();

if (sizeof($groups) > 0){
    ?>
<form method="post" name="manage_group">
    <table class="form-table">
        <tr>
            <td width=15%; align=left>
            Select a Group: <BR>
            <select name="groupDropDown" style="width:200px" onChange="jQuery.post(
                                                                        ajaxurl,
                                                                        {
                                                                            action : 'my-special-action',

                                                                            groupName : this.value
                                                                        },
                                                                        function( response ) {
                                                                            //alert(response);
                                                                            document.getElementById('placeholder').innerHTML=response;
                                                                        }
                                                                    )
                                                                    ">
                <?php

                   $i=0;

                   foreach ($groups as $group)
                   {
                       ?>
                        <option id="<?php echo $group ?>">
                            <?php echo $group ?>
                        </option>
                       <?php
                       $i++;
                   }
                ?>
            </select>
            </td>
            <td width=15%; align=left><BR><BR>
                <?php echo createTagDisplayObject();?>
            </td>
            <td></td>
        </tr>
    </table>
    <BR>
    <div id="placeholder">
    </div>
    <BR>
    <div class="wrap">
        <table class="widefat page fixed">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="widefat page fixed" style="" width=30%>
                        <input type="button" class="button-secondary" style="width:100px" value="<?php _e('Check all Tags')?>" onClick="toggleCheckboxes('parent_box')">
                    </th>
                </tr>
            </thead>
            <tbody id="parent_box" style="height: 250px; overflow: auto">
                <?php
                foreach ($tags as $tag){
                    ?>
                <tr>
                    <td >
                        <input type="checkbox" id="checkee" name="<?php echo $tag->term_id ?>" value="yes" />
                        <?php
                        echo $tag->name;
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?></tbody>
            <tfoot>
                <tr>
                    <th scope="col" id="cb" class="widefat page fixed" style="" width=30%>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
    <BR>
    <input type="submit" name="manage_group" id ="manage_group" class="button-primary" value="<?php _e('Submit') ?>" />
</form>
<?php
if(isset($_POST['manage_group'])){
    $group = $_POST['groupDropDown'];
    $newtag = $_POST['newtag'];
    $groupID = fetch_groupID($group);

    $tags = fetch_all_tags();

    $found = false;
    $i=0;

    foreach ($tags as $tag){
        if(isset($_POST[$tag->term_id])){
            $tagArray[$i] = $tag->term_id;
            $groupArray[$i] = $groupID;
            $i++;
        }
    }

    if (count($newtag) > 0){
        $found = true;
    }

    if (($i > 0) || ($found != false)){
        $result = update_group_rules($tags);
        if (($result == false)){
            clear_group_tags($groupID);
            write_group_tags($groupArray, $tagArray);
            update_term_relationships_from_group($groupID, $tagArray);

             if (($newtag != '') && ($newtag != NULL)){
                updateTagsFromText($groupID, $newtag, $tagArray);
            }

            echo "<div id='message' class='updated fade'>Group updated successfully.</div>";
        }
    }
    else{
        echo "<div id='error' class='error'><p>You need to select some tags before updating.</p></div>";
    }
}
}
else
{
?><BR><div id='error' class='error'>
    You don't have any groups yet!
<a href="<?php $url ?>admin.php?page=create_group">Why not create some?</a></div>
<?php
}
}