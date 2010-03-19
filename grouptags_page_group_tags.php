<?php
function group_tag_admin(){
    ?>
<div class="wrap nosubsub">
    <div class="icon32">
        <img src="<?php echo get_bloginfo('siteurl')?>/wp-content/plugins/tag-grouping/images/Group.png" height=36 width=36>
        </img>
    </div>
    <h2>Group Your Tags</h2>
    <p class="wp-caption-text"><B>NOTE:</B> <I>Changes made on this page will over-ride all of your existing tag assignments in the Group. If you wish to retain your previous tag assignments, you must re-select them.</I>
    <BR><BR><B>TIP:</B> <I>If you wish to create a new Group, go to <a href="<?php $url ?>admin.php?page=create_group">Create Group</a>.</I>
    <BR><BR>Select a Group from the drop-down list. Select the tags you would like to assign to the Group. Click "Check all Tags" to select all tags in the list. Click Update Group.</p>
</div>
<?php
$groups = fetch_groupNames();
$tags = fetch_all_tags();

if (sizeof($groups) > 0){
    ?>

<form method="post" name="manage_group">
    <BR><table>Select a Group:
    <select name="groupDropDown" style="width:200px">

        <?php

        foreach ($groups as $group)
        {
            ?>
        <option id="<?php echo $group ?>">
            <?php echo $group ?>
        </option>
        <?php
    }
    ?>
    </select></table>
    <BR><BR>
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

    <SCRIPT LANGUAGE="JavaScript">
        function toggleCheckboxes(id) {
            if (!document.getElementById){ return; }
            if (!document.getElementsByTagName){ return; }
            var inputs = document.getElementById(id).getElementsByTagName("input");
            for(var x=0; x < inputs.length; x++) {
                if (inputs[x].type == 'checkbox'){
                    inputs[x].checked = !inputs[x].checked;
                }
            }
        }
    </SCRIPT>

    <BR>
    <input type="submit" name="manage_group" id ="manage_group" class="button-primary" value="<?php _e('Update Group') ?>" />
</form>
<?php
if(isset($_POST['manage_group'])){
    $group = $_POST['groupDropDown'];
    $groupID = fetch_groupID($group);

    $tags = fetch_all_tags();

    $i=0;

    foreach ($tags as $tag){
        if(isset($_POST[$tag->term_id])){
            $tagArray[$i] = $tag->term_id;
            $groupArray[$i] = $groupID;
            $i++;
        }
    }
    if ($i > 0){
        $result = update_group_rules($tags);
        if (($result == false)){
            clear_group_tags($groupID);
            write_group_tags($groupArray, $tagArray);
            update_term_relationships_from_group($groupID, $tagArray);

            echo "<div id='message' class='updated fade below-h2'>Group updated successfully.</div>";
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