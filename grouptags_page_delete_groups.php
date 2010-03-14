<?php

function group_tag_delete_page(){

    $groups = fetch_groupNames();
    ?>
<div class="wrap nosubsub">
    <div class="icon32">
        <img src="<?php echo get_bloginfo('siteurl')?>/wp-content/plugins/tag-grouping/images/Group.png" height=36 width=36>
        </img>
    </div>
<h2>Delete Groups of Tags</h2></div>
<p class="wp-caption-text">Select the Group(s) you wish to delete. Click "Check all Groups" to select all Groups in the list. Click Delete.</p>
<BR>
<?php
if (sizeof($groups) > 0){
    ?>
<form method="post" name="delete_group" actoin="delete_group">
<input type="submit" name="delete_group" id ="delete_group1" class="button-primary" value="<?php _e('Delete') ?>" /><BR><BR>
    <div class="wrap">
        <table class="widefat page fixed">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="widefat page fixed" style="" width=30%>
                        <input type="button" value="<?php _e('Check all Groups')?>" onClick="toggleCheckboxes('parent_box')">
                    </th>
                </tr>
            </thead>
            <tbody id="parent_box">
                <?php
                foreach ($groups as $result){
                    ?>
                <tr>
                    <td>
                        <input type="checkbox" name="<?php echo $result ?>" value="yes" />
                        <?php
                        echo $result;
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?></tbody>
            <tfoot>
                <tr><th scope="col" id="cb" class="widefat page fixed" style="" width=30%>
                        <input type="button" value="<?php _e('Check all Groups')?>" onClick="toggleCheckboxes('parent_box')">
                    </th>
                </tr>
            </tfoot>
        </table>
        <BR>
        <input type="submit" name="delete_group" id ="delete_group" class="button-primary" value="<?php _e('Delete') ?>" />
    </div>
</form>

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

        <?php
        $groups = fetch_groups();
        $i=0;
        if(isset($_POST['delete_group'])){
            foreach($groups as $group){
                if(isset($_POST[$group->groupName])){
                    $groupArray[] = fetch_groupID($group->groupName);
                    $i++;
                }
            }
            if ($i > 0){
                delete_groups($groupArray);
                echo '<script>document.location.replace("' . get_bloginfo("siteurl") . '/wp-admin/admin.php?page=delete_group");</script>';

                echo "<div id='message' class='updated fade below-h2'>Group(s) deleted.</div>";
            }
        }
    }
    else{
        $url = get_bloginfo('siteurl');
        echo "<div id='error' class='error'>No groups exist. <a href=\"$url/wp-admin/admin.php?page=create_group\"'>Go create some!</a></div>";
    }
}