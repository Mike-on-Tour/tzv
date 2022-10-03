<?php
/**
*
* @package phpBB Extension [Tour destinations]
* @copyright (c) 2014-2021 waldkatze
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace mot\tzv\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class mot_tzv_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \mot\tzv\functions\mot_tzv_events */
	protected $mot_tzv_events;

	/** @var string mot.tzv.tables.tourziel */
	protected $mot_tzv_tourziel_table;

	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db,
								\phpbb\extension\manager $phpbb_extension_manager, \phpbb\controller\helper $helper, \phpbb\template\template $template,
								\phpbb\user $user, \mot\tzv\functions\mot_tzv_events $mot_tzv_events, $mot_tzv_tourziel_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->events = $mot_tzv_events;
		// TZV Tabellen
		$this->tourziel_table = $mot_tzv_tourziel_table;

		$this->ext_path = $this->phpbb_extension_manager->get_extension_path('mot/tzv', true);

		$this->tzv_flags_url = $this->config['mot_tzv_flags_url'];
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.permissions'			=> 'permissions',
			'core.user_setup'			=> 'load_language_on_setup',
			'core.page_header'			=> 'add_page_header_link',
			'core.page_header_after'	=> 'tourziel_count',
			'core.delete_user_before'	=> 'check_for_deleted_tourziel_creator',
		];
	}

	public function permissions($event)
	{
		$permissions_cat = $event['categories'];
		$permissions_cat['tourziel'] = 'ACL_MOT_CAT_TOURZIEL';
		$event['categories'] = $permissions_cat;

		$permissions = $event['permissions'];
		$permissions['u_mot_tzv_mainview'] = ['lang' => 'ACL_U_MOT_TZV_MAINVIEW', 'cat'  => 'tourziel'];
		$permissions['u_mot_tzv_view'] = ['lang' => 'ACL_U_MOT_TZV_VIEW', 'cat'  => 'tourziel'];
		$permissions['u_mot_tzv_add'] = ['lang' => 'ACL_U_MOT_TZV_ADD', 'cat'  => 'tourziel'];
		$permissions['u_mot_tzv_edit_own'] = ['lang' => 'ACL_U_MOT_TZV_EDIT_OWN', 'cat'  => 'tourziel'];
		$permissions['u_mot_tzv_delete_own'] = ['lang' => 'ACL_U_MOT_TZV_DELETE_OWN', 'cat'  => 'tourziel'];
		$permissions['m_mot_tzv_edit'] = ['lang' => 'ACL_M_MOT_TZV_EDIT', 'cat'  => 'tourziel'];
		$permissions['m_mot_tzv_delete'] = ['lang' => 'ACL_M_MOT_TZV_DELETE', 'cat'  => 'tourziel'];
		$event['permissions'] = $permissions;
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'mot/tzv',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function add_page_header_link()
	{
		if ($this->auth->acl_get('u_mot_tzv_mainview'))
		{
			$row = $this->events->get_events(0, 1, true);

			if (!empty($row))
			{
				$row = $row[0];
				if ($row['user_id'] > 1)		// if user_id == 1 the original user who created this Tourziel was deleted
				{
					$sql = 'SELECT user_id, username, user_colour
							FROM ' . USERS_TABLE . '
							WHERE user_id = ' . (int) $row['user_id'];
					$result = $this->db->sql_query($sql);
					$user = $this->db->sql_fetchrow($result);
					$this->db->sql_freeresult($result);
					$username = get_username_string('full', $user['user_id'], $user['username'], $user['user_colour']);
				}
				else
				{
					$username = $row['creator_username'];
				}

				$this->template->assign_vars([
					'MOT_TZV_NEWS_ADD_ENABLE'	=> $this->config['mot_tzv_news_add_enable'],

					'U_MOT_TZV_NEW_EVENT'		=> $this->helper->route('mot_tzv_event', ['id' => $row['id']]),
					'MOT_TZV_NEW_EVENT'			=> $row['name'],
					'MOT_TZV_NEW_LAND'			=> $row['country_name'],
					'MOT_TZV_NEW_REGION'		=> $row['region_name'],
					'MOT_TZV_NEW_TIME'			=> (!empty($row['post_time'])) ? $this->user->format_date($row['post_time']) : '-',

					'MOT_TZV_NEWS_AUTHOR'		=> $username,

					'COUNTRY_NAME'				=> $row['country_name'],
					'COUNTRY_IMG'				=> $this->tzv_flags_url . $row['country_image'],
				]);
			}

			$this->template->assign_vars([
				'U_MOT_TOURZIEL' 			=> $this->helper->route('mot_tzv_index'),		// Link for navbar
				'MOT_TZV_SUPPORT_ENABLE'	=> $this->config['mot_tzv_support_enable'],		// General variable
				'MOT_TZV_SUPPORT'			=> $this->config['mot_tzv_support'],

				'MOT_TZV_ENABLE'			=> $this->config['mot_tzv_enable'],
				'MOT_TZV_ADMIN'				=> $this->config['mot_tzv_admin'],

			]);
		}
	}

	public function tourziel_count()
	{
		if ($this->auth->acl_get('u_mot_tzv_mainview'))
		{
			$this->template->assign_vars([
				'MOT_TZV_STATS_ENABLE'		=> $this->config['mot_tzv_stats_enable'],
				'MOT_TZV_COUNT_TOURZIEL'	=> $this->events->get_total_count_tourziele(),
			]);
		}
	}

	/**
	* Check whether a user to be deleted (no matter from where and by whomsoever) is creator of a Tourziel and change the entries in the TOURZIEL_TABLE accordingly (user_id => 1 and Creator_username => username)
	*
	* @param array	$event	containing:
	*	@var string		mode				Mode of posts deletion (retain|delete)
	*	@var array		user_ids			ID(s) of the user(s) bound to be deleted
	*	@var bool		retain_username		True if username should be retained, false otherwise
	*	@var array		user_rows			Array containing data of the user(s) bound to be deleted (since 3.2.4-RC1)
	*
	*/
	public function check_for_deleted_tourziel_creator($event)
	{
		$user_rows = $event['user_rows'];
		foreach ($user_rows as $row)
		{
			if ($row['user_id'] != 1)		// just to make sure that nobody tries to delete the guest user
			{
				$sql_array = [
					'user_id'			=> 1,
					'creator_username'	=> $row['username'],
				];
				$sql = 'UPDATE ' . $this->tourziel_table . '
						SET ' . $this->db->sql_build_array('UPDATE', $sql_array) . '
						WHERE user_id = ' . (int) $row['user_id'];
				$this->db->sql_query($sql);
			}
		}
	}
}
