<?php
/**
 * bbGuild SWTOR Extension — game registry functional test
 *
 * @package   bbguildswtor v2.0
 * @copyright 2026 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

/**
 * After enabling bbguildswtor, asserts its game_provider is registered
 * with bbguild core's game registry (avathar.bbguild.game_registry,
 * built from services tagged 'bbguild.game_provider') and reachable
 * with has_api() === false.
 *
 * phpbb_functional_test_case drives requests through a separate HTTP
 * process, so the DI container is not reachable in-process here (see
 * tests/integration-tests.md's "no DI container is reachable" note) —
 * the registry is instead exercised indirectly through the ACP "Edit
 * game" page (i=-avathar-bbguild-acp-game_module&mode=editgames), which
 * resolves the provider via $this->game_registry->get($game_id) and
 * renders GAME_NAME from it, and conditionally renders an
 * "enable_armory" checkbox only when the resolved provider's
 * has_api() is true.
 *
 * @group functional
 */
class avathar_bbguildswtor_game_registry_test extends phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return array('avathar/bbguild', 'avathar/bbguildswtor');
	}

	public function test_swtor_provider_registered_and_has_no_api()
	{
		$this->login('admin');
		$this->admin_login();

		self::request('GET', 'adm/index.php?i=-avathar-bbguild-acp-game_module&mode=editgames&game_id=swtor&sid=' . $this->sid, array(), false);
		$status = (int) self::$client->getResponse()->getStatus();
		$this->assertSame(200, $status, 'Edit game page for swtor should render without error');

		$body = self::$client->getResponse()->getContent();

		// Provider is registered and reachable: its game_name is rendered.
		$this->assertStringContainsString('Star Wars: The Old Republic', $body);

		// has_api() === false: no armory-enable checkbox should be rendered.
		$this->assertStringNotContainsString('name="enable_armory"', $body);

		$this->logout();
	}
}
