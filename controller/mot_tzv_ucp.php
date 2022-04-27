<?php
/**
*
* @package phpBB Extension [Adressverwaltung - Tourziele]
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace mot\tzv\controller;

class mot_tzv_ucp
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

	/**
	 * {@inheritdoc
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db,
								\phpbb\controller\helper $helper, \phpbb\language\language $language, \phpbb\pagination $pagination,
								\phpbb\request\request_interface $request, \phpbb\template\template $template, \phpbb\user $user,
								\mot\tzv\functions\mot_tzv_events $mot_tzv_events, $root_path, $php_ext, $mot_tzv_tourziel_table,
								$mot_tzv_tourziel_country_table, $mot_tzv_tourziel_region_table, $mot_tzv_tourziel_cats_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->language = $language;
		$this->pagination = $pagination;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->events = $mot_tzv_events;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		// TZV Tabellen
		$this->tourziel_table         = $mot_tzv_tourziel_table;
		$this->tourziel_country_table = $mot_tzv_tourziel_country_table;
		$this->tourziel_region_table  = $mot_tzv_tourziel_region_table;
		$this->tourziel_cats_table    = $mot_tzv_tourziel_cats_table;

		$this->tzv_flags_url = $this->config['mot_tzv_flags_url'];
	}

	public function summary()
	{
		$id = $this->request->variable('id', 0);
		if ($id != 0)
		{
			$event = $this->events->get_events($id, false, false, true);
		}

		$action = $this->request->variable('action', '');
		switch ($action)
		{
			case 'delete':
				// If the user is not owning this Tourziel with the permission to delete own Tourziele we just display an error message
				if (!( ($this->user->data['user_id'] == $event['user_id']) && $this->auth->acl_get('u_mot_tzv_delete_own') ) )
				{
					trigger_error('UCP_MOT_TZV_NOT_AUTHORISED');
				}

				if (confirm_box(true))
				{
					$this->events->delete_event($id);
					$message = $this->language->lang('MOT_TZV_EVENT_DELETE_SUCCESSFUL') . '<br><br><a href="' . $this->u_action . '">' . $this->language->lang('UCP_MOT_TZV_BACK_TO_UCP') . '</a>';
					trigger_error($message);
				}
				else
				{
					confirm_box(false, '<p>' . $this->language->lang('MOT_TZV_EVENT_DELETE_CONFIRM', $event['name']) . '</p>', build_hidden_fields([
						'id'	=> $event['id'],
					]));
				}

				// If the user decided to not delete this Tourziel we display the following message
				$message = $this->language->lang('MOT_TZV_EVENT_NOT_DELETED') . '<br><br><a href="' . $this->u_action . '">' . $this->language->lang('UCP_MOT_TZV_BACK_TO_UCP') . '</a>';
				trigger_error($message);

				break;

			case 'edit':
				// If the user is not owning this Tourziel with the permission to edit own Tourziele we just display an error message
				if (!( ($this->user->data['user_id'] == $event['user_id']) && $this->auth->acl_get('u_mot_tzv_edit_own') ) )
				{
					trigger_error('UCP_MOT_TZV_NOT_AUTHORISED');
				}

				add_form_key('ucp_mot_tzv_edit');

				if ($this->request->is_set_post('submit'))
				{
					if (!check_form_key('ucp_mot_tzv_edit'))
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

					$message =  $this->language->lang('MOT_TZV_EVENT_EDIT_SUCCESSFUL') . '<br><br><a href="' . $this->u_action . '">' . $this->language->lang('UCP_MOT_TZV_BACK_TO_UCP') . '</a>';
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

				break;

			default:
				// Get total number of Tourziele from this user (for pagination)
				$sql = 'SELECT COUNT(*) AS count
						FROM ' . $this->tourziel_table . '
						WHERE user_id = ' . (int) $this->user->data['user_id'];
				$result = $this->db->sql_query($sql);
				$total_tz = $this->db->sql_fetchfield('count');
				$this->db->sql_freeresult($result);

				// set parameters for pagination
				$start = $this->request->variable('start', 0);
				$limit = $this->config['mot_tzv_rows_per_page'];

				$sql = 'SELECT tz.id, tz.name, tz.maps_lat, tz.maps_lon,
						ct.country_name, ct.country_image,
						rt.region_name,
						kt.cat_name

						FROM ' . $this->tourziel_table . ' tz
						JOIN ' . $this->tourziel_country_table . ' ct
						ON tz.country = ct.country_id
						JOIN ' . $this->tourziel_region_table . ' rt
						ON tz.region = rt.region_id
						JOIN ' . $this->tourziel_cats_table . ' kt
						ON tz.category = kt.cat_id
						WHERE tz.user_id = ' . (int) $this->user->data['user_id'] . '
						ORDER BY tz.id ASC';
				$result = $this->db->sql_query_limit($sql, $limit, $start);
				$tourziele = $this->db->sql_fetchrowset($result);
				$this->db->sql_freeresult($result);

				foreach ($tourziele as $row)
				{
					$this->template->assign_block_vars('tzlist', [
						'NAME'			=> $row['name'],
						'CATEGORY'		=> $row['cat_name'],
						'FLAG'			=> $this->tzv_flags_url . $row['country_image'],
						'COUNTRY'		=> $row['country_name'],
						'REGION'		=> $row['region_name'],
						'MAP_LAT'		=> $row['maps_lat'],
						'MAP_LON'		=> $row['maps_lon'],
						'U_EDIT'		=> $this->auth->acl_get('u_mot_tzv_edit_own') ? $this->u_action . '&amp;action=edit&amp;id=' . $row['id'] : '',
						'U_DELETE'		=> $this->auth->acl_get('u_mot_tzv_delete_own') ? $this->u_action . '&amp;action=delete&amp;id=' . $row['id'] : '',
					]);
				}

				//base url for pagination
				$base_url = $this->u_action;

				// Load pagination
				$start = $this->pagination->validate_start($start, $limit, $total_tz);
				$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_tz, $limit, $start);

				$this->template->assign_vars([
					'ICON_EDIT'					=> '<i class="icon acp-icon acp-icon-settings fa-cog fa-fw" title="' . $this->language->lang('EDIT') . '"></i>',
					'ICON_EDIT_DISABLED'		=> '<i class="icon acp-icon acp-icon-disabled fa-cog fa-fw" title="' . $this->language->lang('EDIT') . '"></i>',
					'ICON_DELETE'				=> '<i class="icon acp-icon acp-icon-delete fa-times-circle fa-fw" title="' . $this->language->lang('DELETE') . '"></i>',
					'ICON_DELETE_DISABLED'		=> '<i class="icon acp-icon acp-icon-disabled fa-times-circle fa-fw" title="' . $this->language->lang('DELETE') . '"></i>',
				]);

				break;
		}
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
