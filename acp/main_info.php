<?php
/**
*
* @package phpBB Extension [Tour destinations]
* @copyright (c) 2014-2021 waldkatze
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace mot\tzv\acp;

class main_info
{
	function module()
	{
		return [
			'filename'	=> '\mot\tzv\acp\main_module',
			'title'		=> 'ACP_MOT_TZV_TOURZIEL',
			'modes'		=> [
				'settings'	=> [
					'title' => 'ACP_MOT_TZV_SETTINGS',
					'auth'  => 'ext_mot/tzv && acl_a_board',
					'cat'   => ['ACP_MOT_TZV_TOURZIEL'],
				],
				'country'	=> [
					'title' => 'ACP_MOT_TZV_COUNTRY',
					'auth'  => 'ext_mot/tzv && acl_a_board',
					'cat'   => ['ACP_MOT_TZV_TOURZIEL'],
				],
				'region'	=> [
					'title' => 'ACP_MOT_TZV_REGION',
					'auth'  => 'ext_mot/tzv && acl_a_board',
					'cat'   => ['ACP_MOT_TZV_TOURZIEL'],
				],
				'category'	=> [
					'title' => 'ACP_MOT_TZV_CATEGORY',
					'auth'  => 'ext_mot/tzv && acl_a_board',
					'cat'   => ['ACP_MOT_TZV_TOURZIEL'],
				],
				'wlan'	    => [
					'title' => 'ACP_MOT_TZV_WLAN',
					'auth'  => 'ext_mot/tzv && acl_a_board',
					'cat'   => ['ACP_MOT_TZV_TOURZIEL'],
				],
				'info'	    => [
					'title' => 'ACP_MOT_TZV_INFO',
					'auth'  => 'ext_mot/tzv && acl_a_board',
					'cat'   => ['ACP_MOT_TZV_TOURZIEL'],
				],
			],
		];
	}
}
