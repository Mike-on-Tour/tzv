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

class v_0_2_1 extends \phpbb\db\migration\migration
{

	/**
	* Check for migration v_0_1_1 to be installed
	*/
	public static function depends_on()
	{
		return ['\mot\tzv\migrations\v_0_1_1'];
	}

	public function update_data()
	{
		return [
			['config.add', ['mot_tzv_flags_url', 'https://flagcdn.com/16x12/']],
		];
	}

	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'mot_tourziel' => [
					'creator_username'	=> ['VCHAR:255', ''],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns' => [
				$this->table_prefix . 'mot_tourziel' => [
					'creator_username',
				],
			],
		];
	}
}
