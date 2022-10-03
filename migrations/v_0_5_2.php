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

class v_0_5_2 extends \phpbb\db\migration\migration
{

	/**
	* Check for migration v_0_5_0 to be installed
	*/
	public static function depends_on()
	{
		return ['\mot\tzv\migrations\v_0_5_0'];
	}

	public function update_schema()
	{
		return [
			'change_columns' => [
				$this->table_prefix . 'mot_tourziel' => [
					'homepage'	=> ['VCHAR:255', ''],
				],
			],
		];
	}

	public function revert_schema()
	{
		// Return empty array since we do not need to revert the column 'homepage' back to 50 characters
		return [];
	}
}
