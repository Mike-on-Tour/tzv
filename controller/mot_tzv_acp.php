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

class mot_tzv_acp
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/** @var \phpbb\language\language $language Language object */
	protected $language;

	/** @var \phpbb\pagination  */
	protected $pagination;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var string PHP extension */
	protected $php_ext;

	/** @var string phpBB phpbb root path */
	protected $root_path;

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
	public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\extension\manager $phpbb_extension_manager,
								\phpbb\language\language $language, \phpbb\pagination $pagination, \phpbb\request\request_interface $request,
								\phpbb\template\template $template, $php_ext, $root_path, $mot_tzv_tourziel_table, $mot_tzv_tourziel_country_table,
								$mot_tzv_tourziel_region_table, $mot_tzv_tourziel_cats_table, $mot_tzv_tourziel_wlan_table)
	{
		$this->config = $config;
		$this->db = $db;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->language = $language;
		$this->pagination = $pagination;
		$this->request = $request;
		$this->template = $template;
		$this->phpbb_root_path = $root_path;
		$this->php_ext = $php_ext;
		// TZV Tabellen
		$this->tourziel_table         = $mot_tzv_tourziel_table;
		$this->tourziel_country_table = $mot_tzv_tourziel_country_table;
		$this->tourziel_region_table  = $mot_tzv_tourziel_region_table;
		$this->tourziel_cats_table    = $mot_tzv_tourziel_cats_table;
		$this->tourziel_wlan_table    = $mot_tzv_tourziel_wlan_table;

		$this->md_manager = $this->phpbb_extension_manager->create_extension_metadata_manager('mot/tzv');
		$this->tzv_version = $this->md_manager->get_metadata('version');
		$this->ext_path = $this->phpbb_extension_manager->get_extension_path('mot/tzv', true);

		$this->tzv_flags_url = $this->config['mot_tzv_flags_url'];
	}

	/*-------------------------------------
	Einstellungen / Schalter
	-------------------------------------*/
	public function settings()
	{
		add_form_key('acp_mot_tzv');

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('acp_mot_tzv'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$this->config->set('mot_tzv_enable', $this->request->variable('tzv_enable', 0));						// Tourziele ein/aus
			$this->config->set('mot_tzv_admin', $this->request->variable('tzv_admin', 0));							// Tourziele Testmodus Administrator

			$this->config->set('mot_tzv_map_lat', $this->request->variable('mot_tzv_map_lat', ''));					// Breitengrad des Zentrums Übersichtskarte
			$this->config->set('mot_tzv_map_lon', $this->request->variable('mot_tzv_map_lon', ''));					//Längengrad des Zentrums Übersichtskarte
			$this->config->set('mot_tzv_map_zoom', $this->request->variable('mot_tzv_map_zoom', 0));				// Zoom der Übersichtskarte
			$this->config->set('mot_tzv_map_enable_clusters', $this->request->variable('mot_tzv_map_enable_clusters', 0));

			$this->config->set('mot_tzv_maps_enable', $this->request->variable('tzv_maps_enable', 0));				// Karte in Detailansicht ein/aus
			$this->config->set('mot_tzv_kurviger_enable', $this->request->variable('tzv_kurviger_enable', 0));		// Datenübergabe an www.kurviger.de
			$this->config->set('mot_tzv_komoot_enable', $this->request->variable('tzv_komoot_enable', 0));			// Datenübergabe an www.komoot.de
			$this->config->set('mot_tzv_googlemap_enable', $this->request->variable('tzv_googlemap_enable', 0));	// Karte Googlemap
			$this->config->set('mot_tzv_ostreetmap_enable', $this->request->variable('tzv_ostreetmap_enable', 0));	// Karte Openstreetmap
			$this->config->set('mot_tzv_maps_width', $this->request->variable('tzv_maps_width', ''));				// Karte Breite
			$this->config->set('mot_tzv_maps_height', $this->request->variable('tzv_maps_height', ''));				// Karte Höhe
			$this->config->set('mot_tzv_maps_zoom', $this->request->variable('tzv_maps_zoom', 6));					// Karte Zoom

			$this->config->set('mot_tzv_stats_enable', $this->request->variable('tzv_stats_enable', 0));			// Anzeige Anzahl Tourziele im Footer
			$this->config->set('mot_tzv_news_add_enable', $this->request->variable('tzv_news_add_enable', 0));		// Neuester Eintrag im Index anzeigen
			$this->config->set('mot_tzv_country_enable', $this->request->variable('tzv_country_enable', 0));		// Anzeige Länder im TZV-Index
			$this->config->set('mot_tzv_main_image', $this->request->variable('tzv_main_image', 0));				// Anzeige Bilder im TZV-Index
			$this->config->set('mot_tzv_maininfo_enable', $this->request->variable('tzv_maininfo', 0));				// Anzeige Versionsneuigkeiten im TZV-Index
			$this->config->set('mot_tzv_support_enable', $this->request->variable('tzv_support_enable', ''));		// Link Support anzeigen
			$this->config->set('mot_tzv_support', $this->request->variable('tzv_support', ''));						// Link Support
			$this->config->set('mot_tzv_latest_tz_view', $this->request->variable('tzv_latest_tz_view', 0));
			$this->config->set('mot_tzv_list_tz_view', $this->request->variable('tzv_list_tz_view', 0));

			$this->config->set('mot_tzv_acp_rows_per_page', $this->request->variable('tzv_rows_acp', 0));
			$this->config->set('mot_tzv_rows_per_page', $this->request->variable('tzv_rows_front', 0));

			trigger_error($this->language->lang('ACP_MOT_TZV_CONFIG_SAVED') . adm_back_link($this->u_action));				// MESSAGE: Einstellung gespeichert
		}

		$this->template->assign_vars([
			'ACP_MOT_TZV_VERSION'			=> $this->language->lang('ACP_MOT_TZV_VERSION', $this->tzv_version),
			'ACP_MOT_TZV_ENABLE'			=> $this->config['mot_tzv_enable'],
			'ACP_MOT_TZV_ADMIN'				=> $this->config['mot_tzv_admin'],

			'ACP_MOT_TZV_LAT'				=> $this->config['mot_tzv_map_lat'],
			'ACP_MOT_TZV_LON'				=> $this->config['mot_tzv_map_lon'],
			'ACP_MOT_TZV_ZOOM'				=> $this->config['mot_tzv_map_zoom'],
			'ACP_MOT_TZV_MAP_CLUSTERS'		=> $this->config['mot_tzv_map_enable_clusters'],

			'ACP_MOT_TZV_MAPS_ENABLE'		=> $this->config['mot_tzv_maps_enable'],
			'ACP_MOT_TZV_KURVIGER_ENABLE'	=> $this->config['mot_tzv_kurviger_enable'],
			'ACP_MOT_TZV_KOMOOT_ENABLE'		=> $this->config['mot_tzv_komoot_enable'],
			'ACP_MOT_TZV_GOOGLEMAP_ENABLE'	=> $this->config['mot_tzv_googlemap_enable'],
			'ACP_MOT_TZV_OSMMAP_ENABLE'		=> $this->config['mot_tzv_ostreetmap_enable'],
			'ACP_MOT_TZV_MAPS_WIDTH'		=> $this->config['mot_tzv_maps_width'],
			'ACP_MOT_TZV_MAPS_HEIGHT'		=> $this->config['mot_tzv_maps_height'],
			'ACP_MOT_TZV_MAPS_ZOOM'			=> $this->config['mot_tzv_maps_zoom'],

			'ACP_MOT_TZV_STATS_ENABLE'		=> $this->config['mot_tzv_stats_enable'],
			'ACP_MOT_TZV_NEWS_ADD_ENABLE'	=> $this->config['mot_tzv_news_add_enable'],
			'ACP_MOT_TZV_COUNTRY_ENABLE'	=> $this->config['mot_tzv_country_enable'],
			'ACP_MOT_TZV_MAIN_IMAGE'		=> $this->config['mot_tzv_main_image'],
			'ACP_MOT_TZV_MAININFO'			=> $this->config['mot_tzv_maininfo_enable'],
			'ACP_MOT_TZV_SUPPORT_ENABLE'	=> $this->config['mot_tzv_support_enable'],
			'ACP_MOT_TZV_SUPPORT'			=> $this->config['mot_tzv_support'],
			'ACP_MOT_TZV_LATEST_TZ_VIEW'	=> $this->config['mot_tzv_latest_tz_view'],
			'ACP_MOT_TZV_LIST_TZ_VIEW'		=> $this->config['mot_tzv_list_tz_view'],

			'ACP_MOT_TZV_ROWS_ACP'			=> $this->config['mot_tzv_acp_rows_per_page'],
			'ACP_MOT_TZV_ROWS_FRONT'		=> $this->config['mot_tzv_rows_per_page'],

			'U_ACTION'						=> $this->u_action,
		]);
	}

	/*-------------------------------------
	Einstellungen LÄNDER
	-------------------------------------*/
	public function country()
	{
		// set parameters for pagination
		$start = $this->request->variable('start', 0);
		$limit = (int) $this->config['mot_tzv_acp_rows_per_page'];

		add_form_key('acp_mot_tzv');
		$error = [];

		$submit = $this->request->is_set_post('submit');
		$action = $this->request->variable('action', '');
		$country_id = $this->request->variable('id', 0);

		if ($this->request->is_set_post('add'))
		{
			$action = 'add';
		}

		switch ($action)
		{
			case 'delete':
				if (confirm_box(true))
				{
					$sql = 'DELETE FROM ' . $this->tourziel_country_table . '
							WHERE country_id = ' . (int) $country_id;
					$this->db->sql_query($sql);

					trigger_error($this->language->lang('ACP_MOT_TZV_MSG_DELETED') . adm_back_link($this->u_action));
				}
				else
				{
					$country_name = $this->request->variable('name', '');
					confirm_box(false, '<p>' . $this->language->lang('ACP_MOT_TZV_COUNTRY_CONFIRM_DELETE', $country_name) . '</p>', build_hidden_fields([
						'id'		=> $country_id,
						'action'	=> 'delete',
					]));
				}
				break;

			case 'edit':
				if ($submit)
				{
					if (!check_form_key('acp_mot_tzv'))
					{
						trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$country_row = [
						'country_name'	=> utf8_normalize_nfc($this->request->variable('country_name', '', true)),
						'country_image'	=> $this->request->variable('country_img', ''),
					];

					if (empty($country_row['country_name']))
					{
						$error[] = $this->language->lang('ACP_MOT_TZV_ERROR_NO_NAME');
					}

					if (!sizeof($error))
					{
						$sql = 'UPDATE ' . $this->tourziel_country_table . '
								SET ' . $this->db->sql_build_array('UPDATE', $country_row) . '
								WHERE country_id = ' . (int) $country_id;
						$this->db->sql_query($sql);

						trigger_error($this->language->lang('ACP_MOT_TZV_MSG_EDITED') . adm_back_link($this->u_action));
					}
				}

				$sql = 'SELECT country_id, country_name, country_image
						FROM ' . $this->tourziel_country_table . '
						WHERE country_id =' . (int) $country_id;
				$result = $this->db->sql_query($sql);
				$country_row = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);

				if (!$country_row)
				{
					trigger_error($this->language->lang('ACP_MOT_TZV_ERROR_NOT_EXIST') . adm_back_link($this->u_action . "&amp;id=$country_id&amp;action=$action"), E_USER_WARNING);
				}

				$this->template->assign_vars([
					'L_TITLE'			=> $this->language->lang('ACP_MOT_TZV_COUNTRY_EDIT'),
					'U_ACTION'			=> $this->u_action . "&amp;id=$country_id&amp;action=$action",
					'ERROR_MSG'			=> (sizeof($error)) ? implode('<br>', $error) : '',
					'COUNTRY_NAME'		=> $country_row['country_name'],
					'COUNTRY_IMG'		=> $country_row['country_image'],
					'COUNTRY_ID'		=> $country_row['country_id'],
					'S_ADD_COUNTRY'		=> true,
					'S_EDIT_COUNTRY'	=> true,
					'S_ERROR'			=> (sizeof($error)) ? true : false,
				]);
				break;

			case 'add':
				if (!check_form_key('acp_mot_tzv'))
				{
					trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
				}

				// Eintrag mit dem gleichen Namen vorhanden?
				$sql    = 'SELECT * FROM ' . $this->tourziel_country_table;
				$result = $this->db->sql_query($sql);

				$country_name_arry = [];

				while ($row = $this->db->sql_fetchrow($result))
				{
					$country_name_arry[] = $row['country_name'];
				}
				$this->db->sql_freeresult($result);

				$country_name_arry = implode(',', $country_name_arry);
				$country_name_arry = strtolower($country_name_arry);
				$country_name_arry = explode(',', $country_name_arry);

				if ($submit)
				{
					$country_row = [
						'country_name'	=> utf8_normalize_nfc($this->request->variable('country_name', '', true)),
						'country_image' => $this->request->variable('country_img', ''),
					];
					if (empty($country_row['country_name']))
					{
						$error[] = $this->language->lang('ACP_MOT_TZV_ERROR_NO_NAME');
					}
					// prüfe ob Name anders ist
					if (in_array(strtolower($country_row['country_name']), $country_name_arry))
					{
						$error[] = $this->language->lang('ACP_MOT_TZV_NAME_EXISTS');
					}
					// No errors detected, insert into database
					if (count($error) == 0)
					{
						$sql = 'INSERT INTO ' . $this->tourziel_country_table . ' ' . $this->db->sql_build_array('INSERT', $country_row);
						$this->db->sql_query($sql);
						trigger_error($this->language->lang('ACP_MOT_TZV_MSG_ADDED') . adm_back_link($this->u_action));
					}
				}

				$this->template->assign_vars([
					'L_TITLE'			=> $this->language->lang('ACP_MOT_TZV_COUNTRY_ADD'),
					'U_ACTION'			=> $this->u_action . "&amp;action=$action",
					'ERROR_MSG'			=> (count($error)) ? implode('<br>', $error) : '',
					'S_ADD_COUNTRY'		=> true,
					'S_EDIT_COUNTRY'	=> false,
					'S_ERROR'			=> (count($error)) ? true : false,
				]);
				break;
		}

		// Get number of countries used in Tourziele
		$sql = 'SELECT country, COUNT(*) AS count FROM ' . $this->tourziel_table . '
				GROUP BY country';
		$result = $this->db->sql_query($sql);
		$countries = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);
		$country_count = [];
		foreach ($countries as $row)
		{
			$country_count[$row['country']] = $row['count'];
		}

		// Get total number of countries for pagination
		$count_sql = "SELECT COUNT(country_id) AS total_countries FROM " . $this->tourziel_country_table;
		$result = $this->db->sql_query($count_sql);
		$row = $this->db->sql_fetchrow($result);
		$total_countries = $row['total_countries'];
		$this->db->sql_freeresult($result);

		// Load the countries
		$sql = 'SELECT * FROM ' . $this->tourziel_country_table . '
				ORDER BY country_name ASC';
		$result = $this->db->sql_query_limit($sql, $limit, $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('country', [
				'COUNTRY_NAME'	=> $row['country_name'],
				'COUNTRY_IMG'	=> $this->tzv_flags_url . $row['country_image'],
				'COUNTRY_ID'	=> $row['country_id'],
				'COUNTRY_COUNT'	=> array_key_exists($row['country_id'], $country_count) ? $country_count[$row['country_id']] : '-',
				'U_EDIT'		=> $this->u_action . "&amp;id={$row['country_id']}&amp;action=edit",
				'U_DELETE'		=> $this->u_action . "&amp;id={$row['country_id']}&amp;name={$row['country_name']}&amp;action=delete"
			]);
		}
		$this->db->sql_freeresult($result);

		//base url for pagination
		$base_url = $this->u_action;

		// Load pagination
		$start = $this->pagination->validate_start($start, $limit, $total_countries);
		$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_countries, $limit, $start);

		$this->template->assign_vars([
			'S_COUNTRY'	=> true,
			'U_BACK'	=> $this->u_action,
		]);
	}

	/*-------------------------------------
	Einstellungen REGIONEN
	-------------------------------------*/
	public function region()
	{
		// set parameters for pagination
		$start = $this->request->variable('start', 0);
		$limit = (int) $this->config['mot_tzv_acp_rows_per_page'];

		add_form_key('acp_mot_tzv');
		$error = [];

		$submit = $this->request->is_set_post('submit');
		$action = $this->request->variable('action', '');
		$region_id = $this->request->variable('id', 0);

		if ($this->request->is_set_post('add'))
		{
			$action = 'add';
		}

		switch ($action)
		{
			case 'delete':
				if (confirm_box(true))
				{
					$sql = 'DELETE FROM ' . $this->tourziel_region_table . '
							WHERE region_id = ' . (int) $region_id;
					$this->db->sql_query($sql);

					trigger_error($this->language->lang('ACP_MOT_TZV_MSG_DELETED') . adm_back_link($this->u_action));
				}
				else
				{
					$region_name = $this->request->variable('name', '');
					confirm_box(false, '<p>' . $this->language->lang('ACP_MOT_TZV_REGION_CONFIRM_DELETE', $region_name) . '</p>', build_hidden_fields([
						'id'		=> $region_id,
						'action'	=> 'delete',
					]));
				}
				break;

			case 'edit':
				if ($submit)
				{
					if (!check_form_key('acp_mot_tzv'))
					{
						trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$region_row = [
						'region_name' => utf8_normalize_nfc($this->request->variable('region_name', '', true)),
					];

					if (empty($region_row['region_name']))
					{
						$error[] = $this->language->lang('ACP_MOT_TZV_ERROR_NO_NAME');
					}

					if (count($error) == 0)
					{
						$sql = 'UPDATE ' . $this->tourziel_region_table . '
								SET ' . $this->db->sql_build_array('UPDATE', $region_row) . '
								WHERE region_id = ' . (int) $region_id;
						$this->db->sql_query($sql);

						trigger_error($this->language->lang('ACP_MOT_TZV_MSG_EDITED') . adm_back_link($this->u_action));
					}
				}

				$sql = 'SELECT region_id, region_name
						FROM ' . $this->tourziel_region_table . '
						WHERE region_id =' . (int) $region_id;
				$result = $this->db->sql_query($sql);
				$region_row = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);

				if (!$region_row)
				{
					trigger_error($this->language->lang('ACP_MOT_TZV_ERROR_NOT_EXIST') . adm_back_link($this->u_action . "&amp;id=$region_id&amp;action=$action"), E_USER_WARNING);
				}

				$this->template->assign_vars([
					'L_TITLE'		=> $this->language->lang('ACP_MOT_TZV_REGION_EDIT'),
					'U_ACTION'		=> $this->u_action . "&amp;id=$region_id&amp;action=$action",
					'ERROR_MSG'		=> count($error) > 0 ? implode('<br>', $error) : '',
					'REGION_NAME'	=> $region_row['region_name'],
					'REGION_ID'		=> $region_row['region_id'],
					'S_ADD_REGION'	=> true,
					'S_EDIT_REGION'	=> true,
					'S_ERROR'		=> count($error) > 0 ? true : false,
				]);
				break;

			case 'add':
				if (!check_form_key('acp_mot_tzv'))
				{
					trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
				}

				// Eintrag mit gleichen Namen vorhanden?
				$sql = 'SELECT * FROM ' . $this->tourziel_region_table;
				$result = $this->db->sql_query($sql);

				$region_name_arry = [];

				while ($row = $this->db->sql_fetchrow($result))
				{
					$region_name_arry[] = $row['region_name'];
				}
				$this->db->sql_freeresult($result);

				$region_name_arry = implode(',', $region_name_arry);
				$region_name_arry = strtolower($region_name_arry);
				$region_name_arry = explode(',', $region_name_arry);

				if ($submit)
				{
					$region_row = [
						'region_name' => utf8_normalize_nfc($this->request->variable('region_name', '', true)),
					];
					if (empty($region_row['region_name']))
					{
						$error[] = $this->language->lang('ACP_MOT_TZV_ERROR_NO_NAME');
					}
					// prüfe ob Name anders
					if (in_array(strtolower($region_row['region_name']), $region_name_arry))
					{
						$error[] = $this->language->lang('ACP_MOT_TZV_NAME_EXISTS');
					}
					if (count($error) == 0)
					{
						$sql = 'INSERT INTO ' . $this->tourziel_region_table . ' ' . $this->db->sql_build_array('INSERT', $region_row);
						$this->db->sql_query($sql);

						trigger_error($this->language->lang('ACP_MOT_TZV_MSG_ADDED') . adm_back_link($this->u_action));
					}
				}

				$this->template->assign_vars([
					'L_TITLE'		=> $this->language->lang('ACP_MOT_TZV_REGION_ADD'),
					'U_ACTION'		=> $this->u_action . "&amp;action=$action",
					'ERROR_MSG'		=> count($error) > 0 ? implode('<br>', $error) : '',
					'S_ADD_REGION'	=> true,
					'S_EDIT_REGION'	=> false,
					'S_ERROR'		=> count($error) > 0 ? true : false,
				]);
				break;
		}

		// Get number of regions used in Tourziele
		$sql = 'SELECT region, COUNT(*) AS count FROM ' . $this->tourziel_table . '
				GROUP BY region';
		$result = $this->db->sql_query($sql);
		$regions = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);
		$region_count = [];
		foreach ($regions as $row)
		{
			$region_count[$row['region']] = $row['count'];
		}

		// Get total number of regions for pagination
		$count_sql = "SELECT COUNT(region_id) AS total_regions FROM " . $this->tourziel_region_table;
		$result = $this->db->sql_query($count_sql);
		$row = $this->db->sql_fetchrow($result);
		$total_regions = $row['total_regions'];
		$this->db->sql_freeresult($result);

		// Load the countries
		$sql = 'SELECT * FROM ' . $this->tourziel_region_table . '
				ORDER BY region_name ASC';
		$result = $this->db->sql_query_limit($sql, $limit, $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('region', [
				'REGION_NAME'	=> $row['region_name'],
				'REGION_ID'		=> $row['region_id'],
				'REGION_COUNT'	=> array_key_exists($row['region_id'], $region_count) ? $region_count[$row['region_id']] : '-',
				'U_EDIT'		=> $this->u_action . "&amp;id={$row['region_id']}&amp;action=edit",
				'U_DELETE'		=> $this->u_action . "&amp;id={$row['region_id']}&amp;name={$row['region_name']}&amp;action=delete"
			]);
		}
		$this->db->sql_freeresult($result);

		//base url for pagination
		$base_url = $this->u_action;

		// Load pagination
		$start = $this->pagination->validate_start($start, $limit, $total_regions);
		$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_regions, $limit, $start);

		$this->template->assign_vars([
			'S_REGION'	=> true,
			'U_BACK'	=> $this->u_action,
		]);
	}

	/*-------------------------------------
	Einstellungen KATEGORIE
	-------------------------------------*/
	public function category()
	{
		// set parameters for pagination
		$start = $this->request->variable('start', 0);
		$limit = (int) $this->config['mot_tzv_acp_rows_per_page'];

		add_form_key('acp_mot_tzv');
		$error = [];

		$submit = $this->request->is_set_post('submit');
		$action = $this->request->variable('action', '');
		$cat_id = $this->request->variable('id', 0);

		if ($this->request->is_set_post('add'))
		{
			$action = 'add';
		}

		switch ($action)
		{
			case 'delete':
				if (confirm_box(true))
				{
					$sql = 'DELETE FROM ' . $this->tourziel_cats_table . '
							WHERE cat_id = ' . (int) $cat_id;
					$this->db->sql_query($sql);

					trigger_error($this->language->lang('ACP_MOT_TZV_MSG_DELETED') . adm_back_link($this->u_action));
				}
				else
				{
					$cat_name = $this->request->variable('name', '');
					confirm_box(false, '<p>' . $this->language->lang('ACP_MOT_TZV_CAT_CONFIRM_DELETE', $cat_name) . '</p>', build_hidden_fields([
						'id'		=> $cat_id,
						'action'	=> 'delete',
					]));
				}
				break;

			case 'edit':
				if ($submit)
				{
					if (!check_form_key('acp_mot_tzv'))
					{
						trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$cat_row = [
						'cat_name'	=> utf8_normalize_nfc($this->request->variable('cat_name', '', true)),
					];

					if (empty($cat_row['cat_name']))
					{
						$error[] = $this->language->lang('ACP_MOT_TZV_ERROR_NO_NAME');
					}

					if (count($error) == 0)
					{
						$sql = 'UPDATE ' . $this->tourziel_cats_table . '
								SET ' . $this->db->sql_build_array('UPDATE', $cat_row) . '
								WHERE cat_id = ' . (int) $cat_id;
						$this->db->sql_query($sql);

						trigger_error($this->language->lang('ACP_MOT_TZV_MSG_EDITED') . adm_back_link($this->u_action));
					}
				}

				$sql = 'SELECT cat_id, cat_name
						FROM ' . $this->tourziel_cats_table . '
						WHERE cat_id =' . (int) $cat_id;
				$result = $this->db->sql_query($sql);
				$cat_row = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);

				if (!$cat_row)
				{
					trigger_error($this->language->lang('ACP_MOT_TZV_ERROR_NOT_EXIST') . adm_back_link($this->u_action . "&amp;id=$cat_id&amp;action=$action"), E_USER_WARNING);
				}

				$this->template->assign_vars([
					'L_TITLE'		=> $this->language->lang('ACP_MOT_TZV_CAT_EDIT'),
					'U_ACTION'		=> $this->u_action . "&amp;id=$cat_id&amp;action=$action",
					'ERROR_MSG'		=> (sizeof($error)) ? implode('<br>', $error) : '',
					'CATS_NAME'		=> $cat_row['cat_name'],
					'CATS_ID'		=> $cat_row['cat_id'],
					'S_ADD_CATS'	=> true,
					'S_EDIT_CAT'	=> true,
					'S_ERROR'		=> (sizeof($error)) ? true : false,
				]);
				break;

			case 'add':
				if (!check_form_key('acp_mot_tzv'))
				{
					trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
				}

				// Eintrag mit gleichen Namen vorhanden?
				$sql = 'SELECT * FROM ' . $this->tourziel_cats_table;
				$result = $this->db->sql_query($sql);

				$cat_name_arry = [];

				while ($row = $this->db->sql_fetchrow($result))
				{
					$cat_name_arry[] = $row['cat_name'];
				}
				$this->db->sql_freeresult($result);

				$cat_name_arry = implode(',', $cat_name_arry);
				$cat_name_arry = strtolower($cat_name_arry);
				$cat_name_arry = explode(',', $cat_name_arry);

				if ($submit)
				{
					$cat_row = [
						'cat_name' => utf8_normalize_nfc($this->request->variable('cat_name', '', true)),
					];
					if (empty($cat_row['cat_name']))
					{
						$error[] = $this->language->lang('ACP_MOT_TZV_ERROR_NO_NAME');
					}
					// überprüfe ob Name anders
					if (in_array(strtolower($cat_row['cat_name']), $cat_name_arry))
					{
						$error[] = $this->language->lang('ACP_MOT_TZV_NAME_EXISTS');
					}
					if (count($error) == 0)
					{
						$sql = 'INSERT INTO ' . $this->tourziel_cats_table . ' ' . $this->db->sql_build_array('INSERT', $cat_row);
						$this->db->sql_query($sql);

						trigger_error($this->language->lang('ACP_MOT_TZV_MSG_ADDED') . adm_back_link($this->u_action));
					}
				}

				$this->template->assign_vars([
					'L_TITLE'		=> $this->language->lang('ACP_MOT_TZV_CAT_ADD'),
					'U_ACTION'		=> $this->u_action . "&amp;action=$action",
					'ERROR_MSG'		=> (sizeof($error)) ? implode('<br>', $error) : '',
					'S_ADD_CATS'	=> true,
					'S_EDIT_CAT'	=> false,
					'S_ERROR'		=> (sizeof($error)) ? true : false,
				]);
				break;
		}

		// Get number of categories used in Tourziele
		$sql = 'SELECT category, COUNT(*) AS count FROM ' . $this->tourziel_table . '
				GROUP BY category';
		$result = $this->db->sql_query($sql);
		$cats = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);
		$cat_count = [];
		foreach ($cats as $row)
		{
			$cat_count[$row['category']] = $row['count'];
		}

		// Get total number of categories for pagination
		$count_sql = "SELECT COUNT(cat_id) AS total_categories FROM " . $this->tourziel_cats_table;
		$result = $this->db->sql_query($count_sql);
		$row = $this->db->sql_fetchrow($result);
		$total_categories = $row['total_categories'];
		$this->db->sql_freeresult($result);

		// Load the categories
		$sql = 'SELECT * FROM ' . $this->tourziel_cats_table . '
				ORDER BY cat_name ASC';
		$result = $this->db->sql_query_limit($sql, $limit, $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('cats', [
				'CATS_NAME'		=> $row['cat_name'],
				'CATS_ID'		=> $row['cat_id'],
				'CAT_COUNT'		=> array_key_exists($row['cat_id'], $cat_count) ? $cat_count[$row['cat_id']] : '-',
				'U_EDIT'		=> $this->u_action . "&amp;id={$row['cat_id']}&amp;action=edit",
				'U_DELETE'		=> $this->u_action . "&amp;id={$row['cat_id']}&amp;name={$row['cat_name']}&amp;action=delete",
			]);
		}
		$this->db->sql_freeresult($result);

		//base url for pagination
		$base_url = $this->u_action;

		// Load pagination
		$start = $this->pagination->validate_start($start, $limit, $total_categories);
		$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_categories, $limit, $start);

		$this->template->assign_vars([
			'S_CATS'	=> true,
			'U_BACK'	=> $this->u_action,
		]);
	}

	/*-------------------------------------
	Einstellungen WLAN
	-------------------------------------*/
	public function wlan()
	{
		// set parameters for pagination
		$start = $this->request->variable('start', 0);
		$limit = (int) $this->config['mot_tzv_acp_rows_per_page'];

		add_form_key('acp_mot_tzv');
		$error = [];

		$submit = $this->request->is_set_post('submit');
		$action = $this->request->variable('action', '');
		$wlan_id = $this->request->variable('id', 0);

		if ($this->request->is_set_post('add'))
		{
			$action = 'add';
		}

		switch ($action)
		{
			case 'delete':
				if (confirm_box(true))
				{
					$sql = 'DELETE FROM ' . $this->tourziel_wlan_table . '
							WHERE wlan_id = ' . (int) $wlan_id;
					$this->db->sql_query($sql);

					trigger_error($this->language->lang('ACP_MOT_TZV_MSG_DELETED') . adm_back_link($this->u_action));
				}
				else
				{
					$wlan_name = $this->request->variable('name', '');
					confirm_box(false, '<p>' . $this->language->lang('ACP_MOT_TZV_WLAN_CONFIRM_DELETE', $wlan_name) . '</p>', build_hidden_fields([
						'id'		=> $wlan_id,
						'action'	=> 'delete',
					]));
				}
				break;

			case 'edit':
				if ($submit)
				{
					if (!check_form_key('acp_mot_tzv'))
					{
						trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$wlan_row = [
						'wlan_name' => utf8_normalize_nfc($this->request->variable('wlan_name', '', true)),
					];

					if (empty($wlan_row['wlan_name']))
					{
						$error[] = $this->language->lang('WLAN_ERROR_NO_CATS_NAME');
					}

					if (count($error) == 0)
					{
						$sql = 'UPDATE ' . $this->tourziel_wlan_table . '
								SET ' . $this->db->sql_build_array('UPDATE', $wlan_row) . '
								WHERE wlan_id = ' . (int) $wlan_id;
						$this->db->sql_query($sql);

						trigger_error($this->language->lang('ACP_MOT_TZV_MSG_EDITED') . adm_back_link($this->u_action));
					}
				}

				$sql = 'SELECT wlan_id, wlan_name
						FROM ' . $this->tourziel_wlan_table . '
						WHERE wlan_id =' . (int) $wlan_id;
				$result = $this->db->sql_query($sql);
				$wlan_row = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);

				if (!$wlan_row)
				{
					trigger_error($this->language->lang('ACP_MOT_TZV_ERROR_NOT_EXIST') . adm_back_link($this->u_action . "&amp;id=$wlan_id&amp;action=$action"), E_USER_WARNING);
				}

				$this->template->assign_vars([
					'L_TITLE'		=> $this->language->lang('ACP_MOT_TZV_WLAN_EDIT'),
					'U_ACTION'		=> $this->u_action . "&amp;id=$wlan_id&amp;action=$action",
					'ERROR_MSG'		=> (sizeof($error)) ? implode('<br>', $error) : '',
					'WLAN_NAME'		=> $wlan_row['wlan_name'],
					'WLAN_ID'		=> $wlan_row['wlan_id'],
					'S_ADD_WLAN'	=> true,
					'S_EDIT_WLAN'	=> true,
					'S_ERROR'		=> (sizeof($error)) ? true : false,
				]);
				break;

			case 'add':
				if (!check_form_key('acp_mot_tzv'))
				{
					trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
				}

				// Eintrag mit gleichen Namen vorhanden?
				$sql = 'SELECT * FROM ' . $this->tourziel_wlan_table;
				$result = $this->db->sql_query($sql);

				$wlan_name_arry = [];

				while ($row = $this->db->sql_fetchrow($result))
				{
					$wlan_name_arry[] = $row['wlan_name'];
				}
				$this->db->sql_freeresult($result);

				$wlan_name_arry = implode(',', $wlan_name_arry);
				$wlan_name_arry = strtolower($wlan_name_arry);
				$wlan_name_arry = explode(',', $wlan_name_arry);

				if ($submit)
				{
					$wlan_row = [
						'wlan_name' => utf8_normalize_nfc($this->request->variable('wlan_name', '', true)),
					];
					if (empty($wlan_row['wlan_name']))
					{
						$error[] = $this->language->lang('ACP_MOT_TZV_ERROR_NO_NAME');
					}
					// überprüfe ob Name anders
					if (in_array(strtolower($wlan_row['wlan_name']), $wlan_name_arry))
					{
						$error[] = $this->language->lang('ACP_MOT_TZV_NAME_EXISTS');
					}
					if (count($error) == 0)
					{
						$sql = 'INSERT INTO ' . $this->tourziel_wlan_table . ' ' . $this->db->sql_build_array('INSERT', $wlan_row);
						$this->db->sql_query($sql);
						trigger_error($this->language->lang('ACP_MOT_TZV_MSG_ADDED') . adm_back_link($this->u_action));
					}
				}

				$this->template->assign_vars([
					'L_TITLE'		=> $this->language->lang('ACP_MOT_TZV_WLAN_ADD'),
					'U_ACTION'		=> $this->u_action . "&amp;action=$action",
					'ERROR_MSG'		=> (sizeof($error)) ? implode('<br>', $error) : '',
					'S_ADD_WLAN'	=> true,
					'S_EDIT_WLAN'	=> false,
					'S_ERROR'		=> (sizeof($error)) ? true : false,
				]);
				break;
		}

		// Get number of WLAN options used in Tourziele
		$sql = 'SELECT wlan, COUNT(*) AS count FROM ' . $this->tourziel_table . '
				GROUP BY wlan';
		$result = $this->db->sql_query($sql);
		$wlans = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);
		$wlan_count = [];
		foreach ($wlans as $row)
		{
			$wlan_count[$row['wlan']] = $row['count'];
		}

		// Get total number of WLANs for pagination
		$count_sql = "SELECT COUNT(wlan_id) AS total_wlans FROM " . $this->tourziel_wlan_table;
		$result = $this->db->sql_query($count_sql);
		$row = $this->db->sql_fetchrow($result);
		$total_wlans = $row['total_wlans'];
		$this->db->sql_freeresult($result);

		// Load the WLANs
		$sql = 'SELECT * FROM ' . $this->tourziel_wlan_table . '
				ORDER BY wlan_name ASC';
		$result = $this->db->sql_query_limit($sql, $limit, $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('wlan', [
				'WLAN_NAME'		=> $row['wlan_name'],
				'WLAN_ID'		=> $row['wlan_id'],
				'WLAN_COUNT'	=> array_key_exists($row['wlan_id'], $wlan_count) ? $wlan_count[$row['wlan_id']] : '-',
				'U_EDIT'		=> $this->u_action . "&amp;id={$row['wlan_id']}&amp;action=edit",
				'U_DELETE'		=> $this->u_action . "&amp;id={$row['wlan_id']}name={$row['wlan_name']}&amp;action=delete",
			]);
		}
		$this->db->sql_freeresult($result);

		//base url for pagination
		$base_url = $this->u_action;

		// Load pagination
		$start = $this->pagination->validate_start($start, $limit, $total_wlans);
		$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_wlans, $limit, $start);

		$this->template->assign_vars([
			'S_WLAN'	=> true,
			'U_BACK'	=> $this->u_action,
		]);
	}

	/*-------------------------------------
	Einstellungen INFO - SUPPORT
	-------------------------------------*/
	public function info()
	{
		$this->language->add_lang('acp_mot_tourziel_help', 'mot/tzv'); // Sprachdatei
		$this->tpl_name = 'acp_tzv_info';
		$this->page_title = $this->language->lang('ACP_TZV_INFO');

		$this->template->assign_var('ACP_MOT_TZV_VERSION', $this->language->lang('ACP_MOT_TZV_VERSION', $this->tzv_version));
	}


// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Set custom form action.
	 *
	 * @param	string	$u_action	Custom form action
	 * @return acp		$this		This controller for chaining calls
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;

		return $this;
	}
}
