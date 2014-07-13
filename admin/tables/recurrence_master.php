<?php
/**
 * @version 3.0.1
 * @package JEM
 * @copyright (C) 2013-2013 joomlaeventmanager.net
 * @copyright (C) 2005-2009 Christoph Lukes
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;


/**
 * Table: Recurrence_master
 */
class JEMTableRecurrencemaster extends JTable
{
	public function __construct(&$db) {
		parent::__construct('#__jem_recurrence_master', 'id', $db);
	}

	/**
	 * Overloaded bind method for the Event table.
	 */
	public function bind($array, $ignore = ''){
		

		return parent::bind($array, $ignore);
	}


	/**
	 * overloaded check function
	 */
	function check()
	{

		return true;
	}

	/**
	 * store method for the Event table.
	 */
	public function store($updateNulls = true)
	{

		return parent::store($updateNulls);
	}

	
}
?>