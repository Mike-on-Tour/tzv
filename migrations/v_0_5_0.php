<?php
/**
*
* @package phpBB Extension [Adressverwaltung - Tourziele]
* @copyright (c) 2014-2021 waldkatze
* @copyright (c) 2022 Mike-on-Tour
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace mot\tzv\migrations;

class v_0_5_0 extends \phpbb\db\migration\migration
{

	/**
	* Check for migration v_0_3_0 to be installed
	*/
	public static function depends_on()
	{
		return ['\mot\tzv\migrations\v_0_3_0'];
	}

	public function update_data()
	{
		return [
			// add new config variable
			['config.add', ['mot_tzv_komoot_enable', 0]],
		];
	}
}
