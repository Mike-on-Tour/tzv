<?php
/**
*
* @package MoT Tour Destinations Database
* ver 1.3.0
* @copyright (c) 2022 - 2025 Mike-on-Tour
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

	/** @var \phpbb\pagination  */
	protected $pagination;

	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \mot\tzv\includes\mot_tzv_functions */
	protected $mot_tzv_functions;

	/** @var string phpBB phpbb root path */
	protected $root_path;

	/** @var string PHP extension */
	protected $php_ext;

	/**
	 * {@inheritdoc
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper,
								\phpbb\language\language $language, \phpbb\pagination $pagination, \phpbb\extension\manager $phpbb_extension_manager,
								\phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user,
								\mot\tzv\includes\mot_tzv_functions $mot_tzv_functions, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->language = $language;
		$this->pagination = $pagination;
		$this->phpbb_extension_manager 	= $phpbb_extension_manager;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->mot_tzv_functions = $mot_tzv_functions;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;

		$this->ext_path = $this->phpbb_extension_manager->get_extension_path('mot/tzv', true);
		$this->md_manager = $this->phpbb_extension_manager->create_extension_metadata_manager('mot/tzv');
		$this->ext_data = $this->md_manager->get_metadata();

		$this->tzv_flags_url = $this->config['mot_tzv_flags_url'];
	}

	public function handle()
	{
		// Check whether the ext is enabled for viewing or if not it is enabled for admins only and the current user is an admin
		if (!$this->config['mot_tzv_enable'] || ($this->config['mot_tzv_enable'] && $this->config['mot_tzv_admin'] && !$this->auth->acl_get('a_')))
		{
			trigger_error($this->language->lang('MOT_TZV_TOURZIEL_NO_VIEW'));
		}

		// Check whether the current user is permitted to view the extension
		if (!$this->auth->acl_get('u_mot_tzv_mainview'))
		{
			trigger_error($this->language->lang('MOT_TZV_TOURZIEL_NO_VIEW'));
		}

		// Include some function files we will need
		include_once($this->root_path . 'includes/functions_content.' . $this->php_ext);
		include_once($this->root_path . 'includes/functions_display.' . $this->php_ext);
		include_once($this->root_path . 'includes/functions_posting.' . $this->php_ext);

		// Create the tab routes
		$this->index_action = $this->helper->route('mot_tzv_main', ['tab' => 'index']);
		$this->list_action = $this->helper->route('mot_tzv_main', ['tab' => 'list']);
		$this->map_action = $this->helper->route('mot_tzv_main', ['tab' => 'map']);
		$this->search_action = $this->helper->route('mot_tzv_main', ['tab' => 'search']);
		$this->create_action = $this->helper->route('mot_tzv_main', ['tab' => 'create']);
		$this->detail_action = $this->helper->route('mot_tzv_main', ['tab' => 'detail']);

		$tab = $this->request->variable('tab', 'index');

		switch ($tab)
		{
			case 'index':
				// if images are to be displayed in the main window go and get them
				if ($this->config['mot_tzv_main_image'])
				{
					$this->image_path = $this->root_path . 'ext/mot/tzv/images/';
					$files = scandir($this->image_path);

					foreach ($files as &$element)
					{
						if (is_file ($this->image_path . $element))
						{
							$element = $this->image_path . $element;
						}
					}
				}

				$this->template->assign_vars([
					'MOT_TZV_COUNTRY_ENABLE'	=> $this->config['mot_tzv_country_enable'],
					'MOT_TZV_COUNTRY_FLAGS'		=> $this->mot_tzv_functions->get_country_info(),
					'MOT_TZV_MAIN_IMAGE'		=> $this->config['mot_tzv_main_image'],
					'MOT_TZV_IMAGES'			=> $files,
					'MOT_TZV_SHOW_GOOGLE'		=> $this->config['mot_tzv_googlemap_enable'],
					'MOT_TZV_SUPPORT_ENABLE'	=> $this->config['mot_tzv_support_enable'],
					'MOT_TZV_SUPPORT_LINK'		=> $this->root_path . $this->config['mot_tzv_support'],

					'ACP_MOT_TZV_MAININFO'		=> $this->config['mot_tzv_maininfo_enable'],
				]);
				break;

			case 'list':
				if (!$this->auth->acl_get('u_mot_tzv_view'))
				{
					trigger_error($this->language->lang('MOT_TZV_TOURZIEL_NO_VIEW'));
				}

				// Get total number of Tourziele
				$total_tz = $this->mot_tzv_functions->get_total_count_destinations();

				if ($total_tz)
				{
					// set parameters for pagination
					$start = $this->request->variable('start', 0);
					$limit = $this->config['mot_tzv_rows_per_page'];

					// get the last Tourziel
					$newest_tz = $this->mot_tzv_functions->get_destinations(0, 1, 0, true);
					$newest_tz[0]['url'] = $this->detail_action . '&id=' . $newest_tz[0]['id'];

					// Load all Tourziele for pagination
					$destinations = $this->mot_tzv_functions->get_destinations(0, $limit, $start, $this->auth->acl_get('m_mot_tzv_edit'));
					foreach ($destinations as &$row)
					{
						$row['url'] = $this->detail_action . '&id=' . $row['id'];
					}

					//base url for pagination
					$base_url = $this->list_action;

					// Load pagination
					$start = $this->pagination->validate_start($start, $limit, $total_tz);
					$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_tz, $limit, $start);
				}

				$this->template->assign_vars([
					'MOT_TZV_LONG_NEWEST'		=> $this->config['mot_tzv_latest_tz_view'],
					'MOT_TZV_LONG_TABLE'		=> $this->config['mot_tzv_list_tz_view'],
					'MOT_TZV_TOTAL_POSTS'		=> $total_tz,
					'MOT_TZV_NEWEST_TZ'			=> $newest_tz,
					'MOT_TZV_DEST_LIST'			=> $destinations,
				]);
				break;

			case 'map':
					$tourziel_array = [];
					$tourziele = $this->mot_tzv_functions->get_destinations();

					foreach ($tourziele as $row)
					{
						if ($row['maps_lat'] != '0' && $row['maps_lon'] != '0')	// Check whether this destination has coordinates to allow map positioning, otherwise we do not include it in the array for the map
						{
							// set the route to this destination's detail view
							$row['url'] = $this->detail_action . '&id=' .  $row['id'];
							$tourziel_array[] = $row;
						}
					}

					$map_config = [
						'Lat'				=> $this->config['mot_tzv_map_lat'],
						'Lon'				=> $this->config['mot_tzv_map_lon'],
						'Zoom'				=> $this->config['mot_tzv_map_zoom'],
						'Cluster'			=> $this->config['mot_tzv_map_enable_clusters'],
						'MultipleLayers'	=> $this->config['mot_tzv_enable_multi_layers'],
					];

					$this->template->assign_vars([
						'MOT_TZV_MARKER_COUNT'		=> count($tourziel_array),
						'MOT_TZV_MAPCONFIG'			=> json_encode($map_config),
						'MOT_TZV_TOURZIELE'			=> json_encode($tourziel_array),
						'MOT_TZV_CREATE_LINK'		=> $this->create_action,
					]);
				break;

			case 'search':
				if (!$this->auth->acl_get('u_mot_tzv_view'))
				{
					trigger_error($this->language->lang('MOT_TZV_TOURZIEL_NO_VIEW'));
				}

				// set parameters for pagination
				$start = $this->request->variable('start', 0);
				$limit = $this->config['mot_tzv_rows_per_page'];

				$destinations = [];
				$total_tz = 0;

				$submit = $this->request->is_set('submit');
				if ($submit)
				{
					$country = $this->request->variable('mot_tzv_country', 0) ? " OR t.country = " . $this->request->variable('mot_tzv_country', 0) : '';
					$region = $this->request->variable('mot_tzv_region', 0) ? " OR t.region = " . $this->request->variable('mot_tzv_region', 0) : '';
					$category = $this->request->variable('mot_tzv_category', 0) ? " OR t.category = " . $this->request->variable('mot_tzv_category', 0) : '';
					$name = ($this->request->variable('name', '', true)) ? " OR LOWER(t.name) LIKE '%" . mb_strtolower($this->db->sql_escape($this->request->variable('name', '', true)), 'UTF-8') . "%'" : '';
					$ort = ($this->request->variable('ort', '', true)) ? " OR LOWER(t.city) LIKE '%" . mb_strtolower($this->db->sql_escape($this->request->variable('ort', '', true)), 'UTF-8') . "%'": '';

					$total_tz = $this->mot_tzv_functions->get_total_count_destinations('t.id = 0' . $country . $region . $category . $name . $ort);
					$destinations = $this->mot_tzv_functions->get_destinations(0, $limit, $start, false, false, 't.id = 0' . $country . $region . $category . $name . $ort);

					foreach ($destinations as &$row)
					{
						$row['url'] = $this->detail_action . '&id=' . $row['id'];
					}

					//base url for pagination
					$base_url = $this->search_action . '&submit=' . $this->language->lang('MOT_TZV_BUTTON_SUCHEN') .
														'&mot_tzv_country=' . $this->request->variable('mot_tzv_country', 0) .
														'&mot_tzv_region=' . $this->request->variable('mot_tzv_region', 0) .
														'&mot_tzv_category=' . $this->request->variable('mot_tzv_category', 0) .
														'&name=' . $this->request->variable('name', '', true) .
														'&plz=' . $this->request->variable('plz', '', true) .
														'&ort=' . $this->request->variable('ort', '', true);

					// Load pagination
					$start = $this->pagination->validate_start($start, $limit, $total_tz);
					$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_tz, $limit, $start);
				}

				$map_config = [
					'Lat'				=> $this->config['mot_tzv_map_lat'],
					'Lon'				=> $this->config['mot_tzv_map_lon'],
					'Zoom'				=> $this->config['mot_tzv_map_zoom'],
					'Cluster'			=> $this->config['mot_tzv_map_enable_clusters'],
					'MultipleLayers'	=> $this->config['mot_tzv_enable_multi_layers'],
				];

				$this->template->assign_vars([
					'MOT_TZV_TOTAL_TZ'			=> $this->mot_tzv_functions->get_total_count_destinations(),
					'MOT_TZV_DEST_LIST'			=> $destinations,
					'U_FORM_ACTION'				=> $this->search_action,
					'MOT_TZV_SEARCH_ACTIVE'		=> true,
					'MOT_TZV_MAPS_ENABLE'		=> $this->config['mot_tzv_maps_enable'],
					'MOT_TZV_LONG_TABLE'		=> $this->config['mot_tzv_list_tz_view'],
					'MOT_TZV_TOTAL_POSTS'		=> $total_tz,

					'MOT_TZV_COUNTRY_ARR'		=> $this->mot_tzv_functions->get_country_selection($this->request->variable('mot_tzv_country', 0), $this->language->lang('MOT_TZV_AUSWAHL')),
					'MOT_TZV_REGION_ARR'		=> $this->mot_tzv_functions->get_region_selection($this->request->variable('mot_tzv_region', 0), $this->language->lang('MOT_TZV_AUSWAHL')),
					'MOT_TZV_CATEGORY_ARR'		=> $this->mot_tzv_functions->get_category_selection($this->request->variable('mot_tzv_category', 0), $this->language->lang('MOT_TZV_AUSWAHL')),
					'MOT_TZV_POST_NAME'			=> $this->request->variable('name', '', true),
					'MOT_TZV_POST_PLZ'			=> $this->request->variable('plz', '', true),
					'MOT_TZV_POST_ORT'			=> $this->request->variable('ort', '', true),

					'MOT_TZV_MAPCONFIG'			=> json_encode($map_config),
					'MOT_TZV_TOURZIELE'			=> json_encode($destinations),
					'MOT_TZV_ENABLE_SEARCH_MAP'	=> $total_tz,
				]);
				break;

			case 'create':
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

					$new_id = $this->mot_tzv_functions->add_destination($input_data, $this->create_action);

					// Create notification
					$this->mot_tzv_functions->create_notification('add', $new_id, $input_data['name']);

					meta_refresh(10, $this->list_action); // Go back to the destination list after 10 seconds

					$message =  $this->language->lang('MOT_TZV_EVENT_ADD_SUCCESSFUL') .
								'<br><br><a href="' . $this->detail_action . '&id=' . $new_id . '">' . $this->language->lang('MOT_TZV_VIEW_EVENT') . '</a>
								<br><br><a href="' . $this->index_action . '">'. $this->language->lang('MOT_TZV_RETURN_TOURZIEL') . '</a>';
					trigger_error($message);
				}

				$this->template->assign_vars([
					'MOT_TZV_MANDATORY_ARR'		=> json_decode($this->config['mot_tzv_mandatory_fields']),
					'MOT_TZV_MANDATORY_ARR_JS'	=> $this->config['mot_tzv_mandatory_fields'],
					'MOT_TZV_COUNTRY_ARR'		=> $this->mot_tzv_functions->get_country_selection(0, $this->language->lang('MOT_TZV_AUSWAHL')),
					'MOT_TZV_REGION_ARR'		=> $this->mot_tzv_functions->get_region_selection(0, $this->language->lang('MOT_TZV_AUSWAHL')),
					'MOT_TZV_CATEGORY_ARR'		=> $this->mot_tzv_functions->get_category_selection(0, $this->language->lang('MOT_TZV_AUSWAHL')),
					'MOT_TZV_WLAN_ARR'			=> $this->mot_tzv_functions->get_wlan_selection(0, $this->language->lang('MOT_TZV_AUSWAHL')),

					'MOT_TZV_COORD_MANDATORY'	=> $this->config['mot_tzv_maps_enable'],

					'S_BBCODE_ALLOWED'			=> true,
					'S_BBCODE_IMG'				=> true,
					'S_BBCODE_FLASH'			=> true,
					'S_LINKS_ALLOWED'			=> true,
				]);

				// Check whether we got a pair of coordinates from a right click on the map
				$ajax_data = [];
				$ajax_data['maps_lat'] = $this->request->variable('lat', '');
				$ajax_data['maps_lon'] = $this->request->variable('lng', '');
				if ($ajax_data['maps_lat'] != '' && $ajax_data['maps_lon'] != '')
				{
					$this->template->assign_var('MOT_TZV_DEST_DETAIL', $ajax_data);
				}

				display_custom_bbcodes();

				generate_smilies('inline', 1);
				break;

			case 'detail':
				$id = $this->request->variable('id', 0);
				$destination = $this->mot_tzv_functions->get_destinations($id)[0];
				$destination['url'] = $this->detail_action . '&id=' . $id;

				if (empty($destination))
				{
					trigger_error($this->language->lang('MOT_TZV_NO_SUCH_ITEM') . $this->mot_tzv_functions->tzv_back_link($this->index_action, $this->language->lang('MOT_TZV_TAB_INDEX')), E_USER_WARNING);
				}

				$edit_dest = false;
				$mode = $this->request->variable('mode', '');
				switch ($mode)
				{
					case 'edit':
						// If the user is neither a moderator with the permission to edit nor the user 'owning' this Tourziel with the permission to edit own Tourziele we just display an error message
						if (!($this->auth->acl_get('m_mot_tzv_edit')) && !( ($this->user->data['user_id'] == $destination['user_id']) && $this->auth->acl_get('u_mot_tzv_edit_own') ) )
						{
							trigger_error('NOT_AUTHORISED');
						}

						$edit_dest = true;

						add_form_key('mot_tzv_edit');

						if ($this->request->is_set_post('submit'))
						{
							if (!check_form_key('mot_tzv_edit'))
							{
								trigger_error($this->language->lang('FORM_INVALID'), E_USER_WARNING);
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

							$this->mot_tzv_functions->edit_destination($id, $input_data);

							// Create notification
							$this->mot_tzv_functions->create_notification('edit', $id, $input_data['name']);

							meta_refresh(10, $this->detail_action . '&id=' . $id); // Go back to the detailed view after 10 seconds

							$message =  $this->language->lang('MOT_TZV_EVENT_EDIT_SUCCESSFUL') . '<br><br><a href="' . $this->detail_action . '&id' . $id . '">' . $this->language->lang('MOT_TZV_RETURN_EVENT') . '</a><br><a href="' . $this->index_action . '">'. $this->language->lang('MOT_TZV_RETURN_TOURZIEL') . '</a>';
							trigger_error($message);
						}

						decode_message($destination['message'], $destination['bbcode_uid']);

						display_custom_bbcodes();

						generate_smilies('inline', 1);

						$this->template->assign_vars([
							'MOT_TZV_MANDATORY_ARR'		=> json_decode($this->config['mot_tzv_mandatory_fields']),
							'MOT_TZV_MANDATORY_ARR_JS'	=> $this->config['mot_tzv_mandatory_fields'],
							'MOT_TZV_COORD_MANDATORY'	=> $this->config['mot_tzv_maps_enable'],

							'MOT_TZV_EDIT_TZ'			=> true,

							'MOT_TZV_COUNTRY_ARR'		=> $this->mot_tzv_functions->get_country_selection((int) $destination['country'], $this->language->lang('MOT_TZV_AUSWAHL')),
							'MOT_TZV_REGION_ARR'		=> $this->mot_tzv_functions->get_region_selection((int) $destination['region'], $this->language->lang('MOT_TZV_AUSWAHL')),
							'MOT_TZV_CATEGORY_ARR'		=> $this->mot_tzv_functions->get_category_selection((int) $destination['category'], $this->language->lang('MOT_TZV_AUSWAHL')),
							'MOT_TZV_WLAN_ARR'			=> $this->mot_tzv_functions->get_wlan_selection((int) $destination['wlan'], $this->language->lang('MOT_TZV_AUSWAHL')),

							'S_BBCODE_ALLOWED'			=> true,
							'S_BBCODE_IMG'				=> true,
							'S_BBCODE_FLASH'			=> true,
						]);
						break;

					case 'delete':
						// If the user is neither a moderator with the permission to delete nor the user 'owning' this Tourziel with the permission to delete own Tourziele we just display an error message
						if (!($this->auth->acl_get('m_mot_tzv_delete')) && !(($this->user->data['user_id'] == $destination['user_id']) && $this->auth->acl_get('u_mot_tzv_delete_own')))
						{
							trigger_error($this->language->lang('NOT_AUTHORISED'));
						}

						if (confirm_box(true))
						{
							$this->mot_tzv_functions->delete_destination($id);

							$this->mot_tzv_functions->create_notification('delete', $id, $destination['name']);

							$message = $this->language->lang('MOT_TZV_EVENT_DELETE_SUCCESSFUL') . '<br><br><a href="' . $this->index_action . '">'. $this->language->lang('MOT_TZV_RETURN_TOURZIEL') . '</a>';
							trigger_error($message);
						}
						else
						{
							confirm_box(false, '<p>' . $this->language->lang('MOT_TZV_EVENT_DELETE_CONFIRM', $destination['name']) . '</p>', build_hidden_fields([
								'id'	=> $destination['id'],
							]));
						}

						// If the user decided to not delete this Tourziel we display the following message
						$message = $this->language->lang('MOT_TZV_EVENT_NOT_DELETED') . '<br><br><a href="' . $this->detail_action . '&id=' . $id .'">'. $this->language->lang('MOT_TZV_RETURN_EVENT') . '</a>';
						trigger_error($message);
						break;

					default:
						break;
				}

				$edit_link = (($this->user->data['user_id'] == $destination['user_id'] && $this->auth->acl_get('u_mot_tzv_edit_own')) || $this->auth->acl_get('m_mot_tzv_edit')) ?
								$this->detail_action . '&id=' .  $destination['id'] . '&mode=edit' : '';
				$delete_link = (($this->user->data['user_id'] == $destination['user_id'] && $this->auth->acl_get('u_mot_tzv_edit_own')) || $this->auth->acl_get('m_mot_tzv_edit')) ?
								$this->detail_action . '&id=' .  $destination['id'] . '&mode=delete' : '';

				if ($this->config['mot_tzv_ostreetmap_enable'])
				{
					$osm_bbox = $this->mot_tzv_functions->lonlat_to_bbox($this->config['mot_tzv_maps_width'], $this->config['mot_tzv_maps_height'], $destination['maps_lat'], $destination['maps_lon'], $this->config['mot_tzv_maps_zoom']);

					$this->template->assign_vars([
						'MOT_TZV_OSM_LON_S'		=> $osm_bbox['lon_s'],
						'MOT_TZV_OSM_LAT_S'		=> $osm_bbox['lat_s'],
						'MOT_TZV_OSM_LON_E'		=> $osm_bbox['lon_e'],
						'MOT_TZV_OSM_LAT_E'		=> $osm_bbox['lat_e'],
					]);
				}

				$komoot_langs = ['de' => 'de', 'en' => 'com', 'es' => 'es', 'fr' => 'fr', 'it' => 'it', 'nl' => 'nl'];
				$this->template->assign_vars([
					'MOT_TZV_EDIT_TZ'			=> $edit_dest,
					'MOT_TZV_DEST_DETAIL'		=> $destination,
					'U_EDIT_LINK'				=> $edit_link,
					'U_DELETE_LINK'				=> $delete_link,
					'MOT_TZV_MAPS_ENABLE'		=> $this->config['mot_tzv_maps_enable'],
					'MOT_TZV_KURVIGER_ENABLE'	=> $this->config['mot_tzv_kurviger_enable'],
					'MOT_TZV_KURVIGER_LANG'		=> in_array($this->language->lang('MOT_TZV_MAP_LANG'), ['de', 'en', 'es', 'fr', 'it', 'nl',]) ? $this->language->lang('MOT_TZV_MAP_LANG') : 'en',
					'MOT_TZV_KOMOOT_ENABLE'		=> $this->config['mot_tzv_komoot_enable'],
					'MOT_TZV_KOMOOT_LANG'		=> in_array($this->language->lang('MOT_TZV_MAP_LANG'), ['de', 'es', 'fr', 'it', 'nl', 'pt',]) ? $komoot_langs[$this->language->lang('MOT_TZV_MAP_LANG')] : 'com',
					'MOT_TZV_GOOGLEMAP_ENABLE'	=> $this->config['mot_tzv_googlemap_enable'],
					'MOT_TZV_OSMMAP_ENABLE'		=> $this->config['mot_tzv_ostreetmap_enable'],

					'MOT_TZV_MAPS_WIDTH'		=> $this->config['mot_tzv_maps_width'],
					'MOT_TZV_MAPS_HEIGHT'		=> $this->config['mot_tzv_maps_height'],
					'MOT_TZV_MAPS_ZOOM'			=> $this->config['mot_tzv_maps_zoom'],
				]);
				break;
		} // End switch statement

		$this->template->assign_vars([
			'MOT_TZV_SELECTED_TAB'		=> $tab,
			'MOT_TZV_TAB_INDEX'			=> $this->index_action,
			'MOT_TZV_TAB_LIST'			=> $this->list_action,
			'MOT_TZV_TAB_MAP'			=> $this->map_action,
			'MOT_TZV_TAB_CREATE'		=> $this->create_action,
			'MOT_TZV_TAB_SEARCH'		=> $this->search_action,
			'MOT_TZV_LIST_AUTH'			=> $this->auth->acl_get('u_mot_tzv_view'),
			'MOT_TZV_CREATE_AUTH'		=> $this->auth->acl_get('u_mot_tzv_add'),
			'MOT_TZV_TOURZIEL_NUMBER'	=> $this->language->lang('MOT_TZV_COUNT_TOTAL_DEST', (int) $this->mot_tzv_functions->get_total_count_destinations()),
			'MOT_TZV_ACTIVE'			=> true,
			'MOT_TZV_COPYRIGHT'			=> $this->ext_data['extra']['display-name'] . ' ' . $this->ext_data['version'] . ' &copy; Mike-on-Tour (<a href="' . $this->ext_data['homepage'] . '" target="_blank" rel="noopener">' . $this->ext_data['homepage'] . '</a>)',
		]);

		// Add breadcrumbs link
		$this->template->assign_block_vars('navlinks', [
				'FORUM_NAME'	=> $this->language->lang('MOT_TZV_TOURZIEL'),
			'U_VIEW_FORUM'	=> $this->helper->route('mot_tzv_main'),
		]);

		return $this->helper->render('@mot_tzv/mot_tzv_main.html', $this->language->lang('MOT_TZV_TOURZIEL'));
	}
}
