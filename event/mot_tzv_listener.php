<?php
/**
*
* @package MoT Tour Destinations Database
* ver 1.3.0
* @copyright (c) 2022 - 2025 Mike-on-Tour
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

	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \mot\tzv\includes\mot_tzv_functions */
	protected $mot_tzv_functions;

	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\extension\manager $phpbb_extension_manager, \phpbb\controller\helper $helper,
								\phpbb\template\template $template, \mot\tzv\includes\mot_tzv_functions $mot_tzv_functions)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->helper = $helper;
		$this->template = $template;
		$this->mot_tzv_functions = $mot_tzv_functions;

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
			'lang_set' => 'mot_tzv_common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function add_page_header_link()
	{
		if ($this->auth->acl_get('u_mot_tzv_mainview'))
		{
			$row = $this->mot_tzv_functions->get_destinations(0, 1, 0, true);

			if (!empty($row))
			{
				$row = $row[0];
				$row['url'] = $this->helper->route('mot_tzv_main', [
					'tab'	=> 'detail',
					'id'	 => $row['id'],
				]);
			}

			$this->template->assign_vars([
				'U_MOT_TZV'		 			=> $this->helper->route('mot_tzv_main'),		// Link for navbar

				'MOT_TZV_ENABLE'			=> $this->config['mot_tzv_enable'],
				'MOT_TZV_ADMIN'				=> $this->config['mot_tzv_admin'],

				'MOT_TZV_NEWS_ADD_ENABLE'	=> $this->config['mot_tzv_news_add_enable'],
				'MOT_TZV_LAST_DEST'			=> $row,
			]);
		}
	}

	public function tourziel_count()
	{
		if ($this->auth->acl_get('u_mot_tzv_mainview'))
		{
			$this->template->assign_vars([
				'MOT_TZV_STATS_ENABLE'		=> $this->config['mot_tzv_stats_enable'],
				'MOT_TZV_COUNT_TOURZIEL'	=> $this->mot_tzv_functions->get_total_count_destinations(),
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
		$this->mot_tzv_functions->update_creator_col($user_rows);
	}
}
