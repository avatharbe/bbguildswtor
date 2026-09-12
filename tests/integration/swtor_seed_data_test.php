<?php
/**
 * bbGuild SWTOR Extension — seed data structural integration test
 *
 * @package   bbguildswtor v2.0
 * @copyright 2026 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

/**
 * bbguildswtor has no external API and no HTTP integration surface (see
 * tests/integration-tests.md's "Notes for other plugins" — for non-API
 * plugins the only integration tests of value are fixture-loading
 * correctness). This exercises the real, installed seed data with a
 * real DB connection and asserts structural correctness beyond what the
 * unit and functional tests check:
 * - every class_id has a valid (non-empty, known) class_armor_type
 * - every race_id's race_faction_id is either 0 or a faction_id that
 *   actually exists in bb_factions for game_id='swtor'
 * - no duplicate class_id or race_id per game_id
 * - every seeded class_id/race_id has a bb_language row for each
 *   language this installer seeds (en, de, fr)
 *
 * Per the integration-tests.md correction (2026-09), this extends
 * phpbb_functional_test_case directly (not phpbb_database_test_case) —
 * that is what gives a real DB connection and a real installed
 * extension in this test framework. No HTTP routing is driven here.
 *
 * @group integration
 */
class avathar_bbguildswtor_swtor_seed_data_test extends phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return array('avathar/bbguild', 'avathar/bbguildswtor');
	}

	private function get_table_prefix(): string
	{
		return self::$config['table_prefix'];
	}

	public function test_every_class_has_valid_armor_type()
	{
		$db = $this->get_db();
		$valid = array('HEAVY', 'LEATHER', 'AUGMENTED', 'ROBE');

		$sql = 'SELECT class_id, class_armor_type FROM ' . $this->get_table_prefix() . "bb_classes WHERE game_id = 'swtor'";
		$result = $db->sql_query($sql);
		$rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$this->assertNotEmpty($rows, 'swtor classes should be seeded');
		foreach ($rows as $row)
		{
			$this->assertContains($row['class_armor_type'], $valid, "class_id {$row['class_id']} has an invalid armor type: {$row['class_armor_type']}");
		}
	}

	public function test_every_race_references_a_valid_faction()
	{
		$db = $this->get_db();

		$sql = 'SELECT faction_id FROM ' . $this->get_table_prefix() . "bb_factions WHERE game_id = 'swtor'";
		$result = $db->sql_query($sql);
		$faction_ids = array_map('intval', array_column($db->sql_fetchrowset($result), 'faction_id'));
		$db->sql_freeresult($result);

		$valid_factions = array_merge(array(0), $faction_ids);

		$sql = 'SELECT race_id, race_faction_id FROM ' . $this->get_table_prefix() . "bb_races WHERE game_id = 'swtor'";
		$result = $db->sql_query($sql);
		$rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$this->assertNotEmpty($rows, 'swtor races should be seeded');
		foreach ($rows as $row)
		{
			$this->assertContains((int) $row['race_faction_id'], $valid_factions, "race_id {$row['race_id']} references faction_id {$row['race_faction_id']}, which is not 0 and not a known swtor faction");
		}
	}

	public function test_no_duplicate_class_ids_per_game()
	{
		$db = $this->get_db();
		$sql = 'SELECT class_id, COUNT(*) AS cnt FROM ' . $this->get_table_prefix() . "bb_classes WHERE game_id = 'swtor' GROUP BY class_id HAVING COUNT(*) > 1";
		$result = $db->sql_query($sql);
		$dupes = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$this->assertSame(array(), $dupes, 'No class_id should be duplicated for game_id=swtor');
	}

	public function test_no_duplicate_race_ids_per_game()
	{
		$db = $this->get_db();
		$sql = 'SELECT race_id, COUNT(*) AS cnt FROM ' . $this->get_table_prefix() . "bb_races WHERE game_id = 'swtor' GROUP BY race_id HAVING COUNT(*) > 1";
		$result = $db->sql_query($sql);
		$dupes = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$this->assertSame(array(), $dupes, 'No race_id should be duplicated for game_id=swtor');
	}

	public function test_every_class_has_a_language_row_per_seeded_language()
	{
		$db = $this->get_db();
		$languages = array('en', 'de', 'fr');

		$sql = 'SELECT DISTINCT class_id FROM ' . $this->get_table_prefix() . "bb_classes WHERE game_id = 'swtor'";
		$result = $db->sql_query($sql);
		$class_ids = array_map('intval', array_column($db->sql_fetchrowset($result), 'class_id'));
		$db->sql_freeresult($result);

		$this->assertNotEmpty($class_ids);

		foreach ($class_ids as $class_id)
		{
			foreach ($languages as $language)
			{
				$sql = 'SELECT id FROM ' . $this->get_table_prefix() . "bb_language WHERE game_id = 'swtor' AND attribute = 'class' AND attribute_id = " . $class_id . " AND language = '" . $db->sql_escape($language) . "'";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				$this->assertNotFalse($row, "class_id $class_id is missing a bb_language row for language '$language'");
			}
		}
	}

	public function test_every_race_has_a_language_row_per_seeded_language()
	{
		$db = $this->get_db();
		$languages = array('en', 'de', 'fr');

		$sql = 'SELECT DISTINCT race_id FROM ' . $this->get_table_prefix() . "bb_races WHERE game_id = 'swtor'";
		$result = $db->sql_query($sql);
		$race_ids = array_map('intval', array_column($db->sql_fetchrowset($result), 'race_id'));
		$db->sql_freeresult($result);

		$this->assertNotEmpty($race_ids);

		foreach ($race_ids as $race_id)
		{
			foreach ($languages as $language)
			{
				$sql = 'SELECT id FROM ' . $this->get_table_prefix() . "bb_language WHERE game_id = 'swtor' AND attribute = 'race' AND attribute_id = " . $race_id . " AND language = '" . $db->sql_escape($language) . "'";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				$this->assertNotFalse($row, "race_id $race_id is missing a bb_language row for language '$language'");
			}
		}
	}
}
