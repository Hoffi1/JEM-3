<?php
/**
 * @version 3.0.1
 * @package JEM
 * @copyright (C) 2013-2014 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;
// JEMHelper::headerDeclarations();
JHtml::_('bootstrap.tooltip');
?>
<script type="text/javascript">
    function selectAll()
    {
        selectBox = document.getElementById("cid");

        for (var i = 0; i < selectBox.options.length; i++){
             selectBox.options[i].selected = true;
        }
    }

    function unselectAll()
    {
        selectBox = document.getElementById("cid");

        for (var i = 0; i < selectBox.options.length; i++){
             selectBox.options[i].selected = false;
        }
    }
</script>


<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data" id="adminForm">
	
	<div class="row-fluid">	
				<div class="span6">
	
	<fieldset class="form-horizontal">
			<legend><?php echo JText::_('COM_JEM_EXPORT_EVENTS_LEGEND');?></legend>
			
		<div class="control-group">
			<div class="control-label"><label class="hasTooltip" title="<?php echo JText::_('COM_JEM_EXPORT_ADD_CATEGORYCOLUMN'); ?>::<?php echo JText::_('COM_JEM_EXPORT_ADD_CATEGORYCOLUMN'); ?>">
		<?php echo JText::_('COM_JEM_EXPORT_ADD_CATEGORYCOLUMN'); ?></label></div>
		<div class="controls"><?php
				$categorycolumn = array();
				$categorycolumn[] = JHtml::_('select.option', '0', JText::_('JNO'));
				$categorycolumn[] = JHtml::_('select.option', '1', JText::_('JYES'));
				$categorycolumn = JHtml::_('select.genericlist', $categorycolumn, 'categorycolumn', array('size'=>'1','class'=>'inputbox'), 'value', 'text', '1');
				echo $categorycolumn;?>
		</div></div>
		
		<div class="control-group">
			<div class="control-label"><label for="dates"><?php echo JText::_('COM_JEM_DATE').':'; ?></label></div>
			<div class="controls"><?php echo JHtml::_('calendar', date("Y-m-d"), 'dates', 'dates', '%Y-%m-%d', array('class' => 'inputbox validate-date')); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><label for="enddates"><?php echo JText::_('COM_JEM_ENDDATE').':'; ?></label></div>
			<div class="controls"><?php echo JHtml::_('calendar', date("Y-m-d"), 'enddates', 'enddates', '%Y-%m-%d', array('class' => 'inputbox validate-date')); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><label for="cid"><?php echo JText::_('COM_JEM_CATEGORY').':'; ?></label></div>
			<div class="controls"><?php echo $this->categories; ?>
			<input class="btn" name="selectall" value="<?php echo JText::_('COM_JEM_EXPORT_SELECT_ALL_CATEGORIES'); ?>" onclick="selectAll();"><br />
			<input class="btn" name="unselectall" value="<?php echo JText::_('COM_JEM_EXPORT_UNSELECT_ALL_CATEGORIES'); ?>" onclick="unselectAll();">
			</div>
		</div>
		<div class="control-group">	
			<div class="control-label"><label></label></div>
			<div class="controls"><input class="btn" type="submit" id="csvexport" value="<?php echo JText::_('COM_JEM_EXPORT_FILE'); ?>" onclick="document.getElementsByName('task')[0].value='export.export';return true;"></input></div>
		</div>
	</fieldset>
		
	</div><div class="span6">
	
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('COM_JEM_EXPORT_OTHER_LEGEND');?></legend>

		<div class="control-group">
			<div class="control-label"><label><?php echo JText::_('COM_JEM_EXPORT_CATEGORIES'); ?></label></div>
			<div class="controls"><input type="submit" class="btn" id="csvexport" value="<?php echo JText::_('COM_JEM_EXPORT_FILE'); ?>" onclick="document.getElementsByName('task')[0].value='export.exportcats';return true;"></input></div>
		</div>
		<div class="control-group">
			<div class="control-label"><label><?php echo JText::_('COM_JEM_EXPORT_VENUES'); ?></label></div>
			<div class="controls"><input type="submit" class="btn" id="csvexport" value="<?php echo JText::_('COM_JEM_EXPORT_FILE'); ?>" onclick="document.getElementsByName('task')[0].value='export.exportvenues';return true;"></input></div>
		</div>
		<div class="control-group">
			<div class="control-label"><label><?php echo JText::_('COM_JEM_EXPORT_CAT_EVENTS'); ?></label></div>
			<div class="controls"><input type="submit" class="btn" id="csvexport" value="<?php echo JText::_('COM_JEM_EXPORT_FILE'); ?>" onclick="document.getElementsByName('task')[0].value='export.exportcatevents';return true;"></input></div>
		</div>
		
		</fieldset>
		</div>
		</div>
		
	
	<input type="hidden" name="option" value="com_jem" />
	<input type="hidden" name="view" value="export" />
	<input type="hidden" name="controller" value="export" />
	<input type="hidden" name="task" value="" />
</form>