<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_whosonline
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Module\Whosonline\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Helper for mod_whosonline
 *
 * @since  1.5
 */
class WhosonlineHelper
{
	/**
	 * Show online count
	 *
	 * @return  array  The number of Users and Guests online.
	 *
	 * @since   1.5
	 **/
	public static function getOnlineCount()
	{
		$db = Factory::getDbo();

		// Calculate number of guests and users
		$result	     = [];
		$user_array  = 0;
		$guest_array = 0;

		$whereCondition = Factory::getApplication()->get('shared_session', '0') ? 'IS NULL' : '= 0';

		$query = $db->getQuery(true)
			->select('guest, client_id')
			->from('#__session')
			->where('client_id ' . $whereCondition);
		$db->setQuery($query);

		try
		{
			$sessions = (array) $db->loadObjectList();
		}
		catch (\RuntimeException $e)
		{
			// Don't worry be happy
			$sessions = [];
		}

		if (count($sessions))
		{
			foreach ($sessions as $session)
			{
				// If guest increase guest count by 1
				if ($session->guest == 1)
				{
					$guest_array ++;
				}

				// If member increase member count by 1
				if ($session->guest == 0)
				{
					$user_array ++;
				}
			}
		}

		$result['user']  = $user_array;
		$result['guest'] = $guest_array;

		return $result;
	}

	/**
	 * Show online member names
	 *
	 * @param   mixed  $params  The parameters
	 *
	 * @return  array   (array) $db->loadObjectList()  The names of the online users.
	 *
	 * @since   1.5
	 **/
	public static function getOnlineUserNames($params)
	{
		$whereCondition = Factory::getApplication()->get('shared_session', '0') ? 'IS NULL' : '= 0';

		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName(array('a.username', 'a.userid', 'a.client_id')))
			->from('#__session AS a')
			->where($db->quoteName('a.userid') . ' != 0')
			->where($db->quoteName('a.client_id') . ' ' . $whereCondition)
			->group($db->quoteName(array('a.username', 'a.userid', 'a.client_id')));

		$user = Factory::getUser();

		if (!$user->authorise('core.admin') && $params->get('filter_groups', 0) == 1)
		{
			$groups = $user->getAuthorisedGroups();

			if (empty($groups))
			{
				return array();
			}

			$query->join('LEFT', '#__user_usergroup_map AS m ON m.user_id = a.userid')
				->join('LEFT', '#__usergroups AS ug ON ug.id = m.group_id')
				->where('ug.id in (' . implode(',', $groups) . ')')
				->where('ug.id <> 1');
		}

		$db->setQuery($query);

		try
		{
			return (array) $db->loadObjectList();
		}
		catch (\RuntimeException $e)
		{
			return array();
		}
	}
}
