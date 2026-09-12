<?php
/**
 * bbGuild SWTOR Extension — disabling this plugin keeps core intact
 *
 * @package   bbguildswtor v2.0
 * @copyright 2026 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

/**
 * The single most important guardrail for a non-flagship game plugin
 * (per tests/functional-tests.md's "Notes for other plugins"): disabling
 * bbguildswtor must not break bbguild core or any other guild.
 *
 * Uses bbguild core's own sample "Test Guild" (guild_id=1, game_id=
 * 'custom') as the control fixture — it is seeded by core's own
 * migration on install, so this test does not depend on any other game
 * plugin being installed. Enables bbguild core + bbguildswtor, disables
 * bbguildswtor, then asserts:
 * - the control guild's page still renders 200
 * - bbguild core's ACP game list still loads
 *
 * @group functional
 */
class avathar_bbguildswtor_disable_keeps_core_test extends phpbb_functional_test_case
{
	/** bbguild core's own seeded sample guild (game_id='custom'). */
	const CONTROL_GUILD_ID = 1;

	static protected function setup_extensions()
	{
		return array('avathar/bbguild', 'avathar/bbguildswtor');
	}

	public function test_disabling_swtor_does_not_break_core()
	{
		$this->login('admin');

		// Sanity check: the control guild renders before we touch anything.
		self::request('GET', 'app.php/guild/' . self::CONTROL_GUILD_ID, array(), false);
		$this->assertSame(200, (int) self::$client->getResponse()->getStatus(), 'Control guild should render before disabling swtor');

		$this->logout();

		$this->disable_ext('avathar/bbguildswtor');

		$this->login('admin');

		self::request('GET', 'app.php/guild/' . self::CONTROL_GUILD_ID, array(), false);
		$this->assertSame(200, (int) self::$client->getResponse()->getStatus(), 'Control guild should still render after disabling swtor');

		$this->admin_login();
		self::request('GET', 'adm/index.php?i=-avathar-bbguild-acp-game_module&mode=listgames&sid=' . $this->sid, array(), false);
		$status = (int) self::$client->getResponse()->getStatus();
		$this->assertLessThan(500, $status, "bbguild core's ACP game list should still load after disabling swtor (got $status)");

		$this->logout();

		// Restore for any test that runs after this one in the same suite.
		$this->install_ext('avathar/bbguildswtor');
	}
}
