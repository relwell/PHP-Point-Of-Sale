<script type="text/javascript">
var listsToGroups = [];

<? foreach ($lists as $list) { ?>
<? if (!$list['groupings']) { continue; } ?>
listsToGroups['<?=$list['id']?>'] = [];
    <? foreach ($list['groupings'] as $grouping) { ?>
    <? if ($add) { ?>
    <? foreach ($grouping['groups'] as $group) { ?>
    listsToGroups['<?=$list['id']?>'].push(['<?=$grouping['id']?>-<?=$group['name']?>', '<?=$grouping['name']?>: <?=$group['name']?>']);
    <? } ?>
    <? } else { ?>
    listsToGroups['<?=$list['id']?>'].push(['<?=$grouping['id']?>', '<?=$grouping['name']?>']);
    <? } ?>
    <? } ?>
<? } ?>

function changeGroups(dom)
{
	var selected = $(dom).find(':selected').val();
	if (selected != '') {
		grouping = listsToGroups[selected];
		if (typeof(grouping) != 'undefined') {
			var optionstring = '<option value=""></option>';
			$(grouping).each(function(){
				optionstring = optionstring + '<option value="'+this[0]+'">'+this[1]+'</option>';
				});
			<? if (!$add) { ?>
			optionstring = optionstring + '<option value="new">New Grouping</option>';
			<? } ?>
            $('#grouppicker').html(optionstring);
            $('#grouppicker-wrapper').show();
		} else {
			$('#grouppicker-wrapper').hide();
		}
	} else {
		$('#grouppicker').hide();
	} 
}

function changeGroupName(dom)
{
	var selected = $(dom).find(':selected').val();
	if (selected == 'new') {
		$('#groupingwrapper').show();
	} else {
	    $('#groupingwrapper').hide();
	}
}

function groupCreate()
{
    var groupingIsNew = 0;
    if (($('#grouppicker').val() == 'new')) {
		var groupingIsNew = 1;
    } 
    
	var groupingID = $('#grouppicker').val();
	
	if (groupingID == '') {
		alert('Please select a grouping.');
		return false;
	}

	var groupName = $('#grouptext').val();
	if (groupName == '') {
		alert('Please specify a group name');
		return false;
	}

	var groupingName = $('#groupingtext').val();
	if (groupingIsNew == 1 && groupingName == '') {
		alert('Please specify a name for your grouping');
		return false;
	}
    
    $.post('<?=preg_replace('/index.php.*/', '', base_url())?>index.php/mailchimpdash/groupcreate',
            {
             listID: $('#listpicker').val(),
             groupingIsNew: groupingIsNew,
             groupingID: groupingID,
             customerIDs: getCustomers(),
             groupingType: $('#groupingtype').val(),
             groupingName: groupingName,
             groupName: groupName
            },
            function(response) {
                if (typeof(response) != 'object') {
                    var data = JSON.parse(response);
                } else {
                    var data = response;
                }
                if (data.success) {
                    set_feedback(data.message, 'success_message', false);
                } else {
                    set_feedback(data.message, 'error_message', true);
                }
                tb_remove();
            });
}

function groupAdd()
{
    $.post('<?=preg_replace('/index.php.*/', '', base_url())?>index.php/mailchimpdash/groupadd',
            {
             listID: $('#listpicker').val(),
             group: $('#grouppicker').val(),
             customerIDs: getCustomers()
            },
            function(response) {
                if (typeof(response) != 'object') {
                    var data = JSON.parse(response);
                } else {
                    var data = response;
                }
                if (data.success) {
                    set_feedback(data.message, 'success_message', false);
                } else {
                    set_feedback(data.message, 'error_message', true);
                }
                tb_remove();
            });
}

function getCustomers()
{
	var emails = [];
	$('#sortable_table tr td:first-child').each(function(){
		emails.push($(this).text());
	});
	return emails.join(',');
}

</script>
<link rel="stylesheet" href="<?=preg_replace('/index.php.*/', '', base_url())?>css/mailchimpdash/charttocampaign.css" />
<h3 id="exportthis"><?=$add ? 'Add to' : 'Create'?> Group</h3>
<div id="groupoptions">
    <div id="groupoptions-listpicker">
        <label for="listpicker">Choose Your List:</label>
        <select id="listpicker" onChange='changeGroups(this);'>
            <option value=""></option>
            <? foreach ($lists as $list) { ?>
            <option value="<?=$list['id']?>"><?=$list['name']?></option>
            <? } ?>
        </select>
        <br/> <br/>
        <div id="grouppicker-wrapper" style="display: none;">
        <label for="grouppicker"><?=$add ? 'Group:' : 'Grouping'?> </label>
        <select id="grouppicker" onChange='changeGroupName(this)'/>
        <br/><br/>
        <? if (!$add) { ?>
        <div id="groupingwrapper" style="display: none">
        <label for="groupingtext">Grouping Name: </label>
        <input type="text" id="groupingtext" /><br/><br/>
        <label for="groupingtype">Grouping Type:</label>
        <select id="groupingtype">
        	<option value="checkboxes" selected="selected">Checkboxes</option>
        	<option value="radio">Radio</option>
        	<option value="hidden">Hidden</option>
        	<option value="dropdown">Dropdown</option>
        </select>
        <br/><br/>
        </div>
        <label for="grouptext">Group: </label>
        <input type="text" id="grouptext" />
        <? } ?>
        </div>
    </div>
    <br/>
    <div id="groupoptions-buttonwrapper">
        <a class="button pill" onClick="<?=$add ? 'groupAdd()' : 'groupCreate()'?>">Add to Group</a>
    </div>
</div>