<?php
/**
 * @package bbGuild SWTOR Extension
 * @copyright (c) 2026 avathar.be
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace avathar\bbguildswtor\tests\game;

use PHPUnit\Framework\TestCase;
use avathar\bbguildswtor\game\swtor_installer;

class swtor_installer_test extends TestCase
{
	/** @var swtor_installer */
	protected $installer;

	/** @var array Captured sql_multi_insert calls: array of [table, data] */
	protected $inserted = array();

	/** @var \PHPUnit\Framework\MockObject\MockObject */
	protected $db;

	protected function setUp(): void
	{
		parent::setUp();

		$this->inserted = array();

		$this->db = $this->createMock(\phpbb\db\driver\driver_interface::class);

		// Capture sql_multi_insert calls
		$this->db->method('sql_multi_insert')
			->willReturnCallback(function ($table, $data) {
				$this->inserted[] = array('table' => $table, 'data' => $data);
			});

		// sql_query (DELETE statements) — no-op
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_escape')->willReturnCallback(function ($v) { return $v; });

		$cache = $this->createMock(\phpbb\cache\driver\driver_interface::class);
		$config = new \phpbb\config\config(array());
		$user = $this->getMockBuilder(\phpbb\user::class)
			->disableOriginalConstructor()
			->getMock();

		$this->installer = new swtor_installer($this->db, $cache, $config, $user);

		// Set table_names and game_id via reflection (normally set by install())
		$ref = new \ReflectionClass($this->installer);

		$tn = $ref->getProperty('table_names');
		$tn->setAccessible(true);
		$tn->setValue($this->installer, array(
			'bb_factions_table'  => 'phpbb_bb_factions',
			'bb_classes_table'   => 'phpbb_bb_classes',
			'bb_races_table'     => 'phpbb_bb_races',
			'bb_language_table'  => 'phpbb_bb_language',
		));

		$gid = $ref->getProperty('game_id');
		$gid->setAccessible(true);
		$gid->setValue($this->installer, 'swtor');
	}

	/**
	 * Invoke a protected method on the installer.
	 */
	private function invoke_protected(string $method_name): void
	{
		$this->inserted = array();
		$method = new \ReflectionMethod(swtor_installer::class, $method_name);
		$method->setAccessible(true);
		$method->invoke($this->installer);
	}

	/**
	 * Set (key => value) or remove (value === null) a single entry in the
	 * installer's table_names map, on top of whatever setUp() put there.
	 */
	private function set_table_name(string $key, ?string $value): void
	{
		$ref = new \ReflectionClass($this->installer);
		$tn = $ref->getProperty('table_names');
		$tn->setAccessible(true);
		$current = $tn->getValue($this->installer);

		if ($value === null)
		{
			unset($current[$key]);
		}
		else
		{
			$current[$key] = $value;
		}

		$tn->setValue($this->installer, $current);
	}

	// ── Factions ───────────────────────────────────────────

	public function test_install_factions_count(): void
	{
		$this->invoke_protected('install_factions');
		$this->assertCount(1, $this->inserted);
		$this->assertCount(4, $this->inserted[0]['data']);
	}

	public function test_install_factions_ids(): void
	{
		$this->invoke_protected('install_factions');
		$factions = $this->inserted[0]['data'];
		$ids = array_column($factions, 'faction_id');
		$this->assertContains(1, $ids, 'Galactic Republic faction_id=1');
		$this->assertContains(2, $ids, 'Jedi Order faction_id=2');
		$this->assertContains(3, $ids, 'Sith Empire faction_id=3');
		$this->assertContains(4, $ids, 'Sith Lords faction_id=4');
	}

	public function test_install_factions_names(): void
	{
		$this->invoke_protected('install_factions');
		$factions = $this->inserted[0]['data'];
		$names = array_column($factions, 'faction_name');
		$this->assertContains('Galactic Republic', $names);
		$this->assertContains('Jedi Order', $names);
		$this->assertContains('Sith Empire', $names);
		$this->assertContains('Sith Lords', $names);
	}

	public function test_install_factions_game_id(): void
	{
		$this->invoke_protected('install_factions');
		foreach ($this->inserted[0]['data'] as $row)
		{
			$this->assertSame('swtor', $row['game_id']);
		}
	}

	// ── Classes ────────────────────────────────────────────

	public function test_install_classes_count(): void
	{
		$this->invoke_protected('install_classes');
		// First insert: class rows, second insert: language rows
		$this->assertCount(2, $this->inserted);
		$this->assertCount(9, $this->inserted[0]['data']);
	}

	public function test_install_classes_valid_armor_types(): void
	{
		$this->invoke_protected('install_classes');
		$valid = array('HEAVY', 'LEATHER', 'AUGMENTED', 'ROBE');
		foreach ($this->inserted[0]['data'] as $row)
		{
			$this->assertContains($row['class_armor_type'], $valid, "class_id {$row['class_id']} has valid armor type");
		}
	}

	public function test_install_classes_valid_faction_refs(): void
	{
		$this->invoke_protected('install_classes');
		foreach ($this->inserted[0]['data'] as $row)
		{
			$this->assertContains($row['class_faction_id'], array(1, 2, 3, 4), "class_id {$row['class_id']} references a valid faction");
		}
	}

	public function test_install_classes_language_coverage(): void
	{
		$this->invoke_protected('install_classes');
		$lang_rows = $this->inserted[1]['data'];
		$languages = array_unique(array_column($lang_rows, 'language'));
		sort($languages);
		$this->assertSame(array('de', 'en', 'fr'), $languages);
	}

	public function test_install_classes_language_entries_per_lang(): void
	{
		$this->invoke_protected('install_classes');
		$lang_rows = $this->inserted[1]['data'];
		$per_lang = array_count_values(array_column($lang_rows, 'language'));
		// 9 classes x 3 languages = 27 total
		foreach ($per_lang as $lang => $count)
		{
			$this->assertSame(9, $count, "$lang has 9 class name entries");
		}
	}

	// ── Races ──────────────────────────────────────────────

	public function test_install_races_count(): void
	{
		$this->invoke_protected('install_races');
		// First insert: race rows, second insert: language rows
		$this->assertCount(2, $this->inserted);
		$this->assertCount(13, $this->inserted[0]['data']);
	}

	public function test_install_races_valid_factions(): void
	{
		$this->invoke_protected('install_races');
		foreach ($this->inserted[0]['data'] as $row)
		{
			$this->assertContains($row['race_faction_id'], array(0, 1, 2, 3, 4), "race_id {$row['race_id']} has valid faction");
		}
	}

	public function test_install_races_language_coverage(): void
	{
		$this->invoke_protected('install_races');
		$lang_rows = $this->inserted[1]['data'];
		$languages = array_unique(array_column($lang_rows, 'language'));
		sort($languages);
		$this->assertSame(array('de', 'en', 'fr'), $languages);
	}

	public function test_install_races_language_entries_per_lang(): void
	{
		$this->invoke_protected('install_races');
		$lang_rows = $this->inserted[1]['data'];
		$per_lang = array_count_values(array_column($lang_rows, 'language'));
		// 13 races x 3 languages = 39 total
		foreach ($per_lang as $lang => $count)
		{
			$this->assertSame(13, $count, "$lang has 13 race name entries");
		}
	}

	// ── Disciplines (install_specs) ─────────────────────────
	//
	// swtor_installer implements install_specs() with a full
	// 8-class x 6-discipline catalog (see game/swtor_provider.php's
	// spec_catalog()) — issue #6.

	public function test_install_specs_seeds_when_table_wired(): void
	{
		$this->set_table_name('bb_specializations_table', 'phpbb_bb_specializations');

		$this->invoke_protected('install_specs');

		$this->assertCount(1, $this->inserted);
		// 8 classes x 6 disciplines = 48
		$this->assertCount(48, $this->inserted[0]['data']);

		foreach ($this->inserted[0]['data'] as $row)
		{
			$this->assertSame('swtor', $row['game_id']);
			$this->assertContains($row['class_id'], range(1, 8), "spec '{$row['spec_name']}' has a valid class_id");
			$this->assertContains($row['role_id'], array(0, 1, 2), "spec '{$row['spec_name']}' has a valid role_id");
			$this->assertNotSame('', $row['spec_name'], 'spec_name must not be empty');
			$this->assertContains($row['spec_order'], range(1, 6));
		}
	}

	public function test_install_specs_class_ids_have_six_disciplines_each(): void
	{
		$this->set_table_name('bb_specializations_table', 'phpbb_bb_specializations');

		$this->invoke_protected('install_specs');

		$per_class = array_count_values(array_column($this->inserted[0]['data'], 'class_id'));
		foreach (range(1, 8) as $class_id)
		{
			$this->assertArrayHasKey($class_id, $per_class, "class_id $class_id has Disciplines seeded");
			$this->assertSame(6, $per_class[$class_id], "class_id $class_id has 6 Disciplines (2 Advanced Classes x 3)");
		}
	}

	public function test_install_specs_skips_when_table_not_wired(): void
	{
		$this->set_table_name('bb_specializations_table', null);

		$this->invoke_protected('install_specs');

		$this->assertCount(0, $this->inserted, 'install_specs() must no-op when bb_specializations_table is not in table_names');
	}
}
