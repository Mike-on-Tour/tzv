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

class v_0_1_1 extends \phpbb\db\migration\migration
{

	/**
	* Check for migration v_0_1_0 to be installed
	*/
	public static function depends_on()
	{
		return ['\mot\tzv\migrations\v_0_1_0'];
	}

	public function update_data()
	{
		return [
			/* ----------------
			 SCHALTER EINFÜGEN
			---------------- */
			['config.add', ['mot_tzv_googlemap_enable', 0]],
			['config.add', ['mot_tzv_ostreetmap_enable', 0]],
			['config.add', ['mot_tzv_kurviger_enable', 1]],
			['config.add', ['mot_tzv_maininfo_enable', 1]],
		];
	}

}
