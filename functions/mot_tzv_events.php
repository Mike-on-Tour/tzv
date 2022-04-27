<?php
/**
*
* @package phpBB Extension [Adressverwaltung - Tourziele]
* @copyright (c) 2014-2021 waldkatze
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace mot\tzv\functions;

class mot_tzv_events
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\language\language $language Language object */
	protected $language;

	/** @var \phpbb\path_helper  */
	protected $path_helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

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

	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\language\language $language,
								\phpbb\path_helper $path_helper, \phpbb\template\template $template, \phpbb\user $user, $root_path, $php_ext,
								$mot_tzv_tourziel_table, $mot_tzv_tourziel_country_table, $mot_tzv_tourziel_region_table, $mot_tzv_tourziel_cats_table,
								$mot_tzv_tourziel_wlan_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->language = $language;
		$this->path_helper = $path_helper;
		$this->template = $template;
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
	* Loads the content of the TOURZIEL_TABLE
	*
	* @params	int	$id			If given, the id of a single table row to load, if not given or == 0 all entries will be loaded
	*		bool	$limit		Number of Tourziele to return
	*		bool	$descending	Order of searching the database, default is ASC
	*		bool	$edit			If a single Tourziel is selected, should it be edited?
	*
	* @return	array		Either a single row or an array of arrays containing every row from the table
	*
	*/
	public function get_events($id = 0, $limit = false, $descending = false, $edit = false)
	{
		$events = [];
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
				ON tz.wlan = wt.wlan_id ';

		if ($id == 0)
		{
			if ($limit == false)
			{
				$sql .= 'ORDER BY post_time';
				$result = $this->db->sql_query($sql);

				while ($row = $this->db->sql_fetchrow($result))
				{
					$events[] = $row;
				}
			}
			else
			{
				if ($descending == true)
				{
					$sql .= 'ORDER BY post_time DESC';
				}
				else
				{
					$sql .= 'ORDER BY post_time ASC';
				}

				$result = $this->db->sql_query_limit($sql, $limit);

				while ($row = $this->db->sql_fetchrow($result))
				{
					$events[] = $row;
				}
			}
			$this->db->sql_freeresult($result);
			return $events;
		}
		else
		{
			$sql .= 'WHERE tz.id = ' . (int) $id;

			$result = $this->db->sql_query($sql);
			$event = $this->db->sql_fetchrow($result);

			if ($edit == true)
			{
				decode_message($event['message'], $event['bbcode_uid']);
			}
			else
			{
				$event['message'] = generate_text_for_display($event['message'], $event['bbcode_uid'], $event['bbcode_bitfield'], $event['bbcode_options'],
				$event['enable_magic_url'], $event['enable_smilies'], $event['enable_bbcode']);
			}
			$this->db->sql_freeresult($result);
			return $event;
		}
	}


	// [Erhalte Events nach Datum] (Moderator)					Unused function
	public function get_events_of_day($name)
	{
		$events = [];
		$sql_array = [
			'SELECT' => '*',
			'FROM'	 => [$this->tourziel_table => 'c'],
			'WHERE'	 => "c.name = $name",
		];

		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$events[] = $row;
		}
		return $events;
	}


	/*
	* Stores a newly created Tourziel in the MOT_TOURZIEL_TABLE
	*
	* @params	array		$sql_array		Array with the table columns as keys and the variables from the input form as values
	*		string	$back_link		The route back to the create main window in case a row with the Tourziel name already exists in the table
	*
	* @return	int					In case of a successful operation the id of the stored row, nothing in case of failure (jumps to trigger_error)
	*/
	public function add_event($sql_array, $back_link)
	{
		$sql = 'INSERT INTO ' . $this->tourziel_table . ' ' . $this->db->sql_build_array('INSERT', $sql_array);
		$this->db->sql_return_on_error(true);
		$this->db->sql_query($sql);

		if ($this->db->get_sql_error_triggered())
		{
			$sql_error = $this->db->get_sql_error_returned();
			$this->db->sql_return_on_error();
			if ($sql_error['code'] == 1062)
			{
				trigger_error($this->language->lang('MOT_TZV_TOURZIEL_INVALID') . $this->tzv_back_link($back_link, $this->language->lang('MOT_TZV_MAIN_ADD')), E_USER_WARNING);
			}
		}
		else
		{
			$this->db->sql_return_on_error();
			return $this->db->sql_nextid();
		}
	}


	// [Tourziel ändern]
	public function edit_event($id, $sql_array)
	{
		$sql = 'UPDATE ' . $this->tourziel_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_array) . ' WHERE `id` = ' . (int) $id;
		$this->db->sql_query($sql);
	}


	// [Tourziel löschen]
	public function delete_event($id)
	{
		$sql = 'DELETE FROM ' . $this->tourziel_table . ' WHERE `id` = ' . (int) $id;
		$this->db->sql_query($sql);
	}


	/*
	* Get the total number of Tourziele to display in the index and the tourziel list
	*
	* @return	int	$count	Total number of Tourziele
	*/
	function get_total_count_tourziele()
	{
		$sql = 'SELECT COUNT(*) AS count
				FROM ' . $this->tourziel_table;
		$result = $this->db->sql_query($sql);
		$count = $this->db->sql_fetchfield('count');
		$this->db->sql_freeresult($result);

		return $count;
	}


	/*
	* Get the country information from the MOT_TOURZIEL_COUNTRY_TABLE and store it in a template block variable for usage with the flag view template
	*
	*/
	function get_country_info()
	{
		$sql = 'SELECT * FROM ' . $this->tourziel_country_table . '
				ORDER BY country_name ASC';
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('country_info', [
				'COUNTRY_NAME' => $row['country_name'],
				'COUNTRY_IMG'  => $this->tzv_flags_url . $row['country_image'],
				'COUNTRY_ID'   => $row['country_id'],
			]);
		}
		$this->db->sql_freeresult($result);
	}


	/*
	* Get the country information from the MOT_TOURZIEL_COUNTRY_TABLE and store it in a template block variable for usage with the select field
	*
	*/
	function set_country_select_values()
	{
		// get country data
		$sql = 'SELECT country_id, country_name
				FROM ' . $this->tourziel_country_table . '
				ORDER BY country_name ASC';
		$result = $this->db->sql_query($sql);
		$countries = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		foreach ($countries as $row)
		{
			$this->template->assign_block_vars('country', [
				'COUNTRY_ID'	=> $row['country_id'],
				'COUNTRY_NAME'	=> $row['country_name'],
			]);
		}
	}


	/*
	* Get the region information from the MOT_TOURZIEL_REGION_TABLE and store it in a template block variable for usage with the select field
	*
	*/
	function set_region_select_values()
	{
		// get region data
		$sql = 'SELECT *
				FROM ' . $this->tourziel_region_table . '
				ORDER BY region_name ASC';
		$result = $this->db->sql_query($sql);
		$regions = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		foreach ($regions as $row)
		{
			$this->template->assign_block_vars('region', [
				'REGION_ID'		=> $row['region_id'],
				'REGION_NAME'	=> $row['region_name'],
			]);
		}
	}


	/*
	* Get the category information from the MOT_TOURZIEL_CATS_TABLE and store it in a template block variable for usage with the select field
	*
	*/
	function set_category_select_values()
	{
		// get category data
		$sql = 'SELECT *
				FROM ' . $this->tourziel_cats_table . '
				ORDER BY cat_name ASC';
		$result = $this->db->sql_query($sql);
		$cats = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		foreach ($cats as $row)
		{
			$this->template->assign_block_vars('cat', [
				'CATEGORY_ID'	=> $row['cat_id'],
				'CATEGORY_NAME'	=> $row['cat_name'],
			]);
		}
	}


	/*
	* Get the WLAN information from the MOT_TOURZIEL_WLAN_TABLE and store it in a template block variable for usage with the select field
	*
	*/
	function set_wlan_select_values()
	{
		// get WLAN data
		$sql = 'SELECT *
				FROM ' . $this->tourziel_wlan_table . '
				ORDER BY wlan_id ASC';
		$result = $this->db->sql_query($sql);
		$wlans = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		foreach ($wlans as $row)
		{
			$this->template->assign_block_vars('wlan', [
				'WLAN_ID'	=> $row['wlan_id'],
				'WLAN_NAME'	=> $row['wlan_name'],
			]);
		}
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
	* Get the user ids od those users who have moderators perissions to edit or delete tour destinations
	*
	* @retuen	array		all respective user ids
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
		$users_all = [];
		foreach ($users_total as $row)
		{
			$users_all[] = $row['user_id'];
		}
		$tzv_mods_edit = $this->auth->acl_get_list($users_all, 'm_mot_tzv_edit')[0]['m_mot_tzv_edit'];
		$tzv_mods_delete = $this->auth->acl_get_list($users_all, 'm_mot_tzv_delete')[0]['m_mot_tzv_delete'];
		$tzv_mods = array_replace_recursive($tzv_mods_edit, $tzv_mods_delete);

		// Make sure we have at least an empty array to prevent errors
		if (empty($tzv_mods))
		{
			$tzv_mods = [];
		}
		return $tzv_mods;
	}
}
