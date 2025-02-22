<?php
/**
*
* @package MoT Tour Destinations Database
* ver 1.3.0
* @copyright (c) 2022 - 2025 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace mot\tzv\includes;

class mot_tzv_functions
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\language\language $language Language object */
	protected $language;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/** @var \phpbb\user */
	protected $user;

	/** @var string phpBB phpbb root path */
	protected $root_path;

	/** @var string PHP extension */
	protected $php_ext;

	/** @var string mot.tzv.tables.tourziel */
	protected $mot_tzv_tourziel_table;

	/** @var string mot.tzv.tables.country */
	protected $mot_tzv_tourziel_country_table;

	/** @var string mot.tzv.tables.region */
	protected $mot_tzv_tourziel_region_table;

	/** @var string mot.tzv.tables.cats */
	protected $mot_tzv_tourziel_cats_table;

	/** @var string mot.tzv.tables.wlan */
	protected $mot_tzv_tourziel_wlan_table;

	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\language\language $language,
								\phpbb\notification\manager $notification_manager, \phpbb\user $user, $root_path, $php_ext,
								$mot_tzv_tourziel_table, $mot_tzv_tourziel_country_table, $mot_tzv_tourziel_region_table, $mot_tzv_tourziel_cats_table,
								$mot_tzv_tourziel_wlan_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->language = $language;
		$this->notification_manager = $notification_manager;
		$this->user = $user;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;

		// TZV Tabellen
		$this->tourziel_table = $mot_tzv_tourziel_table;
		$this->tourziel_country_table = $mot_tzv_tourziel_country_table;
		$this->tourziel_region_table = $mot_tzv_tourziel_region_table;
		$this->tourziel_cats_table = $mot_tzv_tourziel_cats_table;
		$this->tourziel_wlan_table = $mot_tzv_tourziel_wlan_table;

		$this->tzv_flags_url = $this->config['mot_tzv_flags_url'];
	}


	/*
	* Get the total number of Tourziele to display in the index and the tourziel list
	*
	* @param	string		$where	A string containing a WHERE clause for the SQL query
	*
	* @return	int				Total number of Tourziele
	*/
	function get_total_count_destinations($where = '')
	{
		$sql_ary = [
			'SELECT'	=> 'COUNT(*) AS count',
			'FROM'		=> [$this->tourziel_table	=> 't',],
			'WHERE'		=> $where,
		];
		$sql = $this->db->sql_build_query('SELECT', $sql_ary);
		$result = $this->db->sql_query($sql);
		$count = $this->db->sql_fetchfield('count');
		$this->db->sql_freeresult($result);

		return $count;
	}


	/*
	* Get the country flags and names from the MOT_TOURZIEL_COUNTRY_TABLE
	*
	* @params	none
	*
	* @return	array
	*/
	function get_country_info() : array
	{
		$sql = 'SELECT country_name, country_image FROM ' . $this->tourziel_country_table . '
				ORDER BY country_name ASC';
		$result = $this->db->sql_query($sql);
		$flags = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		foreach ($flags as &$row)
		{
			$row['country_image'] = $this->config['mot_tzv_flags_url'] . $row['country_image'];
		}

		return $flags;
	}


	/*
	* Load the content of the TOURZIEL_TABLE
	*
	* @params	int			$id		If given, the id of a single table row to load, if not given or == 0 all entries will be loaded
	*		bool/integer		$limit		Number of Tourziele to return
	*		bool/integer		$start	Number of destination to start with from the database
	*		bool			$desc		Order of searching the database, default is ASC
	*		bool			$edit		If a single Tourziel is selected, should it be edited?
	*		string			$where	A string containing a WHERE clause for the SQL query
	*
	* @return	array			Either an array containing a single destination or an array of arrays containing the rows read from the table
	*
	*/
	public function get_destinations($id = 0, $limit = false, $start = false, $desc = false, $edit = false, $where = '')
	{
		$sql_ary = [
			'SELECT'		=> 't.*, c.*, r.*, ca.*, w.*, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height',
			'FROM'			=> [$this->tourziel_table	=> 't',],
			'LEFT_JOIN'		=> [
					[
						'FROM'	=> [$this->tourziel_country_table	=> 'c'],
						'ON'	=> 'c.country_id > 0 AND c.country_id = t.country',
					],
					[
						'FROM'	=> [$this->tourziel_region_table	=> 'r'],
						'ON'	=> 'r.region_id > 0 AND r.region_id = t.region',
					],
					[
						'FROM'	=> [$this->tourziel_cats_table	=> 'ca'],
						'ON'	=> 'ca.cat_id > 0 AND ca.cat_id = t.category',
					],
					[
						'FROM'	=> [$this->tourziel_wlan_table	=> 'w'],
						'ON'	=> 'w.wlan_id > 0 AND w.wlan_id = t.wlan',
					],
					[
						'FROM'	=> [USERS_TABLE	=> 'u'],
						'ON'	=> 't.user_id > 1 AND u.user_id = t.user_id',
					],
			],
			'WHERE'			=> $id ? 't.id = ' . (int) $id : $where,
			'ORDER_BY'		=> $desc ? 't.post_time DESC' : 't.id ASC',
		];
		$sql = $this->db->sql_build_query('SELECT', $sql_ary);

		$result = ($limit === false && $start === false) ? $this->db->sql_query($sql) : $this->db->sql_query_limit($sql, $limit, $start);
		$destinations = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		foreach ($destinations as &$row)
		{
			if ($row['user_id'] > 1)		// if user_id == 1 the original user who created this Tourziel was deleted
			{
				$user = [
					'user_id'				=> $row['user_id'],
					'username'				=> $row['username'],
					'user_colour'			=> $row['user_colour'],
					'user_avatar'			=> $row['user_avatar'],
					'user_avatar'			=> $row['user_avatar'],
					'user_avatar_type'		=> $row['user_avatar_type'],
					'user_avatar_width'		=> $row['user_avatar_width'],
					'user_avatar_height'	=> $row['user_avatar_height']
				];
				$row['username'] = get_username_string('full', $user['user_id'], $user['username'], $user['user_colour']);
				$row['avatar'] = phpbb_get_user_avatar($user);
			}
			else
			{
				$row['username'] = $row['creator_username'];
				$row['avatar'] = '';
			}

			// $row['url'] = $this->detail_action . '&id=' . $row['id'];
			$row['post_time'] = !empty($row['post_time']) ? $this->user->format_date($row['post_time']) : '-';
			$row['flag'] = $row['country_image'] != '' ? $this->tzv_flags_url . $row['country_image'] : '';

			if ($edit)
			{
				decode_message($row['message'], $row['bbcode_uid']);
			}
			else
			{
				$row['message'] = generate_text_for_display($row['message'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options'],
					$row['enable_magic_url'], $row['enable_smilies'], $row['enable_bbcode']);
			}
		}

		return $destinations;
	}


	/*
	* Get the country information from the MOT_TOURZIEL_COUNTRY_TABLE and return it as an array for the select TWIG macro
	*
	* @params	integer	$value		the selected value for this field
	*		string		$choose_str		string containing the language string for 'please select'
	*
	* @return	array					an array which holds the data for a select element
	*/
	function get_country_selection($value, $choose_str) : array
	{
		// get country data
		$sql = 'SELECT country_id, country_name
				FROM ' . $this->tourziel_country_table . '
				ORDER BY country_name ASC';
		$result = $this->db->sql_query($sql);
		$countries = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		$country_arr = [$choose_str => 0];
		foreach ($countries as $row)
		{
			$country_arr = array_merge($country_arr,[$row['country_name'] => (int) $row['country_id']]);
		}

		return $this->select_struct($value, $country_arr);
	}


	/*
	* Get the region information from the MOT_TOURZIEL_REGION_TABLE and return it as an array for the select TWIG macro
	*
	* @params	integer	$value		the selected value for this field
	*		string		$choose_str		string containing the language string for 'please select'
	*
	* @return	array					an array which holds the data for a select element
	*/
	function get_region_selection($value, $choose_str) : array
	{
		// get region data
		$sql = 'SELECT *
				FROM ' . $this->tourziel_region_table . '
				ORDER BY region_name ASC';
		$result = $this->db->sql_query($sql);
		$regions = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		$region_arr = [$choose_str => 0];
		foreach ($regions as $row)
		{
			$region_arr = array_merge($region_arr,[$row['region_name'] => (int) $row['region_id']]);
		}

		return $this->select_struct($value, $region_arr);
	}


	/*
	* Get the category information from the MOT_TOURZIEL_CATS_TABLE and return it as an array for the select TWIG macro
	*
	* @params	integer	$value		the selected value for this field
	*		string		$choose_str		string containing the language string for 'please select'
	*
	* @return	array					an array which holds the data for a select element
	*/
	function get_category_selection($value, $choose_str) : array
	{
		// get category data
		$sql = 'SELECT *
				FROM ' . $this->tourziel_cats_table . '
				ORDER BY cat_name ASC';
		$result = $this->db->sql_query($sql);
		$cats = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		$cats_arr = [$choose_str => 0];
		foreach ($cats as $row)
		{
			$cats_arr = array_merge($cats_arr,[$row['cat_name'] => (int) $row['cat_id']]);
		}

		return $this->select_struct($value, $cats_arr);
	}


	/*
	* Get the WLAN information from the MOT_TOURZIEL_WLAN_TABLE and return it as an array for the select TWIG macro
	*
	* @params	integer	$value		the selected value for this field
	*		string		$choose_str		string containing the language string for 'please select'
	*
	* @return	array					an array which holds the data for a select element
	*/
	function get_wlan_selection($value, $choose_str) : array
	{
		// get WLAN data
		$sql = 'SELECT *
				FROM ' . $this->tourziel_wlan_table . '
				ORDER BY wlan_id ASC';
		$result = $this->db->sql_query($sql);
		$wlans = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		$wlan_arr = [$choose_str => 0];
		foreach ($wlans as $row)
		{
			$wlan_arr = array_merge($wlan_arr,[$row['wlan_name'] => (int) $row['wlan_id']]);
		}

		return $this->select_struct($value, $wlan_arr);
	}


	/*
	* Create the language and value options for the selection of mandatory fields and return it as an array for the select TWIG macro
	*
	* @params	integer	$value		the selected value for this field
	*
	* @return	array					an array which holds the data for a select element
	*/
	function get_mandatory_fields_selection($value) : array
	{
		$mandatory_fields = [
			$this->language->lang('MOT_TZV_LISTEN_LAND')		=> 1,
			$this->language->lang('MOT_TZV_LISTEN_REGION')		=> 2,
			$this->language->lang('MOT_TZV_LISTEN_CATEGORY')	=> 3,
			$this->language->lang('MOT_TZV_LISTEN_PLZ')			=> 4,
			$this->language->lang('MOT_TZV_LISTEN_ORT')			=> 5,
			$this->language->lang('MOT_TZV_LISTEN_STRASSE')		=> 6,
			$this->language->lang('MOT_TZV_LISTEN_TELEFON')		=> 7,
			$this->language->lang('MOT_TZV_LISTEN_EMAIL')		=> 8,
			$this->language->lang('MOT_TZV_LISTEN_HOMEPAGE')	=> 9,
			$this->language->lang('MOT_TZV_LISTEN_GPS')			=> 10,
			$this->language->lang('MOT_TZV_LISTEN_WLAN')		=> 11,
			$this->language->lang('MOT_TZV_MESSAGE_INFO')		=> 12,
		];

		return $this->select_struct($value, $mandatory_fields);
	}


	/*
	* Stores a newly created destination in the MOT_TOURZIEL_TABLE
	*
	* @params	array		$sql_array		Array with the table columns as keys and the variables from the input form as values
	*		string		$back_link		The route back to the create main window in case a row with the destination name already exists in the table
	*
	* @return	int					In case of a successful operation the id of the stored row, nothing in case of failure (jumps to trigger_error)
	*/
	public function add_destination($sql_array, $back_link)
	{
		$sql = 'INSERT INTO ' . $this->tourziel_table . ' ' . $this->db->sql_build_array('INSERT', $sql_array);
		$this->db->sql_return_on_error(true);
		$this->db->sql_query($sql);

		if ($this->db->get_sql_error_triggered())
		{
			$sql_error = $this->db->get_sql_error_returned();
			$this->db->sql_return_on_error();
			switch ($sql_error['code'])
			{
				case 1062:
					trigger_error($this->language->lang('MOT_TZV_TOURZIEL_INVALID') . $this->tzv_back_link($back_link, $this->language->lang('MOT_TZV_MAIN_ADD')), E_USER_WARNING);
					break;
				default:
					$err_msg = $sql_error['code'] . ': ' . $sql_error['message'];
					trigger_error($this->language->lang('MOT_TZV_GENERAL_ERROR', $err_msg) . $this->tzv_back_link($back_link, $this->language->lang('MOT_TZV_MAIN_ADD')), E_USER_WARNING);
					break;
			}
		}
		else
		{
			$this->db->sql_return_on_error();
			return $this->db->sql_nextid();
		}
	}


	// Edit item
	public function edit_destination($id, $sql_array)
	{
		$sql = 'UPDATE ' . $this->tourziel_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_array) . ' WHERE id = ' . (int) $id;
		$this->db->sql_query($sql);
	}


	// Delete item
	public function delete_destination($id)
	{
		$sql = 'DELETE FROM ' . $this->tourziel_table . ' WHERE id = ' . (int) $id;
		$this->db->sql_query($sql);
	}


	/*
	* Set the username of users to be deleted into the creator column of the destinations table to retain it
	*
	* @param	array		$user_ary	Array containing data of the user(s) bound to be deleted
	*/
	public function update_creator_col($user_ary)
	{
		foreach ($user_ary as $row)
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


	/*
	* Get the user ids of those users who have moderator permissions to edit or delete tour destinations
	*
	* @return	array		all respective user ids
	*/
	public function get_tzv_moderators()
	{
		// get the users supposed to get notified of a new POI
		$sql = 'SELECT user_id
				FROM  ' . USERS_TABLE . '
				WHERE ' . $this->db->sql_in_set('user_type', [USER_NORMAL, USER_FOUNDER]) . '
				ORDER BY user_id ASC';
		$result = $this->db->sql_query($sql);
		$users_total = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);
		$users_all = $tzv_mods = $tzv_mods_edit = $tzv_mods_delete = [];
		foreach ($users_total as $row)
		{
			$users_all[] = $row['user_id'];
		}
		$tzv_mods_edit = $this->auth->acl_get_list($users_all, 'm_mot_tzv_edit');
		if (!empty($tzv_mods_edit))
		{
			$tzv_mods_edit = $tzv_mods_edit[0]['m_mot_tzv_edit'];
		}
		$tzv_mods_delete = $this->auth->acl_get_list($users_all, 'm_mot_tzv_delete');
		if (!empty($tzv_mods_delete))
		{
			$tzv_mods_delete = $tzv_mods_delete[0]['m_mot_tzv_delete'];
		}
		$tzv_mods = array_replace_recursive($tzv_mods_edit, $tzv_mods_delete);

		return $tzv_mods;
	}


	/*
	* Creates a notification
	*
	* @params	string		$mode		A string which is one of ['add', 'edit', 'delete']
	*		integer	$dest_id		The destinations id in the destinations table
	*		string		$dest_name		The destinations name
	*/
	function create_notification($mode, $dest_id, $dest_name)
	{
		// get users to be notified
		$mod_users = $this->get_tzv_moderators();
		// prepare users data for the notification message
		if (!function_exists('get_username_string'))
		{
			include_once($this->root_path . 'includes/functions_content.' . $this->php_ext);
		}

		$display_username = get_username_string('no_profile', $this->user->data['user_id'], $this->user->data['username'], $this->user->data['user_colour']);
		// prepare notification data
		$notification_data = [
			'tz_id'				=> $dest_id,
			'tz_name'			=> $dest_name,
			'creator'			=> $this->user->data['username'],
			'display_username'	=> $display_username,
			'user_ids'			=> $mod_users,
			'parent'			=> 0,
		];
		$notification_data = array_merge($notification_data, ['work_mode' => 'notify']);

		switch ($mode)
		{
			case 'add':
				$this->notification_manager->add_notifications('mot.tzv.notification.type.notify_new_tz', $notification_data);
				break;

			case 'edit':
				$this->notification_manager->add_notifications('mot.tzv.notification.type.notify_tz_edited', $notification_data);
				break;

			case 'delete':
				$this->notification_manager->add_notifications('mot.tzv.notification.type.notify_tz_deleted', $notification_data);
				break;
		}
	}


	/*
	* Get the OSM tile number for a given coordinate and a given zoom factor
	* @params	float		$lat		the coordinate's latitude as decimal degree
	*		float		$lon		the coordinate's longitude as decimal degree
	*		integer	$zoom	the current zoom factor
	*
	* @return	array		array with the tile numbers in x and y direction
	*/
	private function get_tile_number($lat, $lon, $zoom)
	{
		$xtile = intval( ($lon+180)/360 * 2**$zoom );
		$ytile = intval( (1 - (log( tan(deg2rad($lat)) + 1/cos(deg2rad($lat)) )/M_PI)) * 2**($zoom-1) );

		return [
			'xtile' => $xtile,
			'ytile' => $ytile,
		];
	}


	/*
	* Get the center coordinate of a given pair of tile numbers at a given zoom factor
	* @params	integer	$xtile	the tile number in x direction
	* 		integer	$ytile	the tile number in y direction
	*		integer	$zoom	the current zoom factor
	*
	* @return	array		array with the latitude and longitude of the tile center as decimal degree
	*/
	private function get_lon_lat($xtile, $ytile, $zoom)
	{
		$n = 2 ** $zoom;
		$lon_deg = $xtile / $n * 360.0 - 180.0;
		$lat_deg = rad2deg(atan(sinh(M_PI * (1 - 2 * $ytile / $n))));

		return [
			'lon'	=> $lon_deg,
			'lat'	=> $lat_deg,
		];
	}

	/*
	* Get the coordinates of the upper left and lower right corner to define a box for a given coordinate at a given zoom factor in a defined window
	* @params	integer	$width	the window's width in pixels
	*		integer	$height	the window's height in pixels
	* 		float		$lat		the coordinate's latitude as decimal degree
	*		float		$lon		the coordinate's longitude as decimal degree
	*		integer	$zoom	the current zoom factor
	*
	* @return	array		array with the latitudes and longitudes of the map boxes upper left and lower right corner as decimal degrees
	*/
	public function lonlat_to_bbox($width, $height, $lat, $lon, $zoom)
	{
		$tile_size = 256;

		$tiles = $this->get_tile_number($lat, $lon, $zoom);

		$xtile_s = ($tiles['xtile'] * $tile_size - $width/2) / $tile_size;
		$ytile_s = ($tiles['ytile'] * $tile_size - $height/2) / $tile_size;
		$xtile_e = ($tiles['xtile'] * $tile_size + $width/2) / $tile_size;
		$ytile_e = ($tiles['ytile'] * $tile_size + $height/2) / $tile_size;

		$coord_s = $this->get_lon_lat($xtile_s, $ytile_s, $zoom);
		$coord_e = $this->get_lon_lat($xtile_e, $ytile_e, $zoom);

		return [
			'lon_s'	=> $lon - (($coord_e['lon'] - $coord_s['lon']) / 2),
			'lat_s'	=> $lat + (($coord_s['lat'] - $coord_e['lat']) / 2),
			'lon_e'	=> $lon + (($coord_e['lon'] - $coord_s['lon']) / 2),
			'lat_e'	=> $lat - (($coord_s['lat'] - $coord_e['lat']) / 2),
		];
	}


	/*
	 * Generate back link for main controller
	 * @param	$u_action
	 *		$language	language variable
	 *
	 * @return	string
	 */
	public function tzv_back_link($u_action, $lang_str)
	{
		return '<br><br><a href="' . $u_action . '">&laquo; ' . $lang_str . '</a>';
	}


	private function select_struct($cfg_value, array $options) : array
	{
		$options_tpl = [];

		foreach ($options as $opt_key => $opt_value)
		{
			if (!is_array($opt_value))
			{
				$opt_value = [$opt_value];
			}
			$options_tpl[] = [
				'label'		=> $opt_key,
				'value'		=> $opt_value[0],
				'bold'		=> $opt_value[1] ?? false,
				'selected'	=> is_array($cfg_value) ? in_array($opt_value[0], $cfg_value) : $opt_value[0] == $cfg_value,
			];
		}

		return $options_tpl;
	}
}
