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
<script type="text/javascript">
	function tableOrdering(order, dir, view)
	{
		var form = document.getElementById("adminForm");

		form.filter_order.value 	= order;
		form.filter_order_Dir.value	= dir;
		form.submit(view);
	}
</script>

<?php if ($this->settings->get('global_show_filter') || $this->settings->get('global_display')) : ?>
	<div id="jem_filter" class="floattext">
		<?php if ($this->settings->get('global_show_filter')) : ?>
			<div class="jem_fleft">
				<?php
					echo $this->lists['filter'].'&nbsp;';
				?>
				<div class="btn-wrapper input-append">
					<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->lists['search'];?>" class="inputbox" onchange="this.form.submit();" />
					<button class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
					<button class="btn hasTooltip" type="button" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($this->settings->get('global_display')) : ?>
			<div class="jem_fright">
				<?php
					echo $this->pagination->getLimitBox();
				?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>

<table class="eventtable" style="width:<?php echo $this->jemsettings->tablewidth; ?>;" summary="jem">
	<colgroup>
			<col width="<?php echo $this->jemsettings->datewidth; ?>" class="jem_col_date" />
		<?php if ($this->jemsettings->showtitle == 1) : ?>
			<col width="<?php echo $this->jemsettings->titlewidth; ?>" class="jem_col_title" />
		<?php endif; ?>
		<?php if ($this->jemsettings->showlocate == 1) :	?>
			<col width="<?php echo $this->jemsettings->locationwidth; ?>" class="jem_col_venue" />
		<?php endif; ?>
		<?php if ($this->jemsettings->showcity == 1) :	?>
			<col width="<?php echo $this->jemsettings->citywidth; ?>" class="jem_col_city" />
		<?php endif; ?>
		<?php if ($this->jemsettings->showcat == 1) :	?>
			<col width="<?php echo $this->jemsettings->catfrowidth; ?>" class="jem_col_category" />
		<?php endif; ?>
	</colgroup>

	<thead>
		<tr>
			<th id="jem_date" class="sectiontableheader" align="left"><?php echo JHtml::_('grid.sort', 'COM_JEM_TABLE_DATE', 'a.dates', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<?php if ($this->jemsettings->showtitle == 1) : ?>
			<th id="jem_title" class="sectiontableheader" align="left"><?php echo JHtml::_('grid.sort', 'COM_JEM_TABLE_TITLE', 'a.title', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<?php endif; ?>
			<?php if ($this->jemsettings->showlocate == 1) : ?>
			<th id="jem_location" class="sectiontableheader" align="left"><?php echo JHtml::_('grid.sort', 'COM_JEM_TABLE_LOCATION', 'l.venue', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<?php endif; ?>
			<?php if ($this->jemsettings->showcity == 1) : ?>
			<th id="jem_city" class="sectiontableheader" align="left"><?php echo JHtml::_('grid.sort', 'COM_JEM_TABLE_CITY', 'l.city', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<?php endif; ?>
			<?php if ($this->jemsettings->showcat == 1) : ?>
			<th id="jem_category" class="sectiontableheader" align="left"><?php echo JHtml::_('grid.sort', 'COM_JEM_TABLE_CATEGORY', 'c.catname', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			<?php endif; ?>
		</tr>
	</thead>

	<tbody>
	<?php if ($this->noevents == 1) : ?>
		<tr align="center"><td colspan="20"><?php echo JText::_('COM_JEM_NO_EVENTS'); ?></td></tr>
	<?php else : ?>
		<?php $this->rows = $this->getRows(); ?>
		<?php foreach ($this->rows as $row) : ?>
			<tr class="sectiontableentry<?php echo ($row->odd +1) . $this->params->get('pageclass_sfx'); ?>"
				itemscope="itemscope" itemtype="http://schema.org/Event">

				<td class="jem_date" align="left">
					<?php
						echo JemOutput::formatShortDateTime($row->dates, $row->times,
							$row->enddates, $row->endtimes);
						echo JemOutput::formatSchemaOrgDateTime($row->dates, $row->times,
							$row->enddates, $row->endtimes);
					?>
				</td>

				<?php if (($this->jemsettings->showtitle == 1) && ($this->jemsettings->showdetails == 1)) : ?>
					<td class="jem_title" align="left" valign="top">
						<a href="<?php echo JRoute::_(JemHelperRoute::getEventRoute($row->slug)); ?>" itemprop="url">
							<span itemprop="name"><?php echo $this->escape($row->title); ?></span>
						</a>
					</td>
				<?php endif; ?>

				<?php if (($this->jemsettings->showtitle == 1) && ($this->jemsettings->showdetails == 0)) : ?>
					<td class="jem_title" align="left" valign="top" itemprop="name">
						<?php echo $this->escape($row->title); ?>
					</td>
				<?php endif; ?>

				<?php if ($this->jemsettings->showlocate == 1) : ?>
					<td class="jem_location" align="left" valign="top">
						<?php if ($this->jemsettings->showlinkvenue == 1) : ?>
							<?php echo $row->locid != 0 ? "<a href='".JRoute::_(JemHelperRoute::getVenueRoute($row->venueslug))."'>".$this->escape($row->venue)."</a>" : '-'; ?>
						<?php else : ?>
							<?php echo $row->locid ? $this->escape($row->venue) : '-'; ?>
						<?php endif; ?>
					</td>
				<?php endif; ?>

				<?php if ($this->jemsettings->showcity == 1) : ?>
					<td class="jem_city" align="left" valign="top"><?php echo $row->city ? $this->escape($row->city) : '-'; ?></td>
				<?php endif; ?>

				<?php if ($this->jemsettings->showcat == 1) : ?>
					<td class="jem_category" align="left" valign="top">
					<?php echo implode(", ",
							JemOutput::getCategoryList($row->categories, $this->jemsettings->catlinklist)); ?>
					</td>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
</table>