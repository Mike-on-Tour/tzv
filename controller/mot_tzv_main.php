<?php
/**
*
* @package phpBB Extension [Adressverwaltung - Tourziele]
* @copyright (c) 2014-2021 waldkatze
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace mot\tzv\controller;

class mot_tzv_main
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\language\language $language Language object */
	protected $language;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/** @var \phpbb\pagination  */
	protected $pagination;

	/** @var \phpbb\path_helper  */
	protected $path_helper;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \mot\tzv\functions\mot_tzv_events */
	protected $mot_tzv_events;

	/** @var string phpBB phpbb root path */
	protected $root_path;

	/** @var string PHP extension */
	protected $php_ext;

	/** @var string mot.tzv.tables.tourziel */
	protected $mot_tzv_tourziel_table;

	/** @var string mot.tzv.tables.tourziel */
	protected $mot_tzv_tourziel_country_table;

	/** @var string mot.tzv.tables.tourziel */
	protected $mot_tzv_tourziel_region_table;

	/** @var string mot.tzv.tables.tourziel */
	protected $mot_tzv_tourziel_cats_table;

	/** @var string mot.tzv.tables.tourziel */
	protected $mot_tzv_tourziel_wlan_table;

	/**
	 * {@inheritdoc
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db,
								\phpbb\controller\helper $helper, \phpbb\language\language $language, \phpbb\notification\manager $notification_manager,
								\phpbb\pagination $pagination, \phpbb\path_helper $path_helper, \phpbb\request\request $request,
								\phpbb\template\template $template, \phpbb\user $user, \mot\tzv\functions\mot_tzv_events $mot_tzv_events, $root_path, $php_ext,
								$mot_tzv_tourziel_table, $mot_tzv_tourziel_country_table, $mot_tzv_tourziel_region_table,
								$mot_tzv_tourziel_cats_table, $mot_tzv_tourziel_wlan_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->language = $language;
		$this->notification_manager = $notification_manager;
		$this->pagination = $pagination;
		$this->path_helper = $path_helper;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->events = $mot_tzv_events;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		// TZV Tabellen
		$this->tourziel_table = $mot_tzv_tourziel_table;
		$this->tourziel_country_table = $mot_tzv_tourziel_country_table;
		$this->tourziel_region_table = $mot_tzv_tourziel_region_table;
		$this->tourziel_cats_table = $mot_tzv_tourziel_cats_table;
		$this->tourziel_wlan_table = $mot_tzv_tourziel_wlan_table;

		$this->tzv_index_route = $this->helper->route('mot_tzv_index');
		$this->tzv_create_route = $this->helper->route('mot_tzv_create');
		$this->tzv_list_route = $this->helper->route('mot_tzv_tzvlist');
		$this->tzv_map_route = $this->helper->route('mot_tzv_map');
		$this->tzv_moderate_route = $this->helper->route('mot_tzv_moderate');
		$this->tzv_search_route = $this->helper->route('mot_tzv_search');
		$this->tzv_support_route = $this->path_helper->get_web_root_path() . $this->config['mot_tzv_support']; // Support Link

		$this->tzv_flags_url = $this->config['mot_tzv_flags_url'];
	}


	/*--------------
	 TOURZIEL INDEX
	--------------*/
	public function index()
	{
		if (!$this->auth->acl_get('u_mot_tzv_mainview'))
		{
			trigger_error($this->language->lang('MOT_TZV_TOURZIEL_NO_VIEW'));
		}

		if ($this->auth->acl_get('m_mot_tzv_edit'))
		{
			$this->template->assign_var('MOT_TZV_MODERATE_LINK', $this->tzv_moderate_route);
		}

		// Get country info to display flags
		$this->events->get_country_info();

		$this->template->assign_vars([
			'MOT_TZV_TOURZIEL_NUMBER'	=> $this->language->lang('MOT_TZV_COUNT_TOTAL_DEST', $this->events->get_total_count_tourziele()),
			'MOT_TZV_COUNTRY_ENABLE'	=> $this->config['mot_tzv_country_enable'],
			'MOT_TZV_MAIN_IMAGE'		=> $this->config['mot_tzv_main_image'],
			'MOT_TZV_SHOW_GOOGLE'		=> $this->config['mot_tzv_googlemap_enable'],
			'ACP_MOT_TZV_MAININFO'		=> $this->config['mot_tzv_maininfo_enable'],
			'MOT_TZV_CREATE_AUTH'		=> $this->auth->acl_get('u_mot_tzv_add'),
			'MOT_TZV_CREATE_LINK'		=> $this->tzv_create_route,
			'MOT_TZV_LIST_AUTH'			=> $this->auth->acl_get('u_mot_tzv_view'),
			'MOT_TZV_MAP_LINK'			=> $this->tzv_map_route,
			'MOT_TZV_LIST_LINK'			=> $this->tzv_list_route,
			'MOT_TZV_SEARCH_LINK'		=> $this->tzv_search_route,
			'MOT_TZV_TZV_SUPPORT'		=> $this->tzv_support_route, // Support Link
		]);

		return $this->helper->render('mot_tzv_main_index.html', $this->language->lang('MOT_TZV_TOURZIEL'));
	}


	/*------------------
	 TOURZIEL EINTRAGEN
	------------------*/
	public function create()
	{
		if (!$this->auth->acl_get('u_mot_tzv_add'))
		{
			trigger_error($this->language->lang('MOT_TZV_TOURZIEL_NO_ADD'));
		}

		// Add the language variables for BBCodes
		$this->language->add_lang(['posting']);

		add_form_key('mot_tzv_create');

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('mot_tzv_create'))
			{
				trigger_error($this->language->lang('FORM_INVALID'), E_USER_WARNING);
			}

			if (!function_exists('generate_text_for_storage'))
			{
				include($this->root_path . 'includes/functions_content.' . $this->php_ext);
			}

			$message = utf8_normalize_nfc($this->request->variable('mot_tzv_message', '', true));
			$uid = $bitfield = $options = '';
			$allow_bbcode = $allow_urls = $allow_smilies = true;
			generate_text_for_storage($message, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

			$input_data = [
				'user_id'		    => $this->user->data['user_id'],
				'post_time'			=> time(),

				'name'			    => $this->request->variable('mot_tzv_name', '', true),
				'country'		    => $this->request->variable('mot_tzv_country', 0),
				'region'			=> $this->request->variable('mot_tzv_region', 0),
				'category'			=> $this->request->variable('mot_tzv_category', 0),
				'postalcode'	    => $this->request->variable('mot_tzv_postalcode', '', true),
				'city'			    => $this->request->variable('mot_tzv_city', '', true),
				'street'			=> $this->request->variable('mot_tzv_street', '', true),
				'telephone'			=> $this->request->variable('mot_tzv_telephone', '', true),
				'email'			    => $this->request->variable('mot_tzv_email', '', true),
				'homepage'			=> $this->request->variable('mot_tzv_homepage', '', true),
				'maps_lat'			=> $this->request->variable('mot_tzv_maps_lat', '', true),
				'maps_lon'			=> $this->request->variable('mot_tzv_maps_lon', '', true),
				'wlan'			    => $this->request->variable('mot_tzv_wlan', 0),
				'message'		    => $message,

				'bbcode_uid'        => $uid,
				'bbcode_bitfield'   => $bitfield,
				'bbcode_options'    => $options,
			];

			$new_id = $this->events->add_event($input_data, $this->tzv_create_route);

			// get users to be notified in case of a new tour destination
			$mod_users = $this->events->get_tzv_moderators();
			// prepare users data for the notification message
			if (!function_exists('get_username_string'))
			{
				include($this->root_path . 'includes/functions_content.' . $this->php_ext);
			}
			$display_username = get_username_string('no_profile', $this->user->data['user_id'], $this->user->data['username'], $this->user->data['user_colour']);
			// prepare notification data
			$notification_data = array(
				'tz_id'				=> $new_id,
				'tz_name'			=> $this->request->variable('mot_tzv_name', '', true),
				'creator'			=> $this->user->data['username'],
				'display_username'	=> $display_username,
				'user_ids'			=> $mod_users,
				'parent'			=> 0,
			);
			$notification_data = array_merge($notification_data, ['work_mode' => 'notify']);
			// notify moderators that a new POI has been created
			$this->notification_manager->add_notifications('mot.tzv.notification.type.notify_new_tz', $notification_data);

			meta_refresh(15, $this->tzv_list_route); // nach 15 Sek. zu Tourziel-Liste

			$message =  $this->language->lang('MOT_TZV_EVENT_ADD_SUCCESSFUL') . '<br><br><a href="' . $this->helper->route('mot_tzv_event', ['id' => $new_id]) . '">'. $this->language->lang('MOT_TZV_VIEW_EVENT') . '</a><br><a href="' . $this->tzv_index_route . '">'. $this->language->lang('MOT_TZV_RETURN_TOURZIEL') . '</a>';
			trigger_error($message);
		}

		if ($this->auth->acl_get('m_mot_tzv_edit'))
		{
			$this->template->assign_var('MOT_TZV_MODERATE_LINK', $this->tzv_moderate_route);
		}

		$this->events->set_country_select_values();

		$this->events->set_region_select_values();

		$this->events->set_category_select_values();

		$this->events->set_wlan_select_values();

		// Get country info to display flags
		$this->events->get_country_info();

		$this->template->assign_vars([
			'MOT_TZV_TOURZIEL_NUMBER'	=> $this->language->lang('MOT_TZV_COUNT_TOTAL_DEST', $this->events->get_total_count_tourziele()),
			'MOT_TZV_COUNTRY_ENABLE'	=> $this->config['mot_tzv_country_enable'],
			'S_CREATE_ACTION'			=> $this->tzv_create_route,
			'MOT_TZV_INDEX_AUTH'		=> $this->auth->acl_get('u_mot_tzv_mainview'),
			'MOT_TZV_INDEX_LINK'		=> $this->tzv_index_route,
			'MOT_TZV_LIST_AUTH'			=> $this->auth->acl_get('u_mot_tzv_view'),
			'MOT_TZV_MAP_LINK'			=> $this->tzv_map_route,
			'MOT_TZV_LIST_LINK'			=> $this->tzv_list_route,
			'MOT_TZV_SEARCH_LINK'		=> $this->tzv_search_route,

			'MOT_TZV_COORD_MANDATORY'	=> $this->config['mot_tzv_maps_enable'],

			'S_BBCODE_ALLOWED'			=> true,
			'S_BBCODE_IMG'				=> true,
			'S_BBCODE_FLASH'			=> true,
			'S_LINKS_ALLOWED'			=> true,
		]);

		if (!function_exists('display_custom_bbcodes'))
		{
			include($this->root_path . 'includes/functions_display.' . $this->php_ext);
		}
		display_custom_bbcodes();

		if (!function_exists('generate_smilies'))
		{
			include($this->root_path . 'includes/functions_posting.' . $this->php_ext);
		}
		generate_smilies('inline', 1);

		return $this->helper->render('mot_tzv_main_add_edit.html', $this->language->lang('MOT_TZV_TOURZIEL'));
	}


	/*------------------------------------------------
	 CONTROLLER route/tzv/event/{id} (DETAIL-Anzeige)
	------------------------------------------------*/
	public function event($id)
	{
		$event = $this->events->get_events($id);

		$flag = $this->tzv_flags_url . $event['country_image'];

		$content = '<br><table>';
		$content .= '<tr><td>' . $this->language->lang('MOT_TZV_LISTEN_NAME') . ':</td>' . '<td> &nbsp;&nbsp;<b>' . $event['name'] . '</b></td></tr>';
		$content .= '<tr><td>' . $this->language->lang('MOT_TZV_LISTEN_CATEGORY') . ':</td>' . '<td> &nbsp;&nbsp;' . $event['cat_name'] . '</td></tr>';
		$content .= '<tr><td>' . $this->language->lang('MOT_TZV_LISTEN_LAND') . ' / ' . $this->language->lang('MOT_TZV_LISTEN_REGION') . ':</td>' . '<td> &nbsp;&nbsp;<img src="' .  $flag . '" title="' . $event['country_name'] . '"> ' . $event['country_name'] . ' / ' . $event['region_name'] . '</td></tr>';
		$content .= '<tr><td>' . $this->language->lang('MOT_TZV_TOURZIEL_PLZ_ORT') . ':</td>' . '<td> &nbsp;&nbsp;' . $event['postalcode'] . '&nbsp;' . $event['city'] . '</td></tr>';
		$content .= '<tr><td>' . $this->language->lang('MOT_TZV_TOURZIEL_STRASSE_NR') . ':</td>' . '<td> &nbsp;&nbsp;' . $event['street'] . '</td></tr>';
		$content .= '<tr><td>' . $this->language->lang('MOT_TZV_LISTEN_TELEFON') . ':</td>' . '<td> &nbsp;&nbsp;' . $event['telephone'] . '</td></tr>';
		$content .= '<tr><td>' . $this->language->lang('MOT_TZV_LISTEN_EMAIL') . ':</td>' . '<td> &nbsp;&nbsp;' . '<a href=mailto:' . $event['email'] . '>' . $event['email'] . '</a>' . '</td></tr>';
		$content .= '<tr><td>' . $this->language->lang('MOT_TZV_LISTEN_HOMEPAGE') . ':</td>' . '<td> &nbsp;&nbsp;' . '<a href=' . $event['homepage'] . '>' . $event['homepage'] .  '</a>' . '</td></tr>';
		$content .= '<tr><td>' . $this->language->lang('MOT_TZV_LISTEN_WLAN') . ':</td>' . '<td> &nbsp;&nbsp;' . $event['wlan_name'] . '</td></tr>';
		if ($this->config['mot_tzv_maps_enable'])
		{
			$content .= '<tr><td><br>' . $this->language->lang('MOT_TZV_TOURZIEL_GPS_DAT') . ':</td>' . '<td><br> &nbsp;&nbsp;' . $event['maps_lat'] . '&nbsp;/&nbsp;' . $event['maps_lon'] . '</td></tr>';
		}
		$content .= '</table>';

		if ($event['message'] != null)
		{
			$content .= '<br><b>' . $this->language->lang('MOT_TZV_TOURZIEL_USER_TIPP') . ':</b></br></br>' . $event['message'];
		}

		$this->template->assign_vars([
			'POST_DATE'	=> (!empty($event['post_time'])) ? $this->user->format_date ($event['post_time']) : '-',
		]);

		$moderator = false;
		if ($this->auth->acl_get('m_mot_tzv_edit'))
		{
			$moderator = true;
			$this->template->assign_vars([
				'U_DELETE_LINK'	 => $this->helper->route('mot_tzv_manage', ['mode' => 'delete', 'id' => $id]),
				'U_EDIT_LINK'	 => $this->helper->route('mot_tzv_manage', ['mode' => 'edit', 'id' => $id]),
			]);
		}

		if ($this->user->data['user_id'] == $event['user_id'])
		{
			if ($this->auth->acl_get('u_mot_tzv_delete_own'))
			{
				$this->template->assign_var('U_DELETE_LINK', $this->helper->route('mot_tzv_manage', ['mode' => 'delete', 'id' => $id]));
			}
			if ($this->auth->acl_get('u_mot_tzv_edit_own'))
			{
				$this->template->assign_var('U_EDIT_LINK', $this->helper->route('mot_tzv_manage', ['mode' => 'edit', 'id' => $id]));
			}
		}

		// Get user data of the user who created this Tourziel
		if ($event['user_id'] > 1)
		{
			$sql = 'SELECT *
					FROM ' . USERS_TABLE . '
					WHERE user_id = ' . (int) $event['user_id'];
			$result = $this->db->sql_query($sql);
			$member = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);
			$username = get_username_string('full', $member['user_id'], $member['username'], $member['user_colour']);
			$user_avatar = phpbb_get_user_avatar($member);
		}
		else
		{
			$username = $event['creator_username'];
			$user_avatar = '';
		}

		// Get country info to display flags
		$this->events->get_country_info();

		if ($this->config['mot_tzv_ostreetmap_enable'])
		{
			$osm_bbox = $this->events->lonlat_to_bbox($this->config['mot_tzv_maps_width'], $this->config['mot_tzv_maps_height'], $event['maps_lat'], $event['maps_lon'], $this->config['mot_tzv_maps_zoom']);

			$this->template->assign_vars([
				'MOT_TZV_OSM_LON_S'		=> $osm_bbox['lon_s'],
				'MOT_TZV_OSM_LAT_S'		=> $osm_bbox['lat_s'],
				'MOT_TZV_OSM_LON_E'		=> $osm_bbox['lon_e'],
				'MOT_TZV_OSM_LAT_E'		=> $osm_bbox['lat_e'],
			]);
		}

		$this->template->assign_vars([
			'MOT_TZV_TOURZIEL_NUMBER'	=> $this->language->lang('MOT_TZV_COUNT_TOTAL_DEST', $this->events->get_total_count_tourziele()),
			'MOT_TZV_COUNTRY_ENABLE'	=> $this->config['mot_tzv_country_enable'],

			'MOT_TZV_MAPS_ENABLE'		=> $this->config['mot_tzv_maps_enable'],
			'MOT_TZV_KURVIGER_ENABLE'	=> $this->config['mot_tzv_kurviger_enable'],
			'MOT_TZV_GOOGLEMAP_ENABLE'	=> $this->config['mot_tzv_googlemap_enable'],
			'MOT_TZV_OSMMAP_ENABLE'		=> $this->config['mot_tzv_ostreetmap_enable'],

			'MOT_TZV_MAPS_WIDTH'		=> $this->config['mot_tzv_maps_width'],
			'MOT_TZV_MAPS_HEIGHT'		=> $this->config['mot_tzv_maps_height'],
			'MOT_TZV_MAPS_ZOOM'			=> $this->config['mot_tzv_maps_zoom'],

			'MOT_TZV_INDEX_AUTH'		=> $this->auth->acl_get('u_mot_tzv_mainview'),
			'MOT_TZV_INDEX_LINK'		=> $this->tzv_index_route,
			'MOT_TZV_CREATE_AUTH'		=> $this->auth->acl_get('u_mot_tzv_add'),
			'MOT_TZV_CREATE_LINK'		=> $this->tzv_create_route,
			'MOT_TZV_LIST_AUTH'			=> $this->auth->acl_get('u_mot_tzv_view'),
			'MOT_TZV_MAP_LINK'			=> $this->tzv_map_route,
			'MOT_TZV_LIST_LINK'			=> $this->tzv_list_route,
			'MOT_TZV_SEARCH_LINK'		=> $this->tzv_search_route,

			'MOT_TZV_EVENT_NAME'		=> $event['name'],
			'MOT_TZV_EVENT_CONTENT'		=> $content,
			'MOT_TZV_EVENT_MAPS_LAT'	=> $event['maps_lat'],
			'MOT_TZV_EVENT_MAPS_LON'	=> $event['maps_lon'],

			'MOT_TZV_EVENT_AVATAR'		=> $user_avatar,
			'MOT_TZV_EVENT_POSTER'		=> $username,

			'S_MODERATOR'				=> $moderator,
		]);

		return $this->helper->render('mot_tzv_main_event.html', $this->language->lang('MOT_TZV_TOURZIEL'));
	}


	/*------------------
	 TOURZIEL MODERATOR
	------------------*/
	public function moderate()
	{
		if (!$this->auth->acl_get('m_mot_tzv_edit'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		// get the latest five Tourziele in descending order of creation time
		$events = $this->events->get_events(0, 5, true);

		$last_5 = "";
		foreach ($events as $row)
		{
			if ($row['user_id'] > 1)
			{
				$sql = 'SELECT user_id, username, user_colour, user_dateformat
						FROM ' . USERS_TABLE . '
						WHERE user_id = ' . (int) $row['user_id'];
				$result = $this->db->sql_query($sql);
				$member = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);
				$username = get_username_string("full", $member['user_id'], $member['username'], $member['user_colour']);
			}
			else
			{
				$username = $row['creator_username'];
			}

			$last_5 .= '<tr><td>' . $row['name'] . '</td><td>' . $username . '</td><td><a href="' . $this->helper->route('mot_tzv_event', ['id' =>  $row['id']]) . '">' . $this->language->lang('MOT_TZV_VIEW_EVENT') . '</a></td><td>' . date($this->user->data['user_dateformat'], $row['post_time']) . '</td></tr>';
		}

		// Get country info to display flags
		$this->events->get_country_info();

		$this->template->assign_vars([
			'MOT_TZV_TOURZIEL_NUMBER'	=> $this->language->lang('MOT_TZV_COUNT_TOTAL_DEST', $this->events->get_total_count_tourziele()),
			'MOT_TZV_COUNTRY_ENABLE'	=> $this->config['mot_tzv_country_enable'],

			'MOT_TZV_INDEX_AUTH'		=> $this->auth->acl_get('u_mot_tzv_mainview'),
			'MOT_TZV_INDEX_LINK'		=> $this->tzv_index_route,
			'MOT_TZV_CREATE_AUTH'		=> $this->auth->acl_get('u_mot_tzv_add'),
			'MOT_TZV_CREATE_LINK'		=> $this->tzv_create_route,
			'MOT_TZV_LIST_AUTH'			=> $this->auth->acl_get('u_mot_tzv_view'),
			'MOT_TZV_MAP_LINK'			=> $this->tzv_map_route,
			'MOT_TZV_LIST_LINK'			=> $this->tzv_list_route,
			'MOT_TZV_SEARCH_LINK'		=> $this->tzv_search_route,
			'MOT_TZV_LAST_5_EVENTS'		=> $last_5,
		]);

		if (!empty($events))
		{
			$this->template->assign_var('S_HAS_EVENTS', true);
		}

		return $this->helper->render('mot_tzv_main_moderate.html', $this->language->lang('MOT_TZV_TOURZIEL'));
	}


	/*----------------------------------------
	 REGLER für: route/tzv/manage/{mode}/{id}
	----------------------------------------*/
	public function manage($mode, $id)
	{
		if ($this->auth->acl_get('m_mot_tzv_edit'))
		{
			$this->template->assign_var('MOT_TZV_MODERATE_LINK', $this->tzv_moderate_route);
		}

		$event = $this->events->get_events($id, false, false, true);

		switch ($mode)
		{
			case 'delete':
				// If the user is neither a moderator with the permission to delete nor the user 'owning' this Tourziel with the permission to delete own Tourziele we just display an error message
				if (!($this->auth->acl_get('m_mot_tzv_delete')) && !(($this->user->data['user_id'] == $event['user_id']) && $this->auth->acl_get('u_mot_tzv_delete_own')))
				{
					trigger_error($this->language->lang('NOT_AUTHORISED'));
				}

				if (confirm_box(true))
				{
					$this->events->delete_event($id);

					// get users to be notified in case of a deleted tour destination
					$mod_users = $this->events->get_tzv_moderators();
					// prepare users data for the notification message
					if (!function_exists('get_username_string'))
					{
						include($this->root_path . 'includes/functions_content.' . $this->php_ext);
					}
					$display_username = get_username_string('no_profile', $this->user->data['user_id'], $this->user->data['username'], $this->user->data['user_colour']);
					// prepare notification data
					$notification_data = array(
						'tz_id'				=> $id,
						'tz_name'			=> $this->request->variable('mot_tzv_name', '', true),
						'creator'			=> $this->user->data['username'],
						'display_username'	=> $display_username,
						'user_ids'			=> $mod_users,
						'parent'			=> 0,
					);
					$notification_data = array_merge($notification_data, ['work_mode' => 'notify']);
					// notify moderators that a new POI has been deleted
					$this->notification_manager->add_notifications('mot.tzv.notification.type.notify_tz_deleted', $notification_data);

					$message = $this->language->lang('MOT_TZV_EVENT_DELETE_SUCCESSFUL') . '<br><br><a href="' . $this->tzv_index_route . '">'. $this->language->lang('MOT_TZV_RETURN_TOURZIEL') . '</a>';
					trigger_error($message);
				}
				else
				{
					confirm_box(false, '<p>' . $this->language->lang('MOT_TZV_EVENT_DELETE_CONFIRM', $event['name']) . '</p>', build_hidden_fields([
						'id'	=> $event['id'],
					]));
				}

				// If the user decided to not delete this Tourziel we display the following message
				$message = $this->language->lang('MOT_TZV_EVENT_NOT_DELETED') . '<br><br><a href="' . $this->helper->route('mot_tzv_event', ['id' => $id]) .'">'. $this->language->lang('MOT_TZV_RETURN_EVENT') . '</a>';
				trigger_error($message);
				break;

			case 'edit':
				// If the user is neither a moderator with the permission to edit nor the user 'owning' this Tourziel with the permission to edit own Tourziele we just display an error message
				if (!($this->auth->acl_get('m_mot_tzv_edit')) && !( ($this->user->data['user_id'] == $event['user_id']) && $this->auth->acl_get('u_mot_tzv_edit_own') ) )
				{
					trigger_error('NOT_AUTHORISED');
				}

				add_form_key('mot_tzv_edit');

				if ($this->request->is_set_post('submit'))
				{
					if (!check_form_key('mot_tzv_edit'))
					{
						trigger_error($this->language->lang('FORM_INVALID'), E_USER_WARNING);
					}

					if (!function_exists('generate_text_for_storage'))
					{
						include($this->root_path . 'includes/functions_content.' . $this->php_ext);
					}

					$message = utf8_normalize_nfc($this->request->variable('mot_tzv_message', '', true));
					$uid = $bitfield = $options = '';
					$allow_bbcode = $allow_urls = $allow_smilies = true;
					generate_text_for_storage($message, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

					$input_data = [
						'post_time'			=> time(),

						'name'			    => $this->request->variable('mot_tzv_name', '', true),
						'country'		    => $this->request->variable('mot_tzv_country', 0),
						'region'			=> $this->request->variable('mot_tzv_region', 0),
						'category'			=> $this->request->variable('mot_tzv_category', 0),
						'postalcode'	    => $this->request->variable('mot_tzv_postalcode', '', true),
						'city'			    => $this->request->variable('mot_tzv_city', '', true),
						'street'			=> $this->request->variable('mot_tzv_street', '', true),
						'telephone'			=> $this->request->variable('mot_tzv_telephone', '', true),
						'email'			    => $this->request->variable('mot_tzv_email', '', true),
						'homepage'			=> $this->request->variable('mot_tzv_homepage', '', true),
						'maps_lat'			=> $this->request->variable('mot_tzv_maps_lat', '', true),
						'maps_lon'			=> $this->request->variable('mot_tzv_maps_lon', '', true),
						'wlan'			    => $this->request->variable('mot_tzv_wlan', 0),
						'message'		    => $message,

						'bbcode_uid'        => $uid,
						'bbcode_bitfield'   => $bitfield,
						'bbcode_options'    => $options,
					];

					$this->events->edit_event($id, $input_data);

					// get users to be notified in case of a edited tour destination
					$mod_users = $this->events->get_tzv_moderators();
					// prepare users data for the notification message
					if (!function_exists('get_username_string'))
					{
						include($this->root_path . 'includes/functions_content.' . $this->php_ext);
					}
					$display_username = get_username_string('no_profile', $this->user->data['user_id'], $this->user->data['username'], $this->user->data['user_colour']);
					// prepare notification data
					$notification_data = array(
						'tz_id'				=> $id,
						'tz_name'			=> $this->request->variable('mot_tzv_name', '', true),
						'creator'			=> $this->user->data['username'],
						'display_username'	=> $display_username,
						'user_ids'			=> $mod_users,
						'parent'			=> 0,
					);
					$notification_data = array_merge($notification_data, ['work_mode' => 'notify']);
					// notify moderators that a new POI has been edited
					$this->notification_manager->add_notifications('mot.tzv.notification.type.notify_tz_edited', $notification_data);

					meta_refresh(15, $this->tzv_list_route); // nach 15 Sek. zu Tourziel-Liste

					$message =  $this->language->lang('MOT_TZV_EVENT_EDIT_SUCCESSFUL') . '<br><br><a href="' . $this->helper->route('mot_tzv_event', ['id' =>  $event['id']]) . '">' . $this->language->lang('MOT_TZV_RETURN_EVENT') . '</a><br><a href="' . $this->tzv_index_route . '">'. $this->language->lang('MOT_TZV_RETURN_TOURZIEL') . '</a>';
					trigger_error($message);
				}

				// Add the language variables for BBCodes
				$this->language->add_lang(['posting']);

				if (!function_exists('display_custom_bbcodes'))
				{
					include($this->root_path . 'includes/functions_display.' . $this->php_ext);
				}
				display_custom_bbcodes();

				if (!function_exists('generate_smilies'))
				{
					include($this->root_path . 'includes/functions_posting.' . $this->php_ext);
				}
				generate_smilies('inline', 1);

				$this->events->set_country_select_values();

				$this->events->set_region_select_values();

				$this->events->set_category_select_values();

				$this->events->set_wlan_select_values();

				// Get country info to display flags
				$this->events->get_country_info();

				$this->template->assign_vars([
					'MOT_TZV_INDEX_AUTH'		=> $this->auth->acl_get('u_mot_tzv_mainview'),
					'MOT_TZV_INDEX_LINK'		=> $this->tzv_index_route,
					'MOT_TZV_CREATE_AUTH'		=> $this->auth->acl_get('u_mot_tzv_add'),
					'MOT_TZV_CREATE_LINK'		=> $this->tzv_create_route,
					'MOT_TZV_LIST_AUTH'			=> $this->auth->acl_get('u_mot_tzv_view'),
					'MOT_TZV_MAP_LINK'			=> $this->tzv_map_route,
					'MOT_TZV_LIST_LINK'			=> $this->tzv_list_route,
					'MOT_TZV_SEARCH_LINK'		=> $this->tzv_search_route,

					'MOT_TZV_TOURZIEL_NUMBER'	=> $this->language->lang('MOT_TZV_COUNT_TOTAL_DEST', $this->events->get_total_count_tourziele()),
					'MOT_TZV_COUNTRY_ENABLE'	=> $this->config['mot_tzv_country_enable'],
					'MOT_TZV_COORD_MANDATORY'	=> $this->config['mot_tzv_maps_enable'],

					'MOT_TZV_EDIT_TZ'			=> true,

					'MOT_TZV_POST_NAME'			=> $event['name'],
					'MOT_TZV_SELECT_COUNTRY_ID'	=> $event['country'],
					'MOT_TZV_SELECT_REGION_ID'	=> $event['region'],
					'MOT_TZV_SELECT_CAT_ID'		=> $event['category'],
					'MOT_TZV_POST_PLZ'			=> $event['postalcode'],
					'MOT_TZV_POST_ORT'			=> $event['city'],
					'MOT_TZV_POST_STREET'		=> $event['street'],
					'MOT_TZV_POST_TELEPHONE'	=> $event['telephone'],
					'MOT_TZV_POST_EMAIL'		=> $event['email'],
					'MOT_TZV_POST_HOMEPAGE'		=> $event['homepage'],
					'MOT_TZV_POST_MAPS_LAT'		=> $event['maps_lat'],
					'MOT_TZV_POST_MAPS_LON'		=> $event['maps_lon'],
					'MOT_TZV_SELECT_WLAN_ID'	=> $event['wlan'],
					'MOT_TZV_MESSAGE'		    => $event['message'],

					'S_BBCODE_ALLOWED'	=> true,
					'S_BBCODE_IMG'		=> true,
					'S_BBCODE_FLASH'	=> true,
					'S_LINKS_ALLOWED'	=> true,
				]);

				return $this->helper->render('mot_tzv_main_add_edit.html', $this->language->lang('MOT_TZV_TOURZIEL'));

				break;

		}
	}


	/*--------------
	 TOURZIEL MAP
	--------------*/
	public function map()
	{
		if (!$this->auth->acl_get('u_mot_tzv_view'))
		{
			trigger_error($this->language->lang('MOT_TZV_TOURZIEL_NO_VIEW'));
		}

		if ($this->auth->acl_get('m_mot_tzv_edit'))
		{
			$this->template->assign_var('MOT_TZV_MODERATE_LINK', $this->tzv_moderate_route);
		}

		$tourziel_array = [];
		$tourziele = $this->events->get_events();
		foreach ($tourziele as $row)
		{
			if ($row['maps_lat'] != '0' && $row['maps_lon'] != '0')
			{
				// set the route to this Tourziel's detail view
				$row['url'] = $this->helper->route('mot_tzv_event', ['id' =>  $row['id']]);
				$tourziel_array[] = $row;
			}
		}

		$map_config = [
			'Lat'			=> $this->config['mot_tzv_map_lat'],
			'Lon'			=> $this->config['mot_tzv_map_lon'],
			'Zoom'			=> $this->config['mot_tzv_map_zoom'],
			'Cluster'		=> $this->config['mot_tzv_map_enable_clusters'],
		];

		// Get country info to display flags
		$this->events->get_country_info();

		$this->template->assign_vars([
			'MOT_TZV_INDEX_AUTH'		=> $this->auth->acl_get('u_mot_tzv_mainview'),
			'MOT_TZV_INDEX_LINK'		=> $this->tzv_index_route,
			'MOT_TZV_CREATE_AUTH'		=> $this->auth->acl_get('u_mot_tzv_add'),
			'MOT_TZV_CREATE_LINK'		=> $this->tzv_create_route,
			'MOT_TZV_LIST_AUTH'			=> $this->auth->acl_get('u_mot_tzv_view'),
			'MOT_TZV_LIST_LINK'			=> $this->tzv_list_route,
			'MOT_TZV_SEARCH_LINK'		=> $this->tzv_search_route,
			'MOT_TZV_TZV_SUPPORT'		=> $this->tzv_support_route, // Support Link
			'MOT_TZV_TOURZIEL_NUMBER'	=> $this->language->lang('MOT_TZV_COUNT_TOTAL_DEST', $this->events->get_total_count_tourziele()),
			'MOT_TZV_COUNTRY_ENABLE'	=> $this->config['mot_tzv_country_enable'],
			'MOT_TZV_MAPS_ENABLE'		=> $this->config['mot_tzv_maps_enable'],

			'MOT_TZV_MARKER_COUNT'		=> count($tourziel_array),

			'MOT_TZV_MAPCONFIG'			=> json_encode($map_config),
			'MOT_TZV_TOURZIELE'			=> json_encode($tourziel_array),
		]);

		return $this->helper->render('mot_tzv_main_map.html', $this->language->lang('MOT_TZV_MAIN_MAP'));
	}


	/*--------------
	 TOURZIEL LISTE
	--------------*/
	public function tzvlist()
	{
		if (!$this->auth->acl_get('u_mot_tzv_view'))
		{
			trigger_error($this->language->lang('MOT_TZV_TOURZIEL_NO_VIEW'));
		}

		if ($this->auth->acl_get('m_mot_tzv_edit'))
		{
			$this->template->assign_var('MOT_TZV_MODERATE_LINK', $this->tzv_moderate_route);
		}

		if (!function_exists('get_username_string'))
		{
			include($this->root_path . 'includes/functions_content.' . $this->php_ext);
		}

		// Get total number of Tourziele
		$total_tz = $this->events->get_total_count_tourziele();

		if ($total_tz > 0)
		{
			// set parameters for pagination
			$start = $this->request->variable('start', 0);
			$limit = $this->config['mot_tzv_rows_per_page'];

			// get the last Tourziel
			$newest_tz = $this->events->get_events(0, 1, true);

			$newest_tz = $newest_tz[0];
			if ($newest_tz['user_id'] > 1)		// if user_id == 1 the original user who created this Tourziel was deleted
			{
				$sql = 'SELECT user_id, username, user_colour, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height
						FROM ' . USERS_TABLE . '
						WHERE user_id = ' . (int) $newest_tz['user_id'];
				$result = $this->db->sql_query($sql);
				$user = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);
				$username = get_username_string('full', $user['user_id'], $user['username'], $user['user_colour']);
				$user_avatar = phpbb_get_user_avatar($user);
			}
			else
			{
				$username = $newest_tz['creator_username'];
				$user_avatar = '';
			}

			$this->template->assign_vars([
				'NEWEST_LISTEN_URL'			=> $this->helper->route('mot_tzv_event', ['id' =>  $newest_tz['id']]),
				'NEWEST_LISTEN_ID'			=> $newest_tz['id'],
				'NEWEST_LISTEN_NAME'		=> $newest_tz['name'],
				'NEWEST_LISTEN_CATEGORY'	=> $newest_tz['cat_name'],
				'NEWEST_LISTEN_REGION'		=> $newest_tz['region_name'],
				'NEWEST_LISTEN_PLZ'			=> $newest_tz['postalcode'],
				'NEWEST_LISTEN_ORT'			=> $newest_tz['city'],
				'NEWEST_LISTEN_STRASSE'		=> $newest_tz['street'],
				'NEWEST_LISTEN_TELEFON'		=> $newest_tz['telephone'],
				'NEWEST_LISTEN_EMAIL'		=> $newest_tz['email'],
				'NEWEST_LISTEN_HOMEPAGE'	=> $newest_tz['homepage'],
				'NEWEST_LISTEN_MAP_LAT'		=> $newest_tz['maps_lat'],
				'NEWEST_LISTEN_MAP_LON'		=> $newest_tz['maps_lon'],
				'NEWEST_LISTEN_WLAN'		=> $newest_tz['wlan_name'],

				'NEWEST_POST_DATE'			=> (!empty($newest_tz['post_time'])) ? $this->user->format_date($newest_tz['post_time']) : '-',

				'NEWEST_POSTER_AUTHOR'		=> $username,
				'NEWEST_POSTER_AVATAR'		=> $user_avatar,

				'NEWEST_COUNTRY_ID'			=> $newest_tz['country_id'],
				'NEWEST_COUNTRY_NAME'		=> $newest_tz['country_name'],
				'NEWEST_COUNTRY_IMG'		=> $this->tzv_flags_url . $newest_tz['country_image'],
			]);

			// Load all Tourziele for pagination
			$sql = 'SELECT tz.*,
					ct.country_id, ct.country_name, ct.country_image,
					rt.region_id, rt.region_name,
					kt.cat_id, kt.cat_name,
					wt.wlan_id, wt.wlan_name

					FROM ' . $this->tourziel_table . ' tz
					JOIN ' . $this->tourziel_country_table . ' ct
					ON tz.country = ct.country_id
					JOIN ' . $this->tourziel_region_table . ' rt
					ON tz.region = rt.region_id
					JOIN ' . $this->tourziel_cats_table . ' kt
					ON tz.category = kt.cat_id
					JOIN ' . $this->tourziel_wlan_table . ' wt
					ON tz.wlan = wt.wlan_id
					ORDER BY tz.id ASC';
			$result = $this->db->sql_query_limit($sql, $limit, $start);
			while ($row = $this->db->sql_fetchrow($result))
			{
				if ($row['user_id'] > 1)
				{
					$sql = 'SELECT user_id, username, user_colour, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height
							FROM ' . USERS_TABLE . '
							WHERE user_id = ' . (int) $row['user_id'];
					$user_result = $this->db->sql_query($sql);
					$user = $this->db->sql_fetchrow($user_result);
					$this->db->sql_freeresult($user_result);
					$username = get_username_string('full', $user['user_id'], $user['username'], $user['user_colour']);
					$user_avatar = phpbb_get_user_avatar($user);
				}
				else
				{
					$username = $row['creator_username'];
					$user_avatar = '';
				}

				$this->template->assign_block_vars('tzlist', [
					'LISTEN_URL'		=> $this->helper->route('mot_tzv_event', ['id' =>  $row['id']]),
					'LISTEN_ID'		    => $row['id'],
					'LISTEN_NAME'		=> $row['name'],
					'LISTEN_CATEGORY'	=> $row['cat_name'],
					'LISTEN_REGION'     => $row['region_name'],
					'LISTEN_PLZ'		=> $row['postalcode'],
					'LISTEN_ORT'		=> $row['city'],
					'LISTEN_STRASSE'	=> $row['street'],
					'LISTEN_TELEFON'	=> $row['telephone'],
					'LISTEN_EMAIL'	    => $row['email'],
					'LISTEN_HOMEPAGE'	=> $row['homepage'],
					'LISTEN_MAP_LAT'	=> $row['maps_lat'],
					'LISTEN_MAP_LON'	=> $row['maps_lon'],
					'LISTEN_WLAN'   	=> $row['wlan_name'],
					'POST_DATE'			=> (!empty($row['post_time'])) ? $this->user->format_date($row['post_time']) : '-',

					'USER_ID'		    => $row['user_id'],
					'POSTER_AUTHOR'     => $username,
					'POSTER_AVATAR'     => $user_avatar,

					'COUNTRY_ID'        => $row['country_id'],
					'COUNTRY_NAME'      => $row['country_name'],
					'COUNTRY_IMG'       => $this->tzv_flags_url . $row['country_image'],
				]);
			}
			$this->db->sql_freeresult($result);

			//base url for pagination
			$base_url = $this->tzv_list_route;

			// Load pagination
			$start = $this->pagination->validate_start($start, $limit, $total_tz);
			$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_tz, $limit, $start);
		}

		// Get country info to display flags
		$this->events->get_country_info();

		$this->template->assign_vars([
			'MOT_TZV_INDEX_AUTH'		=> $this->auth->acl_get('u_mot_tzv_mainview'),
			'MOT_TZV_INDEX_LINK'		=> $this->tzv_index_route,
			'MOT_TZV_CREATE_AUTH'		=> $this->auth->acl_get('u_mot_tzv_add'),
			'MOT_TZV_CREATE_LINK'		=> $this->tzv_create_route,
			'MOT_TZV_MAP_LINK'			=> $this->tzv_map_route,
			'MOT_TZV_SEARCH_LINK'		=> $this->tzv_search_route,
			'MOT_TZV_TOURZIEL_NUMBER'	=> $this->language->lang('MOT_TZV_COUNT_TOTAL_DEST', $this->events->get_total_count_tourziele()),
			'MOT_TZV_COUNTRY_ENABLE'	=> $this->config['mot_tzv_country_enable'],
			'MOT_TZV_MAPS_ENABLE'		=> $this->config['mot_tzv_maps_enable'],
			'MOT_TZV_LONG_NEWEST'		=> $this->config['mot_tzv_latest_tz_view'],
			'MOT_TZV_LONG_TABLE'		=> $this->config['mot_tzv_list_tz_view'],
			'MOT_TZV_TOTAL_POSTS'		=> $total_tz,
		]);

		return $this->helper->render('mot_tzv_main_list.html', $this->language->lang('MOT_TZV_TOURZIEL'));
	}


	/*--------------
	 TOURZIEL SUCHE
	--------------*/
	public function search()
	{
		if (!$this->auth->acl_get('u_mot_tzv_view'))
		{
			trigger_error($this->language->lang('MOT_TZV_TOURZIEL_NO_VIEW'));
		}

		if ($this->auth->acl_get('m_mot_tzv_edit'))
		{
			$this->template->assign_var('MOT_TZV_MODERATE_LINK', $this->tzv_moderate_route);
		}

		// set parameters for pagination
		$start = $this->request->variable('start', 0);
		$limit = $this->config['mot_tzv_rows_per_page'];

		$submit = $this->request->variable('submit', '');
		if ($submit == $this->language->lang('MOT_TZV_BUTTON_SUCHEN'))
		{
			$id	= $this->request->variable('id', 0);		// Just to have something without a leading 'OR' to start the WHERE clause with

			$country = $this->request->variable('mot_tzv_country', 0) ? " OR tz.country = " . $this->request->variable('mot_tzv_country', 0) : '';
			$region = $this->request->variable('mot_tzv_region', 0) ? " OR tz.region = " . $this->request->variable('mot_tzv_region', 0) : '';
			$category = $this->request->variable('mot_tzv_category', 0) ? " OR tz.category = " . $this->request->variable('mot_tzv_category', 0) : '';
			$name = ($this->request->variable('name', '', true)) ? " OR tz.name LIKE '%" . $this->db->sql_escape($this->request->variable('name', '', true)) . "%'" : '';
			$plz = ($this->request->variable('plz', '', true)) ? " OR tz.postalcode LIKE '%" . $this->db->sql_escape($this->request->variable('plz', '', true)) . "%'" : '';
			$ort = ($this->request->variable('ort', '', true)) ? " OR tz.city LIKE '%" . $this->db->sql_escape($this->request->variable('ort', '', true)) . "%'": '';

			// Get total number of Tourziele which meet the search criteria
			$count_sql = "SELECT COUNT(tz.id) AS 'total_tz' FROM " . $this->tourziel_table . ' tz
						WHERE id = ' . (int) $id
						. $country
						. $region
						. $category
						. $name
						. $plz
						. $ort;
			$result = $this->db->sql_query($count_sql);
			$row = $this->db->sql_fetchrow($result);
			$total_tz = $row['total_tz'];
			$this->db->sql_freeresult($result);

			$sql = 'SELECT tz.*,
					ct.country_id, ct.country_name, ct.country_image,
					rt.region_id, rt.region_name,
					kt.cat_id, kt.cat_name,
					wt.wlan_id, wt.wlan_name

					FROM ' . $this->tourziel_table . ' tz
					JOIN ' . $this->tourziel_country_table . ' ct
					ON tz.country = ct.country_id
					JOIN ' . $this->tourziel_region_table . ' rt
					ON tz.region = rt.region_id
					JOIN ' . $this->tourziel_cats_table . ' kt
					ON tz.category = kt.cat_id
					JOIN ' . $this->tourziel_wlan_table . ' wt
					ON tz.wlan = wt.wlan_id

					WHERE id = ' . (int) $id
						. $country
						. $region
						. $category
						. $name
						. $plz
						. $ort;
			$sql .= ' ORDER BY tz.name ASC';
			$result = $this->db->sql_query_limit($sql, $limit, $start);

			while ($row = $this->db->sql_fetchrow($result))
			{
				if ($row['user_id'] > 1)
				{
					$sql = 'SELECT user_id, username, user_colour, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height
							FROM ' . USERS_TABLE . '
							WHERE user_id = ' . (int) $row['user_id'];
					$user_result = $this->db->sql_query($sql);
					$user = $this->db->sql_fetchrow($user_result);
					$this->db->sql_freeresult($user_result);
					$username = get_username_string('full', $user['user_id'], $user['username'], $user['user_colour']);
					$user_avatar = phpbb_get_user_avatar($user);
				}
				else
				{
					$username = $row['creator_username'];
					$user_avatar = '';
				}

				$this->template->assign_block_vars('tzlist', [
					'LISTEN_URL'		=> $this->helper->route('mot_tzv_event', ['id' =>  $row['id']]),
					'LISTEN_ID'		    => $row['id'],
					'LISTEN_NAME'		=> $row['name'],
					'LISTEN_CATEGORY'	=> $row['cat_name'],
					'LISTEN_REGION'     => $row['region_name'],
					'LISTEN_PLZ'		=> $row['postalcode'],
					'LISTEN_ORT'		=> $row['city'],
					'LISTEN_STRASSE'	=> $row['street'],
					'LISTEN_TELEFON'	=> $row['telephone'],
					'LISTEN_EMAIL'	    => $row['email'],
					'LISTEN_HOMEPAGE'	=> $row['homepage'],
					'LISTEN_MAP_LAT'	=> $row['maps_lat'],
					'LISTEN_MAP_LON'	=> $row['maps_lon'],
					'LISTEN_WLAN'   	=> $row['wlan_name'],
					'POST_DATE'			=> (!empty($row['post_time'])) ? $this->user->format_date($row['post_time']) : '-',

					'USER_ID'		    => $row['user_id'],
					'POSTER_AUTHOR'     => $username,
					'POSTER_AVATAR'     => $user_avatar,

					'COUNTRY_ID'        => $row['country_id'],
					'COUNTRY_NAME'      => $row['country_name'],
					'COUNTRY_IMG'       => $this->tzv_flags_url . $row['country_image'],
				]);
			}
			$this->db->sql_freeresult($result);

			//base url for pagination
			$base_url = $this->helper->route('mot_tzv_search', [
				'submit' => 'Suchen',
				'mot_tzv_country'	=> $this->request->variable('mot_tzv_country', 0),
				'mot_tzv_region'	=> $this->request->variable('mot_tzv_region', 0),
				'mot_tzv_category'	=> $this->request->variable('mot_tzv_category', 0),
				'name'				=> $this->request->variable('name', '', true),
				'plz'				=> $this->request->variable('plz', '', true),
				'ort'				=> $this->request->variable('ort', '', true),
			]);

			// Load pagination
			$start = $this->pagination->validate_start($start, $limit, $total_tz);
			$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_tz, $limit, $start);

			$this->template->assign_var('MOT_TZV_TOTAL_POSTS', $total_tz);
		}

		$this->events->set_country_select_values();

		$this->events->set_region_select_values();

		$this->events->set_category_select_values();

		// Get country info to display flags
		$this->events->get_country_info();

		$this->template->assign_vars([
			'U_FORM_ACTION'				=> $this->tzv_search_route,
			'MOT_TZV_TOURZIEL_NUMBER'	=> $this->language->lang('MOT_TZV_COUNT_TOTAL_DEST', $this->events->get_total_count_tourziele()),
			'MOT_TZV_COUNTRY_ENABLE'	=> $this->config['mot_tzv_country_enable'],
			'MOT_TZV_INDEX_AUTH'		=> $this->auth->acl_get('u_mot_tzv_mainview'),
			'MOT_TZV_INDEX_LINK'		=> $this->tzv_index_route,
			'MOT_TZV_CREATE_AUTH'		=> $this->auth->acl_get('u_mot_tzv_add'),
			'MOT_TZV_CREATE_LINK'		=> $this->tzv_create_route,
			'MOT_TZV_LIST_AUTH'			=> $this->auth->acl_get('u_mot_tzv_view'),
			'MOT_TZV_MAP_LINK'			=> $this->tzv_map_route,
			'MOT_TZV_LIST_LINK'			=> $this->tzv_list_route,
			'MOT_TZV_SEARCH_ACTIVE'		=> true,
			'MOT_TZV_MAPS_ENABLE'		=> $this->config['mot_tzv_maps_enable'],
			'MOT_TZV_LONG_TABLE'		=> $this->config['mot_tzv_list_tz_view'],

			'MOT_TZV_SELECT_COUNTRY_ID'	=> $this->request->variable('mot_tzv_country', 0),
			'MOT_TZV_SELECT_REGION_ID'	=> $this->request->variable('mot_tzv_region', 0),
			'MOT_TZV_SELECT_CAT_ID'		=> $this->request->variable('mot_tzv_category', 0),
			'MOT_TZV_POST_NAME'			=> $this->request->variable('name', '', true),
			'MOT_TZV_POST_PLZ'			=> $this->request->variable('plz', '', true),
			'MOT_TZV_POST_ORT'			=> $this->request->variable('ort', '', true),
		]);

		return $this->helper->render('mot_tzv_main_search.html', $this->language->lang('MOT_TZV_TOURZIEL'));
	}

}
