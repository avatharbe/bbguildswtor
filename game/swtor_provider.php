<?php
/**
 * SWTOR Game Provider
 *
 * @package   bbguildswtor v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguildswtor\game;

use avathar\bbguild\model\games\game_provider_interface;

class swtor_provider implements game_provider_interface
{
	/** @var swtor_installer */
	private $installer;

	/** @var \phpbb\extension\manager */
	private $ext_manager;

	public function __construct(swtor_installer $installer, \phpbb\extension\manager $ext_manager)
	{
		$this->installer = $installer;
		$this->ext_manager = $ext_manager;
	}

	public function get_game_id(): string
	{
		return 'swtor';
	}

	public function get_game_name(): string
	{
		return 'Star Wars: The Old Republic';
	}

	public function get_installer(): \avathar\bbguild\model\games\game_install_interface
	{
		return $this->installer;
	}

	public function get_boss_base_url(): string
	{
		return 'http://www.swtor-spy.com/codex/%s';
	}

	public function get_zone_base_url(): string
	{
		return 'http://www.swtor-spy.com/codex/%s';
	}

	public function get_images_path(): string
	{
		return $this->ext_manager->get_extension_path('avathar/bbguildswtor', true) . 'images/';
	}

	public function has_api(): bool
	{
		return false;
	}

	public function get_api(): ?\avathar\bbguild\model\games\game_api_interface
	{
		return null;
	}

	public function get_regions(): array
	{
		return array(
			'us' => 'US',
			'eu' => 'EU',
		);
	}

	public function get_api_locales(): array
	{
		return array();
	}

	public function get_armor_types(): array
	{
		return array(
			'ROBE'      => 'Robes',
			'LEATHER'   => 'Leather',
			'AUGMENTED' => 'Augmented',
			'HEAVY'     => 'Heavy',
		);
	}
}
