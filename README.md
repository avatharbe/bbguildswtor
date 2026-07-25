# bbGuild - Star Wars: The Old Republic

**Current version:** 2.0.0-rc1 (release candidate)

[![Tests](https://github.com/avatharbe/bbguildswtor/actions/workflows/tests.yml/badge.svg)](https://github.com/avatharbe/bbguildswtor/actions/workflows/tests.yml)

Star Wars: The Old Republic splits its playerbase down the middle — Republic and Empire guilds rarely mix, and that faction line runs through everything from strongholds to PvP, so a guild roster tool needs to respect it rather than paper over it. bbguildswtor covers all 8 classes and 12 races (including Togruta and Nautolan), the four factions (Galactic Republic, Jedi Order, Sith Empire, Sith Lords), and boss/zone links straight to SWTOR Spy. Your guild's roster, recruitment, and character claiming now live where the rest of your community already reads and posts.

## Features

- **SWTOR Classes** - 8 classes (Trooper, Smuggler, Jedi Knight, Jedi Consular, Bounty Hunter, Sith Warrior, Imperial Agent, Sith Inquisitor) with color codes
- **SWTOR Races** - 12 playable races (Miraluka, Twi'lek, Zabrak, Mirialan, Human, Chiss, Rattataki, Sith Pureblood, Cathar, Cyborg, Togruta, Nautolan) with gender-specific images
- **Factions** - 4 factions: Galactic Republic, Jedi Order, Sith Empire, Sith Lords
- **Localization** - Class and race names in English, German, and French
- **Database Links** - Boss and zone URLs linked to SWTOR Spy

## Requirements

- phpBB >= 3.3.0
- PHP >= 8.1.0
- **bbGuild core** (`avathar/bbguild`) must be installed and enabled

## Installation

1. Ensure bbGuild core (`avathar/bbguild`) is installed and enabled.
2. Copy the `bbguildswtor` folder to `/ext/avathar/bbguildswtor/`.
3. Navigate in the ACP to `Customise -> Manage extensions`.
4. Look for `bbGuild - SWTOR` under Disabled Extensions and click `Enable`.
5. Go to ACP > bbGuild > Games and install the **Star Wars: The Old Republic** game.

## Uninstall

1. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
2. Find `bbGuild - SWTOR` under Enabled Extensions and click `Disable`.
3. To permanently uninstall, click `Delete Data` and then delete the `/ext/avathar/bbguildswtor` folder.

**Note:** Disabling the extension does not delete existing guild or player data. Your roster and player records remain intact in bbGuild core.

## Game Data

### Factions

| ID | Faction |
|----|---------|
| 1 | Galactic Republic |
| 2 | Jedi Order |
| 3 | Sith Empire |
| 4 | Sith Lords |

### Classes (8)

| ID | Class | Faction | Armor |
|----|-------|---------|-------|
| 0 | Unknown | Republic | Heavy |
| 1 | Trooper | Republic | Heavy |
| 2 | Smuggler | Republic | Leather |
| 3 | Jedi Knight | Jedi Order | Augmented |
| 4 | Jedi Consular | Jedi Order | Robe |
| 5 | Bounty Hunter | Sith Empire | Heavy |
| 6 | Sith Warrior | Sith Lords | Leather |
| 7 | Imperial Agent | Sith Empire | Augmented |
| 8 | Sith Inquisitor | Sith Lords | Robe |

### Races (12)

Miraluka, Twi'lek, Zabrak, Mirialan, Human, Chiss, Rattataki, Sith Pureblood, Cathar, Cyborg, Togruta, Nautolan

## License

[GNU General Public License v2](http://opensource.org/licenses/gpl-2.0.php)

## Links

- [bbGuild Core](https://github.com/avatharbe/bbguild)
- [Issue Tracker](https://github.com/avatharbe/bbguildswtor/issues)
