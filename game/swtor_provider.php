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
use avathar\bbguild\model\games\specialization_provider_interface;

class swtor_provider implements game_provider_interface, specialization_provider_interface
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

	/**
	 * Discipline catalog (issue #6), keyed by class_id (see
	 * game/swtor_installer.php's install_classes() for the id map).
	 *
	 * SWTOR's class_id here is the base Class (Trooper, Smuggler, Jedi
	 * Knight, ...) chosen at character creation. Each base Class branches
	 * at level ~10 into two Advanced Classes (renamed "Combat Styles" as
	 * of Game Update 7.0 Legacy of the Sith, when they were also decoupled
	 * from the class/faction pairing below for character creation
	 * purposes — a level 10+ character can in principle pick either
	 * style regardless of Class). Each Advanced Class/Combat Style in
	 * turn offers 3 Disciplines — the actual build/loadout choice, and
	 * the layer this catalog models, matching this plugin's existing
	 * per-Class install_classes() rows one level down rather than
	 * inventing a second "Advanced Class" tier the bb_specializations
	 * schema has no column for. Per base Class this is 2 Advanced
	 * Classes x 3 Disciplines = 6 entries:
	 *
	 *   1 Trooper:          Vanguard (Shield Specialist/Tactics/Plasmatech),
	 *                       Commando (Combat Medic/Gunnery/Assault Specialist)
	 *   2 Smuggler:         Scoundrel (Sawbones/Scrapper/Ruffian),
	 *                       Gunslinger (Sharpshooter/Saboteur/Dirty Fighting)
	 *   3 Jedi Knight:      Guardian (Defense/Vigilance/Focus),
	 *                       Sentinel (Watchman/Combat/Concentration)
	 *   4 Jedi Consular:    Shadow (Kinetic Combat/Infiltration/Serenity),
	 *                       Sage (Seer/Telekinetics/Balance)
	 *   5 Bounty Hunter:    Powertech (Shield Tech/Pyrotech/Advanced Prototype),
	 *                       Mercenary (Bodyguard/Arsenal/Innovative Ordnance)
	 *   6 Sith Warrior:     Juggernaut (Immortal/Vengeance/Rage),
	 *                       Marauder (Annihilation/Carnage/Fury)
	 *   7 Imperial Agent:   Operative (Medicine/Concealment/Lethality),
	 *                       Sniper (Marksmanship/Engineering/Virulence)
	 *   8 Sith Inquisitor:  Assassin (Darkness/Deception/Hatred),
	 *                       Sorcerer (Corruption/Lightning/Madness)
	 *
	 * class_id 0 ("Unknown" placeholder row from install_classes()) has no
	 * Disciplines and is intentionally omitted from this catalog.
	 *
	 * role_id is the Discipline's own established role — unambiguous per
	 * Discipline (unlike GW2's per-elite-spec judgment call), since each
	 * Discipline commits to exactly one of Tank/Healer/DPS: Shield
	 * Specialist, Defense, Kinetic Combat, Shield Tech, Immortal and
	 * Darkness are Tank; Combat Medic, Sawbones, Seer, Bodyguard,
	 * Medicine and Corruption are Healer; every other Discipline listed
	 * is DPS.
	 *
	 * spec_icon intentionally left empty: no icon assets exist yet for
	 * these Disciplines (same as GW2's shipped Elite Specialization
	 * catalog) — tracked separately as a follow-up.
	 *
	 * @return array<int, list<array{spec_name:string,role_id:int,spec_icon:string,spec_order:int}>>
	 */
	public static function spec_catalog(): array
	{
		[$dps, $healer, $tank] = [0, 1, 2];

		return array(
			1 => array( // Trooper: Vanguard, Commando
				array('spec_name' => 'Shield Specialist',   'role_id' => $tank,   'spec_icon' => '', 'spec_order' => 1),
				array('spec_name' => 'Tactics',              'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 2),
				array('spec_name' => 'Plasmatech',           'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 3),
				array('spec_name' => 'Combat Medic',         'role_id' => $healer, 'spec_icon' => '', 'spec_order' => 4),
				array('spec_name' => 'Gunnery',              'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 5),
				array('spec_name' => 'Assault Specialist',   'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 6),
			),
			2 => array( // Smuggler: Scoundrel, Gunslinger
				array('spec_name' => 'Sawbones',       'role_id' => $healer, 'spec_icon' => '', 'spec_order' => 1),
				array('spec_name' => 'Scrapper',       'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 2),
				array('spec_name' => 'Ruffian',        'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 3),
				array('spec_name' => 'Sharpshooter',   'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 4),
				array('spec_name' => 'Saboteur',       'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 5),
				array('spec_name' => 'Dirty Fighting', 'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 6),
			),
			3 => array( // Jedi Knight: Guardian, Sentinel
				array('spec_name' => 'Defense',       'role_id' => $tank, 'spec_icon' => '', 'spec_order' => 1),
				array('spec_name' => 'Vigilance',     'role_id' => $dps,  'spec_icon' => '', 'spec_order' => 2),
				array('spec_name' => 'Focus',         'role_id' => $dps,  'spec_icon' => '', 'spec_order' => 3),
				array('spec_name' => 'Watchman',      'role_id' => $dps,  'spec_icon' => '', 'spec_order' => 4),
				array('spec_name' => 'Combat',        'role_id' => $dps,  'spec_icon' => '', 'spec_order' => 5),
				array('spec_name' => 'Concentration', 'role_id' => $dps,  'spec_icon' => '', 'spec_order' => 6),
			),
			4 => array( // Jedi Consular: Shadow, Sage
				array('spec_name' => 'Kinetic Combat', 'role_id' => $tank,   'spec_icon' => '', 'spec_order' => 1),
				array('spec_name' => 'Infiltration',   'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 2),
				array('spec_name' => 'Serenity',       'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 3),
				array('spec_name' => 'Seer',           'role_id' => $healer, 'spec_icon' => '', 'spec_order' => 4),
				array('spec_name' => 'Telekinetics',   'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 5),
				array('spec_name' => 'Balance',        'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 6),
			),
			5 => array( // Bounty Hunter: Powertech, Mercenary
				array('spec_name' => 'Shield Tech',         'role_id' => $tank,   'spec_icon' => '', 'spec_order' => 1),
				array('spec_name' => 'Pyrotech',            'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 2),
				array('spec_name' => 'Advanced Prototype',  'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 3),
				array('spec_name' => 'Bodyguard',           'role_id' => $healer, 'spec_icon' => '', 'spec_order' => 4),
				array('spec_name' => 'Arsenal',             'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 5),
				array('spec_name' => 'Innovative Ordnance', 'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 6),
			),
			6 => array( // Sith Warrior: Juggernaut, Marauder
				array('spec_name' => 'Immortal',     'role_id' => $tank, 'spec_icon' => '', 'spec_order' => 1),
				array('spec_name' => 'Vengeance',    'role_id' => $dps,  'spec_icon' => '', 'spec_order' => 2),
				array('spec_name' => 'Rage',         'role_id' => $dps,  'spec_icon' => '', 'spec_order' => 3),
				array('spec_name' => 'Annihilation', 'role_id' => $dps,  'spec_icon' => '', 'spec_order' => 4),
				array('spec_name' => 'Carnage',      'role_id' => $dps,  'spec_icon' => '', 'spec_order' => 5),
				array('spec_name' => 'Fury',         'role_id' => $dps,  'spec_icon' => '', 'spec_order' => 6),
			),
			7 => array( // Imperial Agent: Operative, Sniper
				array('spec_name' => 'Medicine',      'role_id' => $healer, 'spec_icon' => '', 'spec_order' => 1),
				array('spec_name' => 'Concealment',   'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 2),
				array('spec_name' => 'Lethality',     'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 3),
				array('spec_name' => 'Marksmanship',  'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 4),
				array('spec_name' => 'Engineering',   'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 5),
				array('spec_name' => 'Virulence',     'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 6),
			),
			8 => array( // Sith Inquisitor: Assassin, Sorcerer
				array('spec_name' => 'Darkness',    'role_id' => $tank,   'spec_icon' => '', 'spec_order' => 1),
				array('spec_name' => 'Deception',   'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 2),
				array('spec_name' => 'Hatred',      'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 3),
				array('spec_name' => 'Corruption',  'role_id' => $healer, 'spec_icon' => '', 'spec_order' => 4),
				array('spec_name' => 'Lightning',   'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 5),
				array('spec_name' => 'Madness',     'role_id' => $dps,    'spec_icon' => '', 'spec_order' => 6),
			),
		);
	}

	/**
	 * @inheritdoc
	 */
	public function get_spec_label(): string
	{
		return 'Discipline';
	}

	/**
	 * Interface implementation: delegates to the static catalog.
	 *
	 * @return array<int, list<array{spec_name:string,role_id:int,spec_icon:string,spec_order:int}>>
	 */
	public function get_specializations(): array
	{
		return self::spec_catalog();
	}
}
