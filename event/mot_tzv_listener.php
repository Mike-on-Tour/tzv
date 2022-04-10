<?php
/**
*
* @package phpBB Extension [Adressverwaltung - Tourziele]
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

	/** @var string mot.tzv.tables.tourziel */
	protected $mot_tzv_tourziel_table;

	/** @var string mot.tzv.tables.tourziel */
	protected $mot_tzv_tourziel_country_table;

	/** @var string mot.tzv.tables.tourziel */
	protected $mot_tzv_tourziel_region_table;

	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db,
								\phpbb\extension\manager $phpbb_extension_manager, \phpbb\controller\helper $helper, \phpbb\template\template $template,
								\phpbb\user $user, $mot_tzv_tourziel_table, $mot_tzv_tourziel_country_table, $mot_tzv_tourziel_region_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		// TZV Tabellen
		$this->tourziel_table = $mot_tzv_tourziel_table;
		$this->tourziel_country_table = $mot_tzv_tourziel_country_table;
		$this->tourziel_region_table = $mot_tzv_tourziel_region_table;

		$this->ext_path = $this->phpbb_extension_manager->get_extension_path('mot/tzv', true);
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.permissions'			=> 'permissions',
			'core.user_setup'			=> 'load_language_on_setup',
			'core.page_header'			=> 'add_page_header_link',
			'core.page_header_after'	=> 'tourziel_count',
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
			$sql = 'SELECT tz.id, tz.name, tz.country, tz.region, tz.post_time, tz.user_id,
					u.user_id, u.username, u.user_colour,
					ct.country_id, ct.country_name, ct.country_image,
					rt.region_id, rt.region_name

					FROM ' . $this->tourziel_table . ' tz
					JOIN ' . USERS_TABLE . ' u
					ON u.user_id = tz.user_id
					JOIN ' . $this->tourziel_country_table . ' ct
					ON tz.country = ct.country_id
					JOIN ' . $this->tourziel_region_table . ' rt
					ON tz.region = rt.region_id
					ORDER BY tz.id ASC';
			$result = $this->db->sql_query($sql);
			$tourziele = $this->db->sql_fetchrowset($result);
			$this->db->sql_freeresult($result);

			$total_tz = count($tourziele);

			if ($total_tz > 0)
			{
				$row = $tourziele[$total_tz - 1];
				$this->template->assign_vars([
					'MOT_TZV_NEWS_ADD_ENABLE'	=> $this->config['mot_tzv_news_add_enable'],
					'MOT_TZV_SUPPORT_ENABLE'	=> $this->config['mot_tzv_support_enable'],
					'MOT_TZV_SUPPORT'			=> $this->config['mot_tzv_support'],

					'U_MOT_TZV_NEW_EVENT'		=> generate_board_url() . "/app.php/tzv/tzvlist",
					'MOT_TZV_NEW_EVENT'			=> $row['name'],
					'MOT_TZV_NEW_LAND'			=> $row['country_name'],
					'MOT_TZV_NEW_REGION'		=> $row['region_name'],
					'MOT_TZV_NEW_TIME'			=> (!empty($row['post_time'])) ? $this->user->format_date($row['post_time']) : '-',

					'MOT_TZV_NEWS_AUTHOR'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),

					'COUNTRY_NAME'				=> $row['country_name'],
					'COUNTRY_IMG'				=> $this->ext_path . 'images/flag/' . $row['country_image'],
				]);
			}
			$this->template->assign_vars([
				'U_MOT_TOURZIEL' 			=> $this->helper->route('mot_tzv_index'),

				'MOT_TZV_ENABLE'			=> $this->config['mot_tzv_enable'],
				'MOT_TZV_ADMIN'				=> $this->config['mot_tzv_admin'],

			]);
		}
	}

	public function tourziel_count()
	{
		if ($this->auth->acl_get('u_mot_tzv_mainview'))
		{
			$sql = 'SELECT COUNT(*) AS count
					FROM ' . $this->tourziel_table . '
					ORDER BY name';
			$result = $this->db->sql_query($sql);
			$count  = (int) $this->db->sql_fetchfield('count');
			$this->db->sql_freeresult($result);

			$this->template->assign_vars([
				'MOT_TZV_STATS_ENABLE'		=> $this->config['mot_tzv_stats_enable'],
				'MOT_TZV_COUNT_TOURZIEL'	=> $count,
			]);
		}
	}

}
