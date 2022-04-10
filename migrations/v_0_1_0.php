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

class v_0_1_0 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['mot_tzv_enable']);
	}

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v310\extensions'];
	}

	public function update_data()
	{
		$data = [
			/* -------------------------
			   ACP-Module einfügen
			------------------------- */
			['module.add', ['acp', 'ACP_CAT_DOT_MODS', 'ACP_MOT_TZV_TOURZIEL']],
			['module.add', ['acp', 'ACP_MOT_TZV_TOURZIEL', [
				'module_basename'	=> '\mot\tzv\acp\main_module',
				'modes'				=> ['settings', 'country', 'region', 'category', 'wlan', 'info'],
			]]],

			/* -------------------------
			   CONFIG ADD
			------------------------- */
			['config.add', ['mot_tzv_enable', 1]],							// Tourziele ein/aus
			['config.add', ['mot_tzv_admin', 1]],							// Tourziele Testmodus Administrator

			/* Karten */
			['config.add', ['mot_tzv_maps_enable', 1]],						// Karte ein/aus
			['config.add', ['mot_tzv_maps_width', '800']],					// Karte Breite
			['config.add', ['mot_tzv_maps_height', '600']],					// Karte Höhe
			['config.add', ['mot_tzv_maps_zoom', '6']],						// Karte Zoom

			/* Zusatzfunktionen */
			['config.add', ['mot_tzv_stats_enable', 1]],					// Anzeige Anzahl Tourziele im Footer
			['config.add', ['mot_tzv_news_add_enable', 1]],					// Neuester Eintrag im Forum-Index anzeigen
			['config.add', ['mot_tzv_country_enable', 1]],					// Anzeige Länder im TZV-Index
			['config.add', ['mot_tzv_main_image', 1]],						// Bilder im Tourziel-Index
			['config.add', ['mot_tzv_support_enable', 0]],					// Link Support anzeigen
			['config.add', ['mot_tzv_support', 'Link zu Support']],			// Link zu Supportforum

			/* Pagination */
			['config.add', ['mot_tzv_acp_rows_per_page', 25]],				// Anzahl Zeilen pro Tabellenseite im ACP
			['config.add', ['mot_tzv_rows_per_page', 10]],					// Anzahl Zeilen pro Tabellenseite im Front end

			/* -------------------------
			   BERECHTIGUNGEN EINFÜGEN
			------------------------- */
			/* Berechtigungen USER */
			['permission.add', ['u_mot_tzv_mainview', true, ]],				// Kann Tourziel Index und Statistik sehen
			['permission.add', ['u_mot_tzv_view', true, ]],					// Kann Tourziel Index und Statistik sehen
			['permission.add', ['u_mot_tzv_add', true, ]],					// Kann neues Tourziel eintragen
			['permission.add', ['u_mot_tzv_edit_own', true, ]],				// Kann eigene Tourziele ändern
			['permission.add', ['u_mot_tzv_delete_own', true, ]],			// Kann eigene Tourziele löschen

			/* Berechtigungen MODERATOR */
			['permission.add', ['m_mot_tzv_edit', true, ]],					// Kann jedes Tourziel ändern
			['permission.add', ['m_mot_tzv_delete', true, ]],				// Kann jedes Tourziel löschen
		];

		// Set role permissions
		if ($this->role_exists('ROLE_USER_FULL'))
		{
			$data[] = ['permission.permission_set', ['ROLE_USER_FULL', 'u_mot_tzv_mainview']];			// Kann Tourziel Index und Statistik sehen
			$data[] = ['permission.permission_set', ['ROLE_USER_FULL', 'u_mot_tzv_view']];				// Kann Tourziel Index und Statistik sehen
			$data[] = ['permission.permission_set', ['ROLE_USER_FULL', 'u_mot_tzv_add']];				// Kann Tourziel eintragen
			$data[] = ['permission.permission_set', ['ROLE_USER_FULL', 'u_mot_tzv_edit_own']];			// Kann eigene Tourziele ändern
			$data[] = ['permission.permission_set', ['ROLE_USER_FULL', 'u_mot_tzv_delete_own']];		// Kann eigene Tourziele löschen
		}

		if ($this->role_exists('ROLE_USER_STANDARD'))
		{
			$data[] = ['permission.permission_set', ['ROLE_USER_STANDARD', 'u_mot_tzv_mainview']];		// Kann Tourziel Index und Statistik sehen
			$data[] = ['permission.permission_set', ['ROLE_USER_STANDARD', 'u_mot_tzv_view']];			// Kann Tourziel Index und Statistik sehen
			$data[] = ['permission.permission_set', ['ROLE_USER_STANDARD', 'u_mot_tzv_add']];			// Kann Tourziel eintragen
			$data[] = ['permission.permission_set', ['ROLE_USER_STANDARD', 'u_mot_tzv_edit_own']];		// Kann eigene Tourziele ändern
			$data[] = ['permission.permission_set', ['ROLE_USER_STANDARD', 'u_mot_tzv_delete_own']];	// Kann eigene Tourziele löschen
		}

		if ($this->role_exists('ROLE_MOD_FULL'))
		{
			$data[] = ['permission.permission_set', ['ROLE_MOD_FULL', 'm_mot_tzv_edit']];				// Kann jedes Tourziel ändern
			$data[] = ['permission.permission_set', ['ROLE_MOD_FULL', 'm_mot_tzv_delete']];				// Kann jedes Tourziel löschen
		}

			/* ------------------------------
			   DATEN IN TABELLE EINFÜGEN
			------------------------------ */
		$data[] = ['custom', [
				[&$this, 'country_install_data']
			]];
		$data[] = ['custom', [
				[&$this, 'region_install_data']
			]];
		$data[] = ['custom', [
				[&$this, 'cats_install_data']
			]];
		$data[] = ['custom', [
				[&$this, 'wlan_install_data']
			]];

		return $data;
	}

	public function update_schema()
	{
		return [
			'add_tables' => [
			/* ------------------------------
			   TABELLEN EINFÜGEN
			------------------------------ */
				$this->table_prefix . 'mot_tourziel' => [
					'COLUMNS'		    => [
						'id'		        => ['UINT', null, 'auto_increment'],
						'name'	            => ['VCHAR:50', ''],
						'street'	       	=> ['VCHAR:50', ''],
						'postalcode'       	=> ['VCHAR:10', ''],
						'city'	          	=> ['VCHAR:50', ''],
						'country'      	   	=> ['UINT', 0],
						'region'	     	=> ['UINT', 0],
						'category'	        => ['UINT', 0],
						'wlan'              => ['UINT', 0],
						'telephone'	        => ['VCHAR:30', ''],
						'email'	        	=> ['VCHAR:50', ''],
						'homepage'	     	=> ['VCHAR:50', ''],
						'maps_lat'	    	=> ['VCHAR:20', ''],
						'maps_lon'	    	=> ['VCHAR:20', ''],
						'user_id'		    => ['UINT', 0],
						'message'	        => ['TEXT', ''],
						'bbcode_uid'		=> ['VCHAR:8', ''],
						'bbcode_bitfield'	=> ['VCHAR:127', ''],
						'bbcode_options'	=> ['VCHAR:127', ''],
						'enable_magic_url'  => ['TINT:1', null],
						'enable_smilies'	=> ['TINT:1', null],
						'enable_bbcode'	    => ['TINT:1', null],
						'post_time'         => ['TIMESTAMP', 0],
					],
					'PRIMARY_KEY'	=> ['id'],
					'KEYS'	=> [
						'name'		=> ['UNIQUE', 'name'],
					],
				],

				$this->table_prefix . 'mot_tourziel_country' => [
					'COLUMNS'		=> [
						'country_id'    => ['UINT', null, 'auto_increment'],
						'country_name'  => ['VCHAR:100', ''],
						'country_image' => ['VCHAR:25', ''],
					],
					'PRIMARY_KEY'	=> ['country_id'],
				],

				$this->table_prefix . 'mot_tourziel_region'	=> [
					'COLUMNS'     => [
						'region_id'   => ['UINT', null, 'auto_increment'],
						'region_name' => ['VCHAR:100', ''],
					],
					'PRIMARY_KEY' => ['region_id'],
				],

				$this->table_prefix . 'mot_tourziel_cats' => [
					'COLUMNS'  => [
						'cat_id'   => ['UINT', null, 'auto_increment'],
						'cat_name' => ['VCHAR:100', ''],
					],
					'PRIMARY_KEY' => ['cat_id'],
				],

				$this->table_prefix . 'mot_tourziel_wlan' => [
					'COLUMNS'   => [
						'wlan_id'   => ['UINT', null, 'auto_increment'],
						'wlan_name' => ['VCHAR:100', ''],
					],
					'PRIMARY_KEY' => ['wlan_id'],
				],

			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables' => [
				$this->table_prefix . 'mot_tourziel',
				$this->table_prefix . 'mot_tourziel_country',
				$this->table_prefix . 'mot_tourziel_region',
				$this->table_prefix . 'mot_tourziel_cats',
				$this->table_prefix . 'mot_tourziel_wlan',
			],
		];
	}

	public function country_install_data()
	{
		if ($this->db_tools->sql_table_exists($this->table_prefix . 'mot_tourziel_country'))
		{
			$sql_ary = [
				['country_id' => '1', 'country_name' => 'Deutschland', 'country_image' => 'de.png'],
				['country_id' => '2', 'country_name' => 'Österreich', 'country_image' => 'at.png'],
				['country_id' => '3', 'country_name' => 'Schweiz', 'country_image' => 'ch.png'],
				['country_id' => '4', 'country_name' => 'Belgien', 'country_image' => 'be.png'],
				['country_id' => '5', 'country_name' => 'Tschechien', 'country_image' => 'cz.png'],
				['country_id' => '6', 'country_name' => 'Polen', 'country_image' => 'pl.png'],
				['country_id' => '7', 'country_name' => 'Dänemark', 'country_image' => 'dk.png'],
				['country_id' => '8', 'country_name' => 'Niederlande', 'country_image' => 'nl.png'],
				['country_id' => '9', 'country_name' => 'Frankreich', 'country_image' => 'fr.png'],
				['country_id' => '10', 'country_name' => 'Luxemburg', 'country_image' => 'lu.png'],
			];
			$this->db->sql_multi_insert($this->table_prefix . 'mot_tourziel_country', $sql_ary);
		}
	}

	public function region_install_data()
	{
		if ($this->db_tools->sql_table_exists($this->table_prefix . 'mot_tourziel_region'))
		{
			$sql_ary = [
				['region_id' => '1', 'region_name' => 'Baden-Württemberg'],
				['region_id' => '2', 'region_name' => 'Bayern'],
				['region_id' => '3', 'region_name' => 'Berlin'],
				['region_id' => '4', 'region_name' => 'Brandenburg'],
				['region_id' => '5', 'region_name' => 'Bremen'],
				['region_id' => '6', 'region_name' => 'Hamburg'],
				['region_id' => '7', 'region_name' => 'Hessen'],
				['region_id' => '8', 'region_name' => 'Mecklenburg-Vorpommern'],
				['region_id' => '9', 'region_name' => 'Niedersachsen'],
				['region_id' => '10', 'region_name' => 'Nordrhein-Westfalen'],
				['region_id' => '11', 'region_name' => 'Rheinland-Pfalz'],
				['region_id' => '12', 'region_name' => 'Saarland'],
				['region_id' => '13', 'region_name' => 'Sachsen'],
				['region_id' => '14', 'region_name' => 'Sachsen-Anhalt'],
				['region_id' => '15', 'region_name' => 'Schleswig-Holstein'],
				['region_id' => '16', 'region_name' => 'Thüringen'],
			];
			$this->db->sql_multi_insert($this->table_prefix . 'mot_tourziel_region', $sql_ary);
		}
	}

	public function cats_install_data()
	{
		if ($this->db_tools->sql_table_exists($this->table_prefix . 'mot_tourziel_cats'))
		{
			$sql_ary = [
				['cat_id' => '1', 'cat_name' => 'Hotel'],
				['cat_id' => '2', 'cat_name' => 'Pension'],
				['cat_id' => '3', 'cat_name' => 'Gaststätte'],
				['cat_id' => '4', 'cat_name' => 'Bikertreffpunkt'],
				['cat_id' => '5', 'cat_name' => 'Sehenswürdigkeit'],
				['cat_id' => '6', 'cat_name' => 'Eiscafe'],
				['cat_id' => '7', 'cat_name' => 'Freizeitpark'],
				['cat_id' => '8', 'cat_name' => 'Museum'],
			];
			$this->db->sql_multi_insert($this->table_prefix . 'mot_tourziel_cats', $sql_ary);
		}
	}

	public function wlan_install_data()
	{
		if ($this->db_tools->sql_table_exists($this->table_prefix . 'mot_tourziel_wlan'))
		{
			$sql_ary = [
				['wlan_id' => '1', 'wlan_name' => 'WLAN unbekannt'],
				['wlan_id' => '2', 'wlan_name' => 'WLAN mit Passwort'],
				['wlan_id' => '3', 'wlan_name' => 'WLAN offen'],
			];
			$this->db->sql_multi_insert($this->table_prefix . 'mot_tourziel_wlan', $sql_ary);
		}
	}

	private function role_exists($role)
	{
		$sql = 'SELECT role_id
			FROM ' . ACL_ROLES_TABLE . "
			WHERE role_name = '" . $this->db->sql_escape($role) . "'";
		$result = $this->db->sql_query_limit($sql, 1);
		$role_id = $this->db->sql_fetchfield('role_id');
		$this->db->sql_freeresult($result);
		return $role_id;
	}
}
