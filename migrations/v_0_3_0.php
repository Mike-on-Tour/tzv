<?php
/**
*
* @package phpBB Extension [Tour destinations]
* @copyright (c) 2014-2021 waldkatze
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace mot\tzv\migrations;

class v_0_3_0 extends \phpbb\db\migration\migration
{

	/**
	* Check for migration v_0_2_1 to be installed
	*/
	public static function depends_on()
	{
		return ['\mot\tzv\migrations\v_0_2_1'];
	}

	public function update_data()
	{
		return [
			// add new config variables
			['config.add', ['mot_tzv_map_lat', '50.5']],
			['config.add', ['mot_tzv_map_lon', '10.0']],
			['config.add', ['mot_tzv_map_zoom', 5]],
			['config.add', ['mot_tzv_map_enable_clusters', 0]],
			['config.add', ['mot_tzv_latest_tz_view', 1]],
			['config.add', ['mot_tzv_list_tz_view', 0]],

			// add UCP modules
			['module.add', ['ucp', false, 'UCP_MOT_TZV_TOURZIEL']],
			['module.add', ['ucp', 'UCP_MOT_TZV_TOURZIEL', [
				'module_basename'	=> '\mot\tzv\ucp\main_module',
				'modes'				=> ['summary'],
			]]],
		];
	}
}
