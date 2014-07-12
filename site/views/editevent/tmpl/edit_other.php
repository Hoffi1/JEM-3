<?php
/**
 * @version 3.0.1
 * @package JEM
 * @copyright (C) 2013-2014 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;
?>
	
<!-- CUSTOM FIELDS -->
	<fieldset class="form-horizontal">
		<legend><span class="legendcolor"><?php echo JText::_('COM_JEM_EVENT_CUSTOMFIELDS_LEGEND') ?></span></legend>
			<?php foreach($this->form->getFieldset('custom') as $field): ?>
			<div class="control-group">
				<div class="control-label"><?php echo $field->label; ?></div>
				<div class="controls"><?php echo $field->input; ?></div>
			</div>
			<?php endforeach; ?>
	</fieldset>

	<!-- REGISTRATION -->
	<fieldset class="form-horizontal">
		<legend><span class="legendcolor"><?php echo JText::_('COM_JEM_EVENT_REGISTRATION_LEGEND') ?></span></legend>
		
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('registra'); ?></div> 
				<div class="controls"><?php echo $this->form->getInput('registra'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('unregistra'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('unregistra'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('maxplaces'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('maxplaces'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_JEM_BOOKED_PLACES').':';?></label></div>
				<div class="controls"><input id="event-booked" type="text"  disabled="disabled" readonly="readonly" value="<?php echo $this->item->booked; ?>"  /></div>
			</div>

			<?php if ($this->item->maxplaces): ?>
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('COM_JEM_AVAILABLE_PLACES').':';?></label></div>
				<div class="controls"><input id="event-available" type="text"  disabled="disabled" readonly="readonly" value="<?php echo ($this->item->maxplaces-$this->item->booked); ?>" /></div>
			</div>
			<?php endif; ?>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('waitinglist'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('waitinglist'); ?></div>
			</div>
	</fieldset>

	<!-- IMAGE -->
	<fieldset class="form-horizontal">
	<legend><span class="legendcolor"><?php echo JText::_('COM_JEM_IMAGE'); ?></span></legend>
		
		<?php
		if (JFactory::getUser()->authorise('core.manage', 'com_jem')) {
		?>
		<div class="control-group ">
			<div class="control-label"><?php echo $this->form->getLabel('datimage'); ?></div>
			<div class="controls">
			
			<div class="input-append">
			<?php echo $this->form->getInput('datimage'); ?>
			</div>
			</div>
		</div>
		
		<?php } else { ?>
	
		<div class="control-group ">
			<div class="control-label"><label for="userfile">
				<?php echo JText::_('COM_JEM_IMAGE'); ?>
				<small class="editlinktip hasTooltip" title="<?php echo JText::_('COM_JEM_MAX_IMAGE_FILE_SIZE').' '.$this->jemsettings->sizelimit.' kb'; ?>">
					<?php echo $this->infoimage; ?>
				</small>
			</label></div>
				
			<div class="controls"><input class="inputbox <?php echo $this->jemsettings->imageenabled == 2 ? 'required' : ''; ?>" name="userfile" id="userfile" type="file" />
			<button type="button" class="btn" onclick="document.getElementById('userfile').value = ''"><?php echo JText::_('JSEARCH_FILTER_CLEAR') ?></button>
				<?php
				if ($this->item->datimage) :
					echo JHtml::image('media/com_jem/images/publish_r.png', null, array('class' => 'btn','id' => 'userfile-remove', 'data-id' => $this->item->id, 'data-type' => 'events', 'title' => JText::_('COM_JEM_REMOVE_IMAGE')));
				endif;
				?>
			</div>
		</div>
		
		<input type="hidden" name="removeimage" id="removeimage" value="0" />
		
		<?php } ?>
		
		
		<?php
		# image output
		if ($this->item->datimage) :
		?>
		<div id="hide_image" class="edit_imageflyer center">
		<?php
			echo JemOutput::flyer( $this->item, $this->dimage, 'event','hideimage');
		?>
		</div>
		<?php
		endif;
		?>
		
	</fieldset>

<!-- Recurrence -->
		<fieldset class="form-horizontal">
		<legend><span class="legendcolor"><?php echo JText::_('COM_JEM_EDITEVENT_FIELD_RECURRENCE'); ?></span></legend>
			<div class="control-group">	
				<div class="control-label"><?php echo $this->form->getLabel('recurrence_type'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('recurrence_type'); ?></div>
			</div>
				
			<div class="control-group" id="recurrence_output">
				<div class="control-label"><label></label></div>
			</div>
				
			<div class="control-group" id="counter_row" style="display: none;">	
				<div class="control-label"><?php echo $this->form->getLabel('recurrence_limit_date'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('recurrence_limit_date'); ?></div>
			</div>
			
			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('recurrence_exdates'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('recurrence_exdates'); ?></div>
			</div>
			
<!-- Check if the're holidays -->
			<?php if ($this->item->recurrence_country_holidays) { ?>
			<div class="control-group">
				<div class="control-label"><label><?php //echo 'Exclude Holiday(s)';?></label></div>
				<div class="controls"><?php // echo JemHelper::getHolidayOptions($this->item->recurrence_country_holidays); ?></div>
			</div>
			<?php } ?>
			
				<input type="hidden" name="recurrence_interval" id="recurrence_interval" value="<?php echo $this->item->recurrence_interval;?>" />
				<input type="hidden" name="recurrence_byday" id="recurrence_byday" value="<?php echo $this->item->recurrence_byday;?>" />

			<script
			type="text/javascript">
			<!--
				var $select_output = new Array();
				$select_output[1] = "<?php
				echo JText::_('COM_JEM_OUTPUT_DAY');
				?>";
				$select_output[2] = "<?php
				echo JText::_('COM_JEM_OUTPUT_WEEK');
				?>";
				$select_output[3] = "<?php
				echo JText::_('COM_JEM_OUTPUT_MONTH');
				?>";
				$select_output[4] = "<?php
				echo JText::_('COM_JEM_OUTPUT_WEEKDAY');
				?>";

				var $weekday = new Array();
				$weekday[0] = new Array("MO","<?php echo JText::_('COM_JEM_MONDAY'); ?>");
				$weekday[1] = new Array("TU","<?php echo JText::_('COM_JEM_TUESDAY'); ?>");
				$weekday[2] = new Array("WE","<?php echo JText::_('COM_JEM_WEDNESDAY'); ?>");
				$weekday[3] = new Array("TH","<?php echo JText::_('COM_JEM_THURSDAY'); ?>");
				$weekday[4] = new Array("FR","<?php echo JText::_('COM_JEM_FRIDAY'); ?>");
				$weekday[5] = new Array("SA","<?php echo JText::_('COM_JEM_SATURDAY'); ?>");
				$weekday[6] = new Array("SU","<?php echo JText::_('COM_JEM_SUNDAY'); ?>");

				var $before_last = "<?php
				echo JText::_('COM_JEM_BEFORE_LAST');
				?>";
				var $last = "<?php
				echo JText::_('COM_JEM_LAST');
				?>";
				start_recurrencescript("jform_recurrence_type");
			-->
			</script>
		</fieldset>