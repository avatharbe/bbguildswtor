<?php
/**
 * bbGuild SWTOR Extension — extension enable functional test
 *
 * @package   bbguildswtor v2.0
 * @copyright 2026 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

/**
 * Enables bbguild core, then bbguildswtor on top. Asserts:
 * - a 'swtor' row is present in bb_games
 * - bbguildswtor's classes are seeded in bb_classes for game_id='swtor'
 * - ext::BBGUILDSWTOR_VERSION matches the version declared in composer.json
 *
 * Unlike bbguildwow's equivalent test, this plugin registers no ACP
 * module of its own, so there is nothing to assert there.
 *
 * @group functional
 */
class avathar_bbguildswtor_extension_enable_test extends phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return array('avathar/bbguild', 'avathar/bbguildswtor');
	}

	private function count_rows(string $table, string $where): int
	{
		$db = $this->get_db();
		$sql = 'SELECT COUNT(*) AS cnt FROM ' . $table . ' WHERE ' . $where;
		$result = $db->sql_query($sql);
		$count = (int) $db->sql_fetchfield('cnt');
		$db->sql_freeresult($result);

		return $count;
	}

	private function get_table_prefix(): string
	{
		return self::$config['table_prefix'];
	}

	public function test_swtor_game_row_present()
	{
		$this->assertSame(1, $this->count_rows($this->get_table_prefix() . 'bb_games', "game_id = 'swtor'"));
	}

	public function test_swtor_classes_seeded()
	{
		$this->assertGreaterThan(0, $this->count_rows($this->get_table_prefix() . 'bb_classes', "game_id = 'swtor'"));
	}

	public function test_version_constant_matches_composer_json()
	{
		$composer = json_decode(file_get_contents(__DIR__ . '/../../composer.json'), true);
		$this->assertSame($composer['version'], \avathar\bbguildswtor\ext::BBGUILDSWTOR_VERSION);
	}
}
