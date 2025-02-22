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

class mot_tzv_ucp
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

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
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\language\language $language, \phpbb\pagination $pagination,
								\phpbb\extension\manager $phpbb_extension_manager, \phpbb\request\request_interface $request, \phpbb\template\template $template,
								\phpbb\user $user, \mot\tzv\includes\mot_tzv_functions $mot_tzv_functions, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
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

	public function summary()
	{
		$id = $this->request->variable('id', 0);
		if ($id)
		{
			$destination = $this->mot_tzv_functions->get_destinations($id, false, false, false, true)[0];
		}

		$action = $this->request->variable('action', '');
		switch ($action)
		{
			case 'delete':
				// If the user does not have the permission to delete own destinations we just display an error message
				if (!( ($this->user->data['user_id'] == $destination['user_id']) && $this->auth->acl_get('u_mot_tzv_delete_own') ) )
				{
					trigger_error('UCP_MOT_TZV_NOT_AUTHORISED');
				}

				if (confirm_box(true))
				{
					$this->mot_tzv_functions->delete_destination($id);

					$this->mot_tzv_functions->create_notification('delete', $id, $destination['name']);

					$message = $this->language->lang('MOT_TZV_EVENT_DELETE_SUCCESSFUL') . '<br><br><a href="' . $this->u_action . '">' . $this->language->lang('UCP_MOT_TZV_BACK_TO_UCP') . '</a>';
					trigger_error($message);
				}
				else
				{
					confirm_box(false, '<p>' . $this->language->lang('MOT_TZV_EVENT_DELETE_CONFIRM', $destination['name']) . '</p>', build_hidden_fields([
						'id'	=> $destination['id'],
					]));
				}

				// If the user decided to not delete this Tourziel we display the following message
				$message = $this->language->lang('MOT_TZV_EVENT_NOT_DELETED') . '<br><br><a href="' . $this->u_action . '">' . $this->language->lang('UCP_MOT_TZV_BACK_TO_UCP') . '</a>';
				trigger_error($message);

				break;

			case 'edit':
				// If the user does not have the permission to edit own destinations we just display an error message
				if (!( ($this->user->data['user_id'] == $destination['user_id']) && $this->auth->acl_get('u_mot_tzv_edit_own') ) )
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

					$this->mot_tzv_functions->edit_destination($id, $input_data);

					// Create notification
					$this->mot_tzv_functions->create_notification('edit', $id, $input_data['name']);

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

				$this->template->assign_vars([
					'MOT_TZV_MANDATORY_ARR'		=> json_decode($this->config['mot_tzv_mandatory_fields']),
					'MOT_TZV_MANDATORY_ARR_JS'	=> $this->config['mot_tzv_mandatory_fields'],
					'MOT_TZV_COORD_MANDATORY'	=> $this->config['mot_tzv_maps_enable'],

					'MOT_TZV_EDIT_TZ'			=> true,

					'MOT_TZV_COUNTRY_ARR'		=> $this->mot_tzv_functions->get_country_selection((int) $destination['country'], $this->language->lang('MOT_TZV_AUSWAHL')),
					'MOT_TZV_REGION_ARR'		=> $this->mot_tzv_functions->get_region_selection((int) $destination['region'], $this->language->lang('MOT_TZV_AUSWAHL')),
					'MOT_TZV_CATEGORY_ARR'		=> $this->mot_tzv_functions->get_category_selection((int) $destination['category'], $this->language->lang('MOT_TZV_AUSWAHL')),
					'MOT_TZV_WLAN_ARR'			=> $this->mot_tzv_functions->get_wlan_selection((int) $destination['wlan'], $this->language->lang('MOT_TZV_AUSWAHL')),
					'MOT_TZV_DEST_DETAIL'		=> $destination,

					'S_BBCODE_ALLOWED'			=> true,
					'S_BBCODE_IMG'				=> true,
					'S_BBCODE_FLASH'			=> true,
				]);

				break;

			default:
				// set parameters for pagination
				$start = $this->request->variable('start', 0);
				$limit = $this->config['mot_tzv_rows_per_page'];

				$total_tz = $this->mot_tzv_functions->get_total_count_destinations('t.user_id = ' . (int) $this->user->data['user_id']);
				$destinations = $this->mot_tzv_functions->get_destinations(0, $limit, $start, false, false, 't.user_id = ' . (int) $this->user->data['user_id']);

				foreach ($destinations as &$row)
				{
						// $row['post_time'] = !empty($row['post_time']) ? $this->user->format_date($row['post_time']) : '-';
						// $row['flag'] = $row['country_name'] ? $this->tzv_flags_url . $row['country_image'] : '';
						$row['U_EDIT'] = $this->auth->acl_get('u_mot_tzv_edit_own') ? $this->u_action . '&amp;action=edit&amp;id=' . $row['id'] : '';
						$row['U_DELETE'] = $this->auth->acl_get('u_mot_tzv_delete_own') ? $this->u_action . '&amp;action=delete&amp;id=' . $row['id'] : '';
				}

				//base url for pagination
				$base_url = $this->u_action;

				// Load pagination
				$start = $this->pagination->validate_start($start, $limit, $total_tz);
				$this->pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_tz, $limit, $start);

				$this->template->assign_vars([
					'MOT_TZV_DESTINATIONS'		=> $destinations,
					'ICON_EDIT'					=> '<i class="icon acp-icon acp-icon-settings fa-cog fa-fw" title="' . $this->language->lang('EDIT') . '"></i>',
					'ICON_EDIT_DISABLED'		=> '<i class="icon acp-icon acp-icon-disabled fa-cog fa-fw" title="' . $this->language->lang('EDIT') . '"></i>',
					'ICON_DELETE'				=> '<i class="icon acp-icon acp-icon-delete fa-times-circle fa-fw" title="' . $this->language->lang('DELETE') . '"></i>',
					'ICON_DELETE_DISABLED'		=> '<i class="icon acp-icon acp-icon-disabled fa-times-circle fa-fw" title="' . $this->language->lang('DELETE') . '"></i>',
					'MOT_TZV_COPYRIGHT'			=> $this->ext_data['extra']['display-name'] . ' ' . $this->ext_data['version'] . ' &copy; Mike-on-Tour (<a href="' . $this->ext_data['homepage'] . '" target="_blank" rel="noopener">' . $this->ext_data['homepage'] . '</a>)',
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
