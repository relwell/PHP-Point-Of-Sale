<?php 
//OJB: Check if for excel export process
if($report_service->renderData['data']['export_excel'] == 1){
	ob_start();
	$this->load->view("partial/header_excel");
}else{
	$this->load->view("partial/header");
	echo campaign_export_script();
} 
?>
<div id="page_title" style="margin-bottom:8px;"><?php echo $report_service->renderData['data']['title'] ?></div>
<div id="page_subtitle" style="margin-bottom:8px;"><?php echo $report_service->renderData['data']['subtitle'] ?></div>
<?=$report_service->render()?>
<?=$report_service->renderData['data']['export_excel'] ? '' : campaign_export_button()?>
<?=$report_service->renderData['data']['add_to_group'] 
    && !$report_service->renderData['data']['export_excel'] 
    ? add_to_group_button() : ''?>
<?=$report_service->renderData['data']['export_excel'] 
        ? '' 
        :  repeatable_campaign_button($report_service->renderData['data']['report_name']) ?>
<div id="feedback_bar"></div>
<?php 
if($report_service->renderData['data']['export_excel'] == 1){
	$this->load->view("partial/footer_excel");
	$content = ob_end_flush();
	
	$filename = trim($filename);
	$filename = str_replace(array(' ', '/', '\\'), '', $report_service->renderData['data']['title']);
	$filename .= "_Export.xls";
	header('Content-type: application/ms-excel');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $content;
	die();
	
}else{
	$this->load->view("partial/footer"); 
?>
<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$(".tablesorter a.expand").click(function(event)
	{
		$(event.target).parent().parent().next().find('.innertable').toggle();
		
		if ($(event.target).text() == '+')
		{
			$(event.target).text('-');
		}
		else
		{
			$(event.target).text('+');
		}
		return false;
	});
	
});
</script>
<?php 
} // end if not is excel export 
?>