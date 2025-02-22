<?php
/**
*
* @package MoT Tour Destinations Database
* ver 1.3.0
* @copyright (c) 2022 - 2025 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace mot\tzv\migrations;

class v_1_3_0 extends \phpbb\db\migration\migration
{

	/**
	* Check for migration v_0_5_2 to be installed
	*/
	public static function depends_on()
	{
		return ['\mot\tzv\migrations\v_0_6_0'];
	}

	public function update_data()
	{
		return [
			// add new config variable
			['config.add', ['mot_tzv_mandatory_fields', '[1,2,3,10]']],
		];
	}
}
