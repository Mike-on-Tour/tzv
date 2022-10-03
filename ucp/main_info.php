<?php
/**
*
* @package phpBB Extension [Tour destinations]
* @copyright (c) 2014-2021 waldkatze
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace mot\tzv\ucp;

class main_info
{
	function module()
	{
		return [
			'filename'	=> '\mot\tzv\ucp\main_module',
			'title'		=> 'UCP_MOT_TZV_TOURZIEL',
			'modes'		=> [
				'summary'	=> [
					'title' => 'UCP_MOT_TZV_SUMMARY',
					'auth'  => 'ext_mot/tzv',
					'cat'   => ['UCP_MOT_TZV_TOURZIEL'],
				],
			],
		];
	}
}
