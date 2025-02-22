<?php
/**
*
* @package MoT Tour Destinations Database
* ver 1.3.0
* @copyright (c) 2022 - 2025 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace mot\tzv\notification;

class mot_tzv_tz_edited extends \phpbb\notification\type\base
{
	/**
	* Get notification type name
	*
	* @return	string
	*/
	public function get_type()
	{
		return 'mot.tzv.notification.type.notify_tz_edited';
	}

	/**
	* Notification option data (for outputting to the user)
	*
	* @var	bool|array		False if the service should use it's default data
	* 					Array of data (including keys 'id', 'lang', and 'group')
	*/
	public static $notification_option = [
		'lang'	=> 'UCP_MOT_TZV_NOTIFY_TZ_EDITED',
		'group'	=> 'UCP_MOT_TZV_NOTIFY_MOD',
	];

	/** @var \phpbb\user_loader */
	protected $user_loader;

	/** @var \phpbb\controller\helper */
	protected $helper;

	public function set_user_loader(\phpbb\user_loader $user_loader)
	{
		$this->user_loader = $user_loader;
	}

	public function set_helper(\phpbb\controller\helper $helper)
	{
		$this->helper	= $helper;
	}

	/**
	* Determines whether this notification type is visible in the UCP notifications settings tab
	*
	* @return	boolean	true = visible / false = invisible
	*/
	public function is_available()
	{
		return $this->auth->acl_get('m_mot_tzv_edit') || $this->auth->acl_get('m_mot_tzv_delete');	// visible only to moderators with permission to edit or delete tour destinations
	}

	/**
	* Returns the value of the item to be checked to be inserted into the NOTIFICATIONS_TABLE as 'item_id'
	*
	* @params		$data	specific data for this notification
	*
	* @return		integer	id of the newly created POI
	*/
	public static function get_item_id($data)
	{
		return (int) $data['tz_id'];
	}

	/**
	* Get the id of the parent
	*
	* @params		$data	specific data for this notification
	*
	* @return		integer	id of the item's parent (see function above)
	*/
	public static function get_item_parent_id($data)
	{
		return (int) $data['parent'];
	}

	/**
	* Find the users who will receive notifications
	*
	* @params		$data	specific data for this notification
	*			$options	array with options for finding users for this notification
	*
	* @return		array with users to notify
	*/
	public function find_users_for_notification($data, $options = [])
	{
		if (empty($data['user_ids']))
		{
			$data['user_ids'] = [];
		}
		$this->user_loader->load_users($data['user_ids']);
		return $this->check_user_notification_options($data['user_ids'], $options);
	}

	/**
	* Users needed to query before this notification can be displayed
	*
	* @return	array 	users_ids
	*/
	public function users_to_query()
	{
		return [];
	}

	/**
	* Get the HTML formatted title of this notification
	*
	* @return	string
	*/
	public function get_title()
	{
		return $this->language->lang('MOT_TZV_NOTIFY_TZ_EDITED', $this->get_data('tz_name'), $this->get_data('display_username'));
	}

	/**
	* Get the url to this item
	*
	* @return	string	URL
	*/
	public function get_url()
	{
		$url = $this->helper->route('mot_tzv_main', [
			'tab'	=> 'detail',
			'id'	=> $this->get_data('tz_id'),
		]);
		return $url;
	}

	/**
	* Get email template
	*
	* @return	string|bool
	*/
	public function get_email_template()
	{
		return '@mot_tzv/notify_tz_edited';
	}

	/**
	* Get email template variables
	*
	* @return	array
	*/
	public function get_email_template_variables()
	{
		$mail_vars = [
			'CREATOR'		=> strip_tags(htmlspecialchars_decode($this->get_data('creator'), `ENT_COMPAT`)),
			'TZ_NAME'		=> strip_tags(htmlspecialchars_decode($this->get_data('tz_name'), `ENT_COMPAT`)),
			'U_DETAILVIEW'	=> generate_board_url(true) . $this->helper->route('mot_tzv_event', ['id' => $this->get_data('tz_id'),], false),
		];
		return $mail_vars;
	}

	/**
	* Function for preparing the data for insertion into the 'notification_data' column of the NOTIFICATIONS_TABLE
	* (The service handles insertion)
	*
	* @param	array		$data The data for the new download
	* 		array		$pre_create_data Data from pre_create_insert_array()
	*
	*/
	public function create_insert_array($data, $pre_create_data = [])
	{
		$this->set_data('tz_id', $data['tz_id']);
		$this->set_data('tz_name', $data['tz_name']);
		$this->set_data('creator', $data['creator']);
		$this->set_data('display_username', $data['display_username']);
		parent::create_insert_array($data, $pre_create_data);
	}
}
