<?php
/**
 * @version 3.0.1
 * @package JEM
 * @copyright (C) 2013-2014 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

$params		= $this->item->params;
$images 	= json_decode($this->item->datimage);
$canEdit	= $this->item->params->get('access-edit');
$user		= JFactory::getUser();
$attribs 	= json_decode($this->item->attribs);

JHtml::_('behavior.modal', 'a.flyermodal');
$mapType = $this->mapType;
?>
<?php if ($params->get('access-view')){?>


<div id="jem" class="event_id<?php echo $this->item->did; ?> jem_event<?php echo $this->pageclass_sfx;?>"
	itemscope="itemscope" itemtype="http://schema.org/Event">
 <div class="row-fluid">
<div class="span12 topbox">	
	
	<div class="span10">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>
	</div>
	
	<div class="span2">
<!-- Buttons -->
	<div class="btn-group pull-right hidden-phone">
		<?php 
			if ($this->print) { 
				echo JemOutput::printbutton($this->print_link, $this->params);
			} else {
				if ($this->settings->get('show_dropwdownbutton',1)) {
		?>
	
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <span class="icon-cog"></span> <span class="caret"></span> </a>
		<ul class="dropdown-menu">
			<li><?php echo JemOutput::submitbutton($this->addeventlink, $this->params); ?></li>
			<li><?php echo JemOutput::addvenuebutton($this->addvenuelink, $this->params, $this->jemsettings);?></li>
			<li><?php if ($params->get('event_show_email_icon',1)) {echo JemOutput::mailbutton($this->item->slug, 'event', $this->params);} ?></li>
			<li><?php if ($params->get('event_show_print_icon',1)) { echo JemOutput::printbutton($this->print_link, $this->params);}?></li>
		</ul>
		<?php }} ?>
	</div></div>
</div></div>
		
	<div class="clearfix"> </div>
<div class="info_container">
<!--------------------- 
		EVENT-TAB
----------------------->	
<?php //echo JHtml::_('tabs.start', 'event'.$this->item->id); ?>
<?php //echo JHtml::_('tabs.panel',JText::_('COM_JEM_EVENT_TAB'), 'event-tab' ); ?>	
	
	<!-- Event -->
	<h2 class="jem">
	<?php
		echo JText::_('COM_JEM_EVENT');
		echo JemOutput::editbutton($this->item, $params, $attribs, $this->allowedtoeditevent, 'editevent');
		?>
	</h2>
	
	<?php if ($this->img_position == 0) { ?>
		<?php if ($this->dimage) { ?>
		<div class="image">
			<?php echo JemOutput::flyer($this->item, $this->dimage, 'event'); ?>
		</div>
		<?php } ?>
	<?php } ?>

<!-- EVENT-INFO -->	
<?php if ($this->img_position == 1) { ?>
<div class="row-fluid">
	<div class="span12">
		<div class="span7">	
<?php } ?>

<div class="dl">
	<dl class="event_info">
		<?php if ($params->get('event_show_detailstitle',1)) : ?>
			<dt class="title"><?php echo JText::_('COM_JEM_TITLE').':'; ?></dt>
		<dd class="title" itemprop="name"><?php echo $this->escape($this->item->title); ?></dd>
		<?php
		endif;
		?>
		<dt class="when"><?php echo JText::_('COM_JEM_WHEN').':'; ?></dt>
		<dd class="when">
			<?php
				echo JemOutput::formatLongDateTime($this->item->dates, $this->item->times,$this->item->enddates, $this->item->endtimes);
				echo JemOutput::formatSchemaOrgDateTime($this->item->dates, $this->item->times,$this->item->enddates, $this->item->endtimes);
			?>
		</dd>
		<?php if ($this->item->locid != 0) : ?>
			<dt class="where"><?php echo JText::_('COM_JEM_WHERE').':'; ?></dt>
		<dd class="where">
				<?php if (($params->get('event_show_detlinkvenue') == 1) && (!empty($this->item->url))) : ?>
					<a target="_blank" href="<?php echo $this->item->url; ?>"><?php echo $this->escape($this->item->venue); ?></a> -
				<?php elseif ($params->get('event_show_detlinkvenue') == 2) : ?>
					<a
				href="<?php echo JRoute::_(JemHelperRoute::getVenueRoute($this->item->venueslug)); ?>"><?php echo $this->item->venue; ?></a> -
				<?php elseif ($params->get('event_show_detlinkvenue') == 0) :
					echo $this->escape($this->item->venue).' - ';
				endif;

				echo $this->escape($this->item->city).', '.$this->escape($this->item->state); ?>
			</dd>
		<?php endif;
			$n = count($this->categories);
		?>

		<dt class="category"><?php echo $n < 2 ? JText::_('COM_JEM_CATEGORY') : JText::_('COM_JEM_CATEGORIES'); ?>:</dt>
		<dd class="category">
			<?php
			$i = 0;
			foreach ($this->categories as $category) :
			?>
				<a
				href="<?php echo JRoute::_(JemHelperRoute::getCategoryRoute($category->catslug)); ?>">
					<?php echo $this->escape($category->catname); ?>
				</a>
			<?php
				$i++;
				if ($i != $n) :
					echo ', ';
				endif;
			endforeach;
			?>
		</dd>

		<?php
		for($cr = 1; $cr <= 10; $cr++) {
			$currentRow = $this->item->{'custom'.$cr};
			if(substr($currentRow, 0, 7) == "http://") {
				$currentRow = '<a href="'.$this->escape($currentRow).'" target="_blank">'.$this->escape($currentRow).'</a>';
 			}
			if($currentRow) {
		?>
				<dt class="custom<?php echo $cr; ?>"><?php echo JText::_('COM_JEM_EVENT_CUSTOM_FIELD'.$cr).':'; ?></dt>
		<dd class="custom<?php echo $cr; ?>"><?php echo $currentRow; ?></dd>
		<?php
			}
		}
		?>

		<?php if ($params->get('event_show_hits')) : ?>
		<dt class="hits"><?php echo JText::_('COM_JEM_EVENT_HITS_LABEL'); ?></dt>
		<dd class="hits"><?php echo JText::sprintf('COM_JEM_EVENT_HITS', $this->item->hits); ?></dd>
		<?php endif; ?>


<!-- AUTHOR -->
		<?php if ($params->get('event_show_author') && !empty($this->item->author)) : ?>
		<dt class="createdby"><?php echo JText::_('COM_JEM_EVENT_CREATED_BY_LABEL'); ?></dt>
		<dd class="createdby">
		<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
		<?php if (!empty($this->item->contactid2) && $params->get('event_link_author') == true): ?>
		<?php
		$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid2;
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getItems('link', $needle, true);
		$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
		?>
		<?php echo JText::sprintf('COM_JEM_EVENT_CREATED_BY', JHtml::_('link', JRoute::_($cntlink), $author)); ?>
		<?php else: ?>
		<?php echo JText::sprintf('COM_JEM_EVENT_CREATED_BY', $author); ?>
		<?php endif; ?>
		</dd>
		<?php endif; ?>
		</dl></div>
		
		<?php if ($this->img_position == 1) { ?>
		</div><div class="span5">
			<?php if ($this->dimage) { ?>
			<div class="image">
				<?php echo JemOutput::flyer($this->item, $this->dimage, 'event'); ?>
			</div>
			<?php } ?>
		</div></div></div>
		<?php } ?>


<!-- DESCRIPTION -->
		<?php if ($params->get('event_show_description','1') && ($this->item->fulltext != '' && $this->item->fulltext != '<br />' || $this->item->introtext != '' && $this->item->introtext != '<br />')) { ?>
		<h2 class="description"><?php echo JText::_('COM_JEM_EVENT_DESCRIPTION'); ?></h2>
		<div class="description event_desc" itemprop="description">

		<?php //optional teaser intro text for guests ?>
		<?php if ($params->get('event_show_noauth') == true and  $user->get('guest') ) { ?>

		<?php echo $this->item->introtext; ?>
		<?php //Optional link to let them register to see the whole event. ?>
		<?php if ($params->get('event_show_readmore') && $this->item->fulltext != null) {
		$link1 = JRoute::_('index.php?option=com_users&view=login');
		$link = new JURI($link1);?>
		<p class="readmore">
		<a href="<?php echo $link; ?>">
		<?php
		if ($params->get('event_alternative_readmore') == false) {
			echo JText::_('COM_JEM_EVENT_REGISTER_TO_READ_MORE');
			} elseif ($readmore = $params->get('alternative_readmore')) {
			echo $readmore;
			}

		if ($params->get('event_show_readmore_title', 0) != 0) {
			    echo JHtml::_('string.truncate', ($this->item->title), $params->get('event_readmore_limit'));
			} elseif ($params->get('event_show_readmore_title', 0) == 0) {
			} else {
			echo JHtml::_('string.truncate', ($this->item->title), $params->get('event_readmore_limit'));
		} ?></a>
		</p>
		<?php }
			} else {
			echo $this->item->text;
			}
		?>
		</div>
	<?php } ?>


<!--  Contact -->
       <?php if ($params->get('event_show_contact') && !empty($this->item->conid )) : ?>

        <h2 class="contact">
         			<?php echo JText::_('COM_JEM_CONTACT') ; ?>
         		</h2>

        		<dl class="dl">
        		<dt class="con_name"><?php echo JText::_('COM_JEM_NAME').':'; ?></dt>
        			<dd class="con_name">
          <?php        $contact = $this->item->conname;
        if ($params->get('event_link_contact') == true):
        $needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->conid;
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getItems('link', $needle, true);
        $cntlink2 = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
        ?>
        			<?php
			echo JText::sprintf('COM_JEM_EVENT_CONTACT', JHtml::_('link', JRoute::_($cntlink2), $contact));
 			else:
			echo JText::sprintf('COM_JEM_EVENT_CONTACT', $contact);
			endif;
 			?>
 		</dd>

 		<?php if ($this->item->contelephone) : ?>
		<dt class="con_telephone"><?php echo JText::_('COM_JEM_TELEPHONE').':'; ?></dt>
 		<dd class="con_telephone">
		<?php echo $this->escape($this->item->contelephone); ?>
 		</dd>
		<?php endif; ?>
		</dl>
        <?php endif ?>

	<?php $this->attachments = $this->item->attachments; ?>
	<?php echo $this->loadTemplate('attachments'); ?>
	<!--  	Venue  -->
	<?php if ($this->item->locid != 0) : ?>
	<p></p>
	
<!--------------------------- 
		VENUE-TAB
---------------------------->
		
<?php //echo JHtml::_('tabs.panel',JText::_('COM_JEM_VENUE_TAB'), 'venue-tab' ); ?>
		<div itemprop="location" itemscope="itemscope"
		itemtype="http://schema.org/Place">
		<h2 class="location">
			<?php
			echo JText::_('COM_JEM_VENUE') ;
			$itemid = $this->item ? $this->item->id : 0 ;
			echo JemOutput::editbutton($this->item, $params, $attribs, $this->allowedtoeditvenue, 'editvenue');
			?>
		</h2>
	
<!-- image -->
		<?php if ($this->img_position == 0) { ?>	
			<?php if ($this->limage) { ?>
			<div class="image">
				<?php echo JemOutput::flyer($this->item, $this->limage, 'venue'); ?>
			</div>
			<?php } ?>
		<?php } ?>

<!-- VENUE-INFO -->
<?php if ($this->img_position == 1) { ?>
	<div class="row-fluid">
		<div class="span12">
			<div class="span7">	
<?php } ?>
		<div class="dl">
		<dl class="location_dl">
			<dt class="venue"><?php echo JText::_('COM_JEM_LOCATION').':'; ?></dt>
			<dd class="venue">
			<?php echo "<a href='".JRoute::_(JemHelperRoute::getVenueRoute($this->item->venueslug))."'>".$this->escape($this->item->venue)."</a>"; ?>

			<?php if (!empty($this->item->url)) : ?>
				&nbsp; - &nbsp;
				<a target="_blank" href="<?php echo $this->item->url; ?>"> <?php echo JText::_('COM_JEM_WEBSITE'); ?></a>
			<?php endif; ?>
			</dd>
		</dl></div>
		<?php if ($params->get('event_show_detailsadress','1')) : ?>
		<div class="dl">
			<dl class="location_dl" itemprop="address" itemscope
			itemtype="http://schema.org/PostalAddress">
				<?php if ($this->item->street) : ?>
				<dt class="venue_street"><?php echo JText::_('COM_JEM_STREET').':'; ?></dt>
			<dd class="venue_street" itemprop="streetAddress">
					<?php echo $this->escape($this->item->street); ?>
				</dd>
				<?php endif; ?>

				<?php if ($this->item->postalCode) : ?>
				<dt class="venue_postalCode"><?php echo JText::_('COM_JEM_ZIP').':'; ?></dt>
			<dd class="venue_postalCode" itemprop="postalCode">
					<?php echo $this->escape($this->item->postalCode); ?>
				</dd>
				<?php endif; ?>

				<?php if ($this->item->city) : ?>
				<dt class="venue_city"><?php echo JText::_('COM_JEM_CITY').':'; ?></dt>
			<dd class="venue_city" itemprop="addressLocality">
					<?php echo $this->escape($this->item->city);?>
				</dd>
				<?php endif; ?>

				<?php if ($this->item->state) : ?>
				<dt class="venue_state"><?php echo JText::_('COM_JEM_STATE').':'; ?></dt>
			<dd class="venue_state" itemprop="addressRegion">
					<?php echo $this->escape($this->item->state); ?>
				</dd>
				<?php endif; ?>

				<?php if ($this->item->country) : ?>
				<dt class="venue_country"><?php echo JText::_('COM_JEM_COUNTRY').':'; ?></dt>
			<dd class="venue_country">
					<?php echo $this->item->countryimg ? $this->item->countryimg : $this->item->country; ?>
					<meta itemprop="addressCountry"
					content="<?php echo $this->item->country; ?>" />
			</dd>
				<?php endif; ?>


				<?php
		for($cr = 1; $cr <= 10; $cr++) {
			$currentRow = $this->item->{'venue'.$cr};
			if(substr($currentRow, 0, 7) == "http://") {
				$currentRow = '<a href="'.$this->escape($currentRow).'" target="_blank">'.$this->escape($currentRow).'</a>';
 			}
			if($currentRow) {
		?>
				<dt class="custom<?php echo $cr; ?>"><?php echo JText::_('COM_JEM_VENUE_CUSTOM_FIELD'.$cr).':'; ?></dt>
			<dd class="custom<?php echo $cr; ?>"><?php echo $currentRow; ?></dd>
		<?php
			}
		}
		?>

				<?php if ($params->get('event_show_mapserv')== 1) : ?>
					<?php echo JemOutput::mapicon($this->item,'event',$params); ?>
				<?php endif; ?>
			</dl></div>
			<?php if ($params->get('event_show_mapserv')== 2) : ?>
				<?php echo JemOutput::mapicon($this->item,'event',$params); ?>
			<?php endif; ?>

			<?php if ($params->get('event_show_mapserv')== 3) : ?>

			<input type="hidden" id="latitude" value="<?php echo $this->item->latitude;?>">
			<input type="hidden" id="longitude" value="<?php echo $this->item->longitude;?>">

			<input type="hidden" id="venue" value="<?php echo $this->item->venue;?>">
			<input type="hidden" id="street" value="<?php echo $this->item->street;?>">
			<input type="hidden" id="city" value="<?php echo $this->item->city;?>">
			<input type="hidden" id="state" value="<?php echo $this->item->state;?>">
			<input type="hidden" id="postalCode" value="<?php echo $this->item->postalCode;?>">
			<input type="hidden" id="mapType" value="<?php echo $this->mapType;?>">
				<?php echo JemOutput::mapicon($this->item,'event',$params); ?>
			<?php endif; ?>
		<?php endif; ?>
	
	
	<?php if ($this->img_position == 1) { ?>
	</div><div class="span5">
<!-- image -->
	<?php if ($this->limage) { ?>
		<div class="image">
		<?php echo JemOutput::flyer($this->item, $this->limage, 'venue'); ?>
	</div>
	<?php } ?>
	</div></div></div>
	<?php } ?>	
			
<!-- DESCRIPTION -->

		<?php if ($params->get('event_show_locdescription','1') && $this->item->locdescription != ''
			&& $this->item->locdescription != '<br />') : ?>

			<h2 class="location_desc"><?php echo JText::_('COM_JEM_VENUE_DESCRIPTION'); ?></h2>
		<div class="description location_desc" itemprop="description">
				<?php echo $this->item->locdescription; ?>
			</div>
		<?php endif; ?>


		<?php $this->attachments = $this->item->vattachments; ?>
	<?php echo $this->loadTemplate('attachments'); ?>

		</div>
	<?php endif; ?>

	
<!--------------------------- 
		Registration-TAB
---------------------------->	

	<?php if ($this->item->registra == 1) : ?>
		<?php //echo JHtml::_('tabs.panel',JText::_('COM_JEM_REGISTRATION_TAB'), 'registration-tab' ); ?>
	
		<h2 class="register"><?php echo JText::_('COM_JEM_REGISTRATION'); ?></h2>
			<?php echo $this->loadTemplate('attendees'); ?>
	<?php endif; ?>

	<?php //echo JHtml::_('tabs.end'); ?>
	
	</div>

<!-- call dispatcher -->
	<?php echo $this->item->pluginevent->onEventEnd; ?>

	<div id="iCal" class="iCal">
	<?php
		if ($params->get('event_show_ical_icon',1)) {
			echo JemOutput::icalbutton($this->item->slug, 'event');
		}
		?>
	</div>
	<div class="poweredby">
		<?php echo JemOutput::footer(); ?>
	</div>
</div>
<?php } ?>