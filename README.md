# bbGuild - Star Wars: The Old Republic

[![Tests](https://github.com/avatharbe/bbguildswtor/actions/workflows/tests.yml/badge.svg)](https://github.com/avatharbe/bbguildswtor/actions/workflows/tests.yml)

Game plugin that adds Star Wars: The Old Republic support to [bbGuild](https://github.com/avatharbe/bbguild).

## Features

- **SWTOR Classes** - 9 classes (Trooper, Smuggler, Jedi Knight, Jedi Consular, Bounty Hunter, Sith Warrior, Imperial Agent, Sith Inquisitor) with color codes
- **SWTOR Races** - 13 playable races (Miraluka, Twi'lek, Zabrak, Miralian, Human, Chiss, Rattataki, Red Sith, Cathar, Cyborg, Togruta, Nautolan) with gender-specific images
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

### Classes (9)

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

### Races (13)

Miraluka, Twi'lek, Zabrak, Miralian, Human, Chiss, Rattataki, Red Sith, Cathar, Cyborg, Togruta, Nautolan

## License

[GNU General Public License v2](http://opensource.org/licenses/gpl-2.0.php)

## Links

- [bbGuild Core](https://github.com/avatharbe/bbguild)
- [Issue Tracker](https://github.com/avatharbe/bbguildswtor/issues)
