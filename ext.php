<?php
/**
*
* @package phpBB Extension [Adressverwaltung - Tourziele]
* @copyright (c) 2014-2021 waldkatze
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace mot\tzv;

class ext extends \phpbb\extension\base
{
	public function is_enableable()
	{
		$phpbb_min_ver	 = '3.2.3';
		$phpbb_below_ver = '3.4.0@dev';

		$language = $this->container->get('language');
		$language->add_lang('mot_tzv_ext_enable_error', 'mot/tzv');
		$style_switch = $language->lang('MOT_TZV_TOURZIEL');

		if (!(phpbb_version_compare(PHPBB_VERSION, $phpbb_min_ver, '>=') && phpbb_version_compare(PHPBB_VERSION, $phpbb_below_ver, '<')))
		{
			trigger_error(sprintf($language->lang['MOT_TZV_ERROR_EXTENSION_NOT_ENABLE'] . '<br>' . $language->lang['MOT_TZV_ERROR_MESSAGE_PHPBB_VERSION'], $style_switch, $phpbb_min_ver, $phpbb_below_ver) . $this->get_adm_back_link(), E_USER_WARNING);
		}
		return true;
	}

	public function enable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet
				$phpbb_notifications = $this->container->get('notification_manager');
				$phpbb_notifications->enable_notifications('mot.tzv.notification.type.notify_new_tz');
				$phpbb_notifications->enable_notifications('mot.tzv.notification.type.notify_tz_edited');
				$phpbb_notifications->enable_notifications('mot.tzv.notification.type.notify_tz_deleted');

				return 'notifications';
			break;

			default:
				return parent::enable_step($old_state);
			break;
		}
	}

	public function disable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet
				$phpbb_notifications = $this->container->get('notification_manager');
				$phpbb_notifications->disable_notifications('mot.tzv.notification.type.notify_new_tz');
				$phpbb_notifications->disable_notifications('mot.tzv.notification.type.notify_tz_edited');
				$phpbb_notifications->disable_notifications('mot.tzv.notification.type.notify_tz_deleted');

				return 'notifications';
			break;

			default:
				return parent::disable_step($old_state);
			break;
		}
	}

	public function purge_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet
				try
				{
					$phpbb_notifications = $this->container->get('notification_manager');
					$phpbb_notifications->purge_notifications('mot.tzv.notification.type.notify_new_tz');
					$phpbb_notifications->purge_notifications('mot.tzv.notification.type.notify_tz_edited');
					$phpbb_notifications->purge_notifications('mot.tzv.notification.type.notify_tz_deleted');
				}
				catch (\phpbb\notification\exception $e)
				{
					// continue
				}

				return 'notifications';
			break;

			default:
				return parent::purge_step($old_state);
			break;
		}
	}

	private function get_adm_back_link()
	{
		return adm_back_link(append_sid('index.' . $this->container->getParameter('core.php_ext'), 'i=acp_extensions&amp;mode=main'));
	}
}
