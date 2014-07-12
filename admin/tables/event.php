<?php
/**
 * @version 3.0.1
 * @package JEM
 * @copyright (C) 2013-2014 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;

/**
 * JEM Event Table
 */
class JEMTableEvent extends JTable
{
	public function __construct(&$db) {
		parent::__construct('#__jem_events', 'id', $db);
	}

	/**
	 * Overloaded bind method for the Event table.
	 */
	public function bind($array, $ignore = ''){
		// in here we are checking for the empty value of the checkbox

		if (!isset($array['registra'])) {
			$array['registra'] = 0 ;
		}

		if (!isset($array['unregistra'])) {
			$array['unregistra'] = 0 ;
		}

		if (!isset($array['waitinglist'])) {
			$array['waitinglist'] = 0 ;
		}

		// Search for the {readmore} tag and split the text up accordingly.
		if (isset($array['articletext'])) {
			$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
			$tagPos = preg_match($pattern, $array['articletext']);

			if ($tagPos == 0) {
				$this->introtext = $array['articletext'];
				$this->fulltext = '';
			} else {
				list ($this->introtext, $this->fulltext) = preg_split($pattern, $array['articletext'], 2);
			}
		}

		if (isset($array['attribs']) && is_array($array['attribs'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['attribs']);
			$array['attribs'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		// Bind the rules.
		/*
		if (isset($array['rules']) && is_array($array['rules'])) {
			$rules = new JAccessRules($array['rules']);
			$this->setRules($rules);
		}
		*/

		return parent::bind($array, $ignore);
	}


	/**
	 * overloaded check function
	 */
	function check()
	{
		$jinput = JFactory::getApplication()->input;

		if (trim($this->title) == ''){
			$this->setError(JText::_('COM_JEM_EVENT_ERROR_NAME'));
			return false;
		}

		if (trim($this->alias) == ''){
			$this->alias = $this->title;
		}

		$this->alias = JApplication::stringURLSafe($this->alias);
		if (empty($this->alias)) {
			$this->alias = JApplication::stringURLSafe($this->title);
			if (trim(str_replace('-', '', $this->alias)) == ''){
				$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
			}
		}


		if (empty($this->times)) {
			$this->times = null;
		}

		if (empty($this->endtimes)) {
			$this->endtimes = null;
		}


		// Dates
		if (empty($this->enddates)) {
			$this->enddates = null;
		}

		if (empty($this->dates)) {
			$this->dates = null;
		}

		// check startDate
		if ($this->dates == NULL) {
			$this->times = NULL;
			$this->enddates = NULL;
			$this->endtimes = NULL;
		}

		// Check begin date is before end date

		// Check if end date is set
		if($this->enddates == null) {
			// Check if end time is set
			if($this->endtimes == null) {
				$date1 = new DateTime('00:00');
				$date2 = new DateTime('00:00');
			} else {
				$date1 = new DateTime($this->times);
				$date2 = new DateTime($this->endtimes);
			}
		} else {
			// Check if end time is set
			if($this->endtimes == null) {
				$date1 = new DateTime($this->dates);
				$date2 = new DateTime($this->enddates);
			} else {
				$date1 = new DateTime($this->dates.' '.$this->times);
				$date2 = new DateTime($this->enddates.' '.$this->endtimes);
			}
		}

		if($date1 > $date2) {
			$this->setError(JText::_('COM_JEM_EVENT_ERROR_END_BEFORE_START'));
			return false;
		}

		return true;
	}

	/**
	 * Store
	 */
	public function store($updateNulls = true)
	{
		$date 			= JFactory::getDate();
		$user 			= JFactory::getUser();
		$jinput 		= JFactory::getApplication()->input;
		$app 			= JFactory::getApplication();
		$jemsettings 	= JEMHelper::config();

		// Check if we're in the front or back
		if ($app->isAdmin())
			$backend = true;
		else
			$backend = false;

		if ($this->id) {
			// Existing event
			$this->modified = $date->toSql();
			$this->modified_by = $user->get('id');
		}
		else
		{
			// New event
			if (!intval($this->created)){
				$this->created = $date->toSql();
			}
			if (empty($this->created_by)){
				$this->created_by = $user->get('id');
			}
		}

		// Check if image was selected
		jimport('joomla.filesystem.file');
		$image_dir = JPATH_SITE.'/images/jem/events/';
		$allowable = array ('gif', 'jpg', 'png');
		$image_to_delete = false;

		// get image (frontend) - allow "removal on save" (Hoffi, 2014-06-07)
		if (!$backend) {
			if (($jemsettings->imageenabled == 2 || $jemsettings->imageenabled == 1)) {
				$file = JRequest::getVar('userfile', '', 'files', 'array');
				$removeimage = JRequest::getVar('removeimage', '', 'default', 'int');

				if (!empty($file['name'])) {
					//check the image
					$check = JEMImage::check($file, $jemsettings);

					if ($check !== false) {
						//sanitize the image filename
						$filename = JemHelper::sanitize($image_dir, $file['name']);
						$filepath = $image_dir . $filename;

						if (JFile::upload($file['tmp_name'], $filepath)) {
							$image_to_delete = $this->datimage; // delete previous image
							$this->datimage = $filename;
						}
					}
				} elseif (!empty($removeimage)) {
					// if removeimage is non-zero remove image from event
					// (file will be deleted later (e.g. housekeeping) if unused)
					$image_to_delete = $this->datimage;
					$this->datimage = '';
				}
			} // end image if
		} // if (!backend)

		$format = JFile::getExt($image_dir . $this->datimage);
		if (!in_array($format, $allowable))
		{
			$this->datimage = '';
		}


		if (!$backend) {
			/*	check if the user has the required rank for autopublish	*/
			$maintainer = JEMUser::ismaintainer('publish');
			$autopubev = JEMUser::validate_user($jemsettings->evpubrec, $jemsettings->autopubl);
			if (!($autopubev || $maintainer || $user->authorise('core.edit','com_jem'))) {
				$this->published = 0 ;
			}
		}

		################
		## RECURRENCE ##
		################

		# check if recurrence_groupcheck is true
		$rec_groupcheck		= $jinput->get('recurrence_check','','int');

		if ($rec_groupcheck) {
			# the check returned true, so it's considered as an edit


			# Retrieve id of current event from recurrence_table
			# as the check was true we can skip the groupid=groupid_ref from the where statement
			# but to be sure it's added here too
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id');
			$query->from($db->quoteName('#__jem_recurrence'));
			$query->where(array('groupid = groupid_ref ', 'itemid= '.$this->id));
			$db->setQuery($query);
			$recurrenceid = $db->loadResult();

			if ($recurrenceid) {
				# Retrieve recurrence-table
				$recurrence_table	= JTable::getInstance('Recurrence', 'JEMTable');
				# Load row-data
				$recurrence_table->load($recurrenceid);

				# We want to skip this event from Ical output
				/* $recurrence_table->exdate = $this->dates.'T'.$this->times; */
				# it's a delete of the set so groupid_ref will be blanked
				/* $recurrence_table->groupid_ref = ""; */

				# it's an edit and not a delete so groupid_ref won't be adjusted
				# but we will set the recurrence_id field, as this event has been adjusted and contains
				# info that's not inline with original recurrence-info

				$var2 	= 	$recurrence_table->startdate_org;
				$var3	=	new JDate($var2);
				$var4	=	$var3->format('Ymd\THis\Z');
				$recurrence_table->recurrence_id = $var4;

				# Store fields
				$recurrence_table->store();
			}
		}

		# check if the field recurrence_group is filled and if the recurrence_type has been set
		# if the type has been set then it's part of recurrence and we should have a recurrence_group number
		if (empty($this->recurrence_group) && $this->recurrence_type) {
			$this->recurrence_group = mt_rand(0,9999);
		}

		# Define FREQ
		if ($this->recurrence_type){

			switch($this->recurrence_type) {
				case "1":
					$freq = 'DAILY';
					break;
				case "2":
					$freq = 'WEEKLY';
					break;
				case "3":
					$freq = 'MONTHLY';
					break;
				case "4":
					$freq = 'DAILY';
					break;
			}
				$this->recurrence_freq 		= $freq;
		}
		## END RECURRENCE ##

		return parent::store($updateNulls);

	}

	/**
	 * try to insert first, update if fails
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @access public
	 * @param boolean If false, null object variables are not updated
	 * @return null|string null if successful otherwise returns and error message
	 */
	function insertIgnore($updateNulls=false)
	{
		$ret = $this->_insertIgnoreObject($this->_tbl, $this, $this->_tbl_key);
		if(!$ret) {
			$this->setError(get_class($this).'::store failed - '.$this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	/**
	 * Inserts a row into a table based on an objects properties, ignore if already exists
	 *
	 * @access protected
	 * @param string  The name of the table
	 * @param object  An object whose properties match table fields
	 * @param string  The name of the primary key. If provided the object property is updated.
	 * @return int number of affected row
	 */
	protected function _insertIgnoreObject($table, &$object, $keyName = NULL)
	{
		$fmtsql = 'INSERT IGNORE INTO '.$this->_db->quoteName($table).' (%s) VALUES (%s) ';
		$fields = array();

		foreach (get_object_vars($object) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			$fields[] = $this->_db->quoteName($k);
			$values[] = $this->_db->quote($v);
		}

		$this->_db->setQuery(sprintf($fmtsql, implode(",", $fields), implode(",", $values)));
		if (!$this->_db->query()) {
			return false;
		}
		$id = $this->_db->insertid();
		if ($keyName && $id) {
			$object->$keyName = $id;
		}

		return $this->_db->getAffectedRows();
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table. The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An array of primary key values to update.  If not
	 *                            set the instance property value is used. [optional]
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published] [optional]
	 * @param   integer  $userId  The user id of the user performing the operation. [optional]
	 *
	 * @return  boolean  True on success.
	 */
	function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			} else {
				// Nothing to set publishing state on, return false.
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = '';
		} else {
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
		}

		// Update the publishing state for rows with the given primary keys.
		$query = $this->_db->getQuery(true);
		$query->update($this->_db->quoteName($this->_tbl));
		$query->set($this->_db->quoteName('published') . ' = ' . (int) $state);
		$query->where($where);
		$this->_db->setQuery($query . $checkin);
		$this->_db->query();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
			// Checkin the rows.
			foreach ($pks as $pk) {
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->published = $state;
		}

		$this->setError('');

		return true;
	}
}
?>
