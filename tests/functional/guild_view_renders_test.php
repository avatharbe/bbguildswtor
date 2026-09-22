<?php
/**
 * bbGuild SWTOR Extension — guild view rendering functional test
 *
 * @package   bbguildswtor v2.0
 * @copyright 2026 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

/**
 * Inserts a guild fixture with game_id='swtor' and one player (a Trooper,
 * class_id=1, race_id=5/Human — both real swtor_installer-seeded rows),
 * plus the roster portal module for that guild (only bbguild core's own
 * sample "Test Guild" — guild_id=1 — gets that module pre-seeded by
 * migration; a freshly-inserted guild needs it added explicitly, the
 * same way bbguild core's ACP "add portal module" flow would).
 *
 * GETs /guild/{guild_id} as an authenticated user and asserts:
 * - the response is 200
 * - the roster module rendered the player's row (player name present)
 * - the class image resolves under
 *   ext/avathar/bbguildswtor/images/class_images/
 *
 * @group functional
 */
class avathar_bbguildswtor_guild_view_renders_test extends phpbb_functional_test_case
{
	/** Arbitrary guild_id distinct from bbguild core's own sample guild (id=1). */
	const GUILD_ID = 9001;

	static protected function setup_extensions()
	{
		return array('avathar/bbguild', 'avathar/bbguildswtor');
	}

	private function get_table_prefix(): string
	{
		return self::$config['table_prefix'];
	}

	protected function setUp(): void
	{
		parent::setUp();

		$db = $this->get_db();
		$prefix = $this->get_table_prefix();

		// Clean up any partial previous run (this suite may run more than once
		// against the same DB, e.g. locally against a persisted sqlite file).
		$db->sql_query('DELETE FROM ' . $prefix . 'bb_guild WHERE id = ' . self::GUILD_ID);
		$db->sql_query('DELETE FROM ' . $prefix . 'bb_ranks WHERE guild_id = ' . self::GUILD_ID);
		$db->sql_query('DELETE FROM ' . $prefix . 'bb_players WHERE player_guild_id = ' . self::GUILD_ID);
		$db->sql_query('DELETE FROM ' . $prefix . 'bb_portal_modules WHERE guild_id = ' . self::GUILD_ID);
		$db->sql_query('DELETE FROM ' . $prefix . 'bb_portal_tabs WHERE guild_id = ' . self::GUILD_ID);

		$db->sql_multi_insert($prefix . 'bb_guild', array(array(
			'id'             => self::GUILD_ID,
			'name'           => 'Swtor Test Guild',
			'realm'          => 'Test Server',
			'region'         => 'us',
			'roster'         => 1,
			'players'        => 1,
			'emblemurl'      => '',
			'game_id'        => 'swtor',
			'game_edition'   => 'retail',
			'min_armory'     => 0,
			'rec_status'     => 0,
			'guilddefault'   => 0,
			'armory_enabled' => 0,
			'armoryresult'   => '',
			'recruitforum'   => 0,
			'faction'        => 1,
		)));

		$db->sql_multi_insert($prefix . 'bb_ranks', array(array(
			'guild_id'    => self::GUILD_ID,
			'rank_id'     => 0,
			'rank_name'   => 'Member',
			'rank_hide'   => 0,
			'rank_prefix' => '',
			'rank_suffix' => '',
		)));

		$db->sql_multi_insert($prefix . 'bb_players', array(array(
			'game_id'             => 'swtor',
			'player_name'         => 'Swtortestplayer',
			'player_region'       => 'us',
			'player_realm'        => 'Test Server',
			'player_title'        => '',
			'player_level'        => 50,
			'player_race_id'      => 5, // Human
			'player_class_id'     => 1, // Trooper
			'player_rank_id'      => 0,
			'player_role'         => 'DPS',
			'player_comment'      => '',
			'player_joindate'     => time(),
			'player_outdate'      => 0,
			'player_guild_id'     => self::GUILD_ID,
			'player_gender_id'    => 0,
			'player_achiev'       => 0,
			'player_armory_url'   => '',
			'player_portrait_url' => '',
			'player_spec'         => '',
			'phpbb_user_id'       => 0,
			'player_status'       => 1,
			'deactivate_reason'   => '',
			'last_update'         => time(),
		)));

		// Seed a portal tab first -- portal_renderer::render() bails out
		// before ever looking at bb_portal_modules when a guild has zero
		// tabs (bbguild#360's page-level tabs; see also #374, which
		// backfills this for guilds created through the normal ACP flow,
		// but a fixture inserting rows directly via SQL bypasses that flow
		// entirely and needs to seed its own tab). Uses sql_query()+
		// sql_last_inserted_id() rather than sql_multi_insert() so the new tab_id can
		// be read back for the module row below.
		$db->sql_query('INSERT INTO ' . $prefix . 'bb_portal_tabs ' . $db->sql_build_array('INSERT', array(
			'guild_id'   => self::GUILD_ID,
			'tab_name'   => 'Overview',
			'tab_slug'   => 'welcome',
			'tab_order'  => 0,
			'tab_status' => 1,
		)));
		$tab_id = (int) $db->sql_last_inserted_id();

		$db->sql_multi_insert($prefix . 'bb_portal_modules', array(array(
			'module_classname'    => '\avathar\bbguild\portal\modules\roster',
			'module_tab'          => $tab_id,
			'guild_id'            => self::GUILD_ID,
			'module_column'       => 2,
			'module_order'        => 1,
			'module_name'         => 'BBGUILD_PORTAL_ROSTER',
			'module_image_src'    => '',
			'module_icon'         => '',
			'module_icon_size'    => 16,
			'module_image_width'  => 16,
			'module_image_height' => 16,
			'module_group_ids'    => '',
		)));
	}

	public function test_guild_page_renders_roster_row()
	{
		$this->login('admin');

		self::request('GET', 'app.php/guild/' . self::GUILD_ID, array(), false);
		$status = (int) self::$client->getResponse()->getStatus();
		$this->assertSame(200, $status, 'Guild view page should render');

		$body = self::$client->getResponse()->getContent();

		$this->assertStringContainsString('Swtortestplayer', $body, 'Roster module should render the seeded player row');
		$this->assertStringContainsString('ext/avathar/bbguildswtor/images/class_images/', $body, 'Class image should resolve under the swtor plugin images path');

		$this->logout();
	}
}
