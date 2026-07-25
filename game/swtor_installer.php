<?php
/**
 * SWTOR Installer
 *
 * Installs Star Wars: The Old Republic factions, classes, races, and roles.
 *
 * @package   bbguildswtor v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguildswtor\game;

use avathar\bbguild\model\games\abstract_game_install;

class swtor_installer extends abstract_game_install
{
	/**
	 * Installs SWTOR factions
	 */
	protected function install_factions()
	{

		$this->db->sql_query('DELETE FROM ' . $this->table('bb_factions_table') . " WHERE game_id = '" . $this->db->sql_escape($this->game_id) . "'");
		$sql_ary = array();
		$sql_ary[] = array('game_id' => $this->game_id, 'faction_id' => 1, 'faction_name' => 'Galactic Republic');
		$sql_ary[] = array('game_id' => $this->game_id, 'faction_id' => 2, 'faction_name' => 'Jedi Order');
		$sql_ary[] = array('game_id' => $this->game_id, 'faction_id' => 3, 'faction_name' => 'Sith Empire');
		$sql_ary[] = array('game_id' => $this->game_id, 'faction_id' => 4, 'faction_name' => 'Sith Lords');
		$this->db->sql_multi_insert($this->table('bb_factions_table'), $sql_ary);
	}

	/**
	 * Installs SWTOR classes with translations (en, de, fr)
	 */
	protected function install_classes()
	{

		$this->db->sql_query('DELETE FROM ' . $this->table('bb_classes_table') . " WHERE game_id = '" . $this->db->sql_escape($this->game_id) . "'");
		$sql_ary = array();
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 0, 'class_faction_id' => 1, 'class_armor_type' => 'HEAVY',     'class_min_level' => 1, 'class_max_level' => 55, 'colorcode' => '#999999', 'imagename' => 'swtor_unknown');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 1, 'class_faction_id' => 1, 'class_armor_type' => 'HEAVY',     'class_min_level' => 1, 'class_max_level' => 55, 'colorcode' => '#66CCFF', 'imagename' => 'swtor_trooper');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 2, 'class_faction_id' => 1, 'class_armor_type' => 'LEATHER',   'class_min_level' => 1, 'class_max_level' => 55, 'colorcode' => '#AFDCEC', 'imagename' => 'swtor_smuggler');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 3, 'class_faction_id' => 2, 'class_armor_type' => 'AUGMENTED', 'class_min_level' => 1, 'class_max_level' => 55, 'colorcode' => '#437C17', 'imagename' => 'swtor_jedi');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 4, 'class_faction_id' => 2, 'class_armor_type' => 'ROBE',      'class_min_level' => 1, 'class_max_level' => 55, 'colorcode' => '#66CC66', 'imagename' => 'swtor_consul');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 5, 'class_faction_id' => 3, 'class_armor_type' => 'HEAVY',     'class_min_level' => 1, 'class_max_level' => 55, 'colorcode' => '#CC0033', 'imagename' => 'swtor_hunter');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 6, 'class_faction_id' => 4, 'class_armor_type' => 'LEATHER',   'class_min_level' => 1, 'class_max_level' => 55, 'colorcode' => '#FF6600', 'imagename' => 'swtor_warrior');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 7, 'class_faction_id' => 3, 'class_armor_type' => 'AUGMENTED', 'class_min_level' => 1, 'class_max_level' => 55, 'colorcode' => '#996699', 'imagename' => 'swtor_agent');
		$sql_ary[] = array('game_id' => $this->game_id, 'class_id' => 8, 'class_faction_id' => 4, 'class_armor_type' => 'ROBE',      'class_min_level' => 1, 'class_max_level' => 55, 'colorcode' => '#660033', 'imagename' => 'swtor_inquisitor');
		$this->db->sql_multi_insert($this->table('bb_classes_table'), $sql_ary);
		unset($sql_ary);

		$this->db->sql_query('DELETE FROM ' . $this->table('bb_language_table') . " WHERE game_id = '" . $this->db->sql_escape($this->game_id) . "' AND attribute = 'class'");
		$sql_ary = array();

		// en
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0, 'language' => 'en', 'attribute' => 'class', 'name' => 'Unknown',         'name_short' => 'T7-01');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' => 'en', 'attribute' => 'class', 'name' => 'Trooper',         'name_short' => 'Trooper');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' => 'en', 'attribute' => 'class', 'name' => 'Smuggler',        'name_short' => 'Smuggler');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' => 'en', 'attribute' => 'class', 'name' => 'Jedi Knight',     'name_short' => 'Jedi');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' => 'en', 'attribute' => 'class', 'name' => 'Jedi Consular',   'name_short' => 'Consul');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' => 'en', 'attribute' => 'class', 'name' => 'Bounty Hunter',   'name_short' => 'Hunter');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' => 'en', 'attribute' => 'class', 'name' => 'Sith Warrior',    'name_short' => 'Warrior');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7, 'language' => 'en', 'attribute' => 'class', 'name' => 'Imperial Agent',  'name_short' => 'Agent');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8, 'language' => 'en', 'attribute' => 'class', 'name' => 'Sith Inquisitor', 'name_short' => 'Inquisitor');

		// de
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0, 'language' => 'de', 'attribute' => 'class', 'name' => 'Unbekannt',        'name_short' => 'Unbekannt');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' => 'de', 'attribute' => 'class', 'name' => 'Soldat',            'name_short' => 'Soldat');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' => 'de', 'attribute' => 'class', 'name' => 'Schmuggler',        'name_short' => 'Schmuggler');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' => 'de', 'attribute' => 'class', 'name' => 'Jedi-Ritter',       'name_short' => 'Ritter');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' => 'de', 'attribute' => 'class', 'name' => 'Jedi Botschafter',  'name_short' => 'Botschafter');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' => 'de', 'attribute' => 'class', 'name' => 'Kopfgeldjäger',     'name_short' => 'Kopfgeldjäger');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' => 'de', 'attribute' => 'class', 'name' => 'Sith Krieger',      'name_short' => 'Krieger');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7, 'language' => 'de', 'attribute' => 'class', 'name' => 'Imperialer Agent',  'name_short' => 'Imperialer Agent');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8, 'language' => 'de', 'attribute' => 'class', 'name' => 'Sith Inquisitor',   'name_short' => 'Inquisitor');

		// fr
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Inconnu',            'name_short' => 'Inconnu');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Soldat',              'name_short' => 'Soldat');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Contrebandier',       'name_short' => 'Contrebandier');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Chevalier Jedi',      'name_short' => 'Chevalier');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Jedi Consulaire',     'name_short' => 'Consulaire');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Chasseur de Primes',  'name_short' => 'Chasseur');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Guerrier Sith',       'name_short' => 'Guerrier');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Agent Imperial',      'name_short' => 'Agent');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8, 'language' => 'fr', 'attribute' => 'class', 'name' => 'Inquisiteur Sith',    'name_short' => 'Inquisiteur');

		$this->db->sql_multi_insert($this->table('bb_language_table'), $sql_ary);
	}

	/**
	 * Installs SWTOR races with translations (en, de, fr)
	 */
	protected function install_races()
	{

		$this->db->sql_query('DELETE FROM ' . $this->table('bb_races_table') . " WHERE game_id = '" . $this->db->sql_escape($this->game_id) . "'");
		$sql_ary = array();
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 0,  'race_faction_id' => 1, 'image_female' => ' ',                     'image_male' => ' ');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 1,  'race_faction_id' => 2, 'image_female' => 'swtor_miraluka_female',  'image_male' => 'swtor_miraluka_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 2,  'race_faction_id' => 1, 'image_female' => 'swtor_twilek_female',    'image_male' => 'swtor_twilek_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 3,  'race_faction_id' => 2, 'image_female' => 'swtor_zabrak_female',    'image_male' => 'swtor_zabrak_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 4,  'race_faction_id' => 1, 'image_female' => 'swtor_miralian_female',  'image_male' => 'swtor_miralian_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 5,  'race_faction_id' => 1, 'image_female' => 'swtor_human_female',     'image_male' => 'swtor_human_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 6,  'race_faction_id' => 3, 'image_female' => 'swtor_chiss_female',     'image_male' => 'swtor_chiss_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 7,  'race_faction_id' => 3, 'image_female' => 'swtor_rattataki_female', 'image_male' => 'swtor_rattataki_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 8,  'race_faction_id' => 3, 'image_female' => 'swtor_redsith_female',   'image_male' => 'swtor_redsith_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 9,  'race_faction_id' => 3, 'image_female' => 'swtor_cathar_female',    'image_male' => 'swtor_cathar_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 10, 'race_faction_id' => 3, 'image_female' => 'swtor_cyborg_female',    'image_male' => 'swtor_cyborg_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 11, 'race_faction_id' => 1, 'image_female' => 'swtor_togruta_female',   'image_male' => 'swtor_togruta_male');
		$sql_ary[] = array('game_id' => $this->game_id, 'race_id' => 12, 'race_faction_id' => 1, 'image_female' => 'swtor_nautolan_female',  'image_male' => 'swtor_nautolan_male');
		$this->db->sql_multi_insert($this->table('bb_races_table'), $sql_ary);
		unset($sql_ary);

		$this->db->sql_query('DELETE FROM ' . $this->table('bb_language_table') . " WHERE game_id = '" . $this->db->sql_escape($this->game_id) . "' AND attribute = 'race'");
		$sql_ary = array();

		// en
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0,  'language' => 'en', 'attribute' => 'race', 'name' => 'Unknown',    'name_short' => 'T7-01');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1,  'language' => 'en', 'attribute' => 'race', 'name' => 'Miraluka',   'name_short' => 'Miraluka');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2,  'language' => 'en', 'attribute' => 'race', 'name' => 'Twi\'lek',     'name_short' => 'Twi\'lek');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3,  'language' => 'en', 'attribute' => 'race', 'name' => 'Zabrak',     'name_short' => 'Zabrak');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4,  'language' => 'en', 'attribute' => 'race', 'name' => 'Mirialan',   'name_short' => 'Mirialan');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5,  'language' => 'en', 'attribute' => 'race', 'name' => 'Human',      'name_short' => 'Human');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6,  'language' => 'en', 'attribute' => 'race', 'name' => 'Chiss',      'name_short' => 'Chiss');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7,  'language' => 'en', 'attribute' => 'race', 'name' => 'Rattataki',  'name_short' => 'Rattataki');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8,  'language' => 'en', 'attribute' => 'race', 'name' => 'Sith Pureblood', 'name_short' => 'Pureblood');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9,  'language' => 'en', 'attribute' => 'race', 'name' => 'Cathar',     'name_short' => 'Cathar');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' => 'en', 'attribute' => 'race', 'name' => 'Cyborg',     'name_short' => 'Cyborg');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' => 'en', 'attribute' => 'race', 'name' => 'Togruta',    'name_short' => 'Togruta');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 12, 'language' => 'en', 'attribute' => 'race', 'name' => 'Nautolan',   'name_short' => 'Nautolan');

		// de
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0,  'language' => 'de', 'attribute' => 'race', 'name' => 'Unbekannt',          'name_short' => 'T7-01');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1,  'language' => 'de', 'attribute' => 'race', 'name' => 'Miraluka',           'name_short' => 'Miraluka');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2,  'language' => 'de', 'attribute' => 'race', 'name' => 'Twi\'lek',             'name_short' => 'Twi\'lek');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3,  'language' => 'de', 'attribute' => 'race', 'name' => 'Zabrak',             'name_short' => 'Zabrak');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4,  'language' => 'de', 'attribute' => 'race', 'name' => 'Mirialan',           'name_short' => 'Mirialan');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5,  'language' => 'de', 'attribute' => 'race', 'name' => 'Mensch',             'name_short' => 'Mensch');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6,  'language' => 'de', 'attribute' => 'race', 'name' => 'Chiss',              'name_short' => 'Chiss');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7,  'language' => 'de', 'attribute' => 'race', 'name' => 'Rattataki',          'name_short' => 'Rattataki');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8,  'language' => 'de', 'attribute' => 'race', 'name' => 'Reinblütige Sith',   'name_short' => 'Sith');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9,  'language' => 'de', 'attribute' => 'race', 'name' => 'Cathar',             'name_short' => 'Cathar');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' => 'de', 'attribute' => 'race', 'name' => 'Cyborg',             'name_short' => 'Cyborg');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' => 'de', 'attribute' => 'race', 'name' => 'Togruta',            'name_short' => 'Togruta');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 12, 'language' => 'de', 'attribute' => 'race', 'name' => 'Nautolaner',         'name_short' => 'Nautolan');

		// fr
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 0,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Inconnu',            'name_short' => 'T7-01');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 1,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Miraluka',           'name_short' => 'Miraluka');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 2,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Twi\'lek',             'name_short' => 'Twi\'lek');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 3,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Zabrak',             'name_short' => 'Zabrak');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 4,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Mirialan',           'name_short' => 'Mirialan');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 5,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Humain',             'name_short' => 'Humain');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 6,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Chiss',              'name_short' => 'Chiss');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 7,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Rattataki',          'name_short' => 'Rattataki');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 8,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Sith au sang pur',   'name_short' => 'Sith');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 9,  'language' => 'fr', 'attribute' => 'race', 'name' => 'Cathar',             'name_short' => 'Cathar');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 10, 'language' => 'fr', 'attribute' => 'race', 'name' => 'Cyborg',             'name_short' => 'Cyborg');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 11, 'language' => 'fr', 'attribute' => 'race', 'name' => 'Togruta',            'name_short' => 'Togruta');
		$sql_ary[] = array('game_id' => $this->game_id, 'attribute_id' => 12, 'language' => 'fr', 'attribute' => 'race', 'name' => 'Nautolan',           'name_short' => 'Nautolan');

		$this->db->sql_multi_insert($this->table('bb_language_table'), $sql_ary);
	}
}
