# Change Log
All changes to `Adressverwaltung Tourziele` will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [0.2.1] - 2022-04-19

### Added
-	A config variable holding the URL for the flags
-	A new column to the TOURZIEL_TABLE to hold the username of a deleted user for displaying the Tourziel

### Changed
-	The link in the forum lists display of the newest Tourziel from the Tourziel list to this Tourziel's detail view in `event/mot_tzv_listener.php`
-	The link to the country flags from an internal directory to `https://flagcdn.com/16x12/`


### Fixed
-	Wrong link to former Tourziel list in `event/mot_tzv_listener.php` which resulted in a "page not found" error
-	Three wrong links to the Tourziel index generated with `gemerate_board_url` which resulted in a "page not found" error
-	A wrong location of the support link and its enabler definition in `event/mot_tzv_listener.php` which led to it being not dislayed if there were no new
	Tourziele
-	Added missing `sql_freeresult` in `functions/mot_tzv_events.php` in `function get_events()`
-	Missing coordinates in search result list

### Removed
-	A superfluous include in the `__construct()` function of `functions/mot_tzv_events.php`
-	The `images` directory with its subdirectory `flag` and all the 242 country flags contained there in order to save 251KB of storage space
-	The `transfer.php` file


## [0.2.0] - 2022-04-10

### Added
-	A confirmation box to all delete operations in the ACP
-	An English language pack in order to adhere to English language as default and fall back language
-	A Javascript file to check input entries while adding a new Tourziel entry
-	A check for the Tourziel name already existing to the SQL query adding a new Tourziel to prevent multiple entries with the same name, affected file is
	`functions/events.php`

### Changed
-	Everything from `waldkatze/tzv` into `mot/tzv`
-	All occurrences of `array( ... )` into the short notation `[ ... ]`
-	Dependency of migration file `migrations/v_0_1_1.php` on installed migration file `migrations/v_0_1_0.php` instead of `\phpbb\db\migration\data\v310\extensions`
	as already defined in `migrations/v_0_1_0.php`
-	The ACP main module into a controller using service injection
-	All occurrences of `user->lang` into `$this->language->lang`
-	Made all language keys unique to this extension by prefixing either `MOT_TZV_` or `ACP_MOT_TZV_`
-	The minimum version of phpBB to 3.2.3 (from 3.3.0) and of PHP to 7.2 (from 7.3)
-	Changed user permissions set in migration file from Registered Users group to user roles

### Fixed
-	The packaging structure (`waldkatze_tzv_0_1_1/waldkatze/tzv` to `waldkatze/tzv`
-	The usage of `request_var()`, changed to `$this->request->variable()` in `acp/main_module.php` and `controller/main.php`
-	The `composer.json` file according to specifics for version constraints and SPDX license identifier
-	The naming of routes in `config/routing.yml`, which didn't start with `waldkatze_tvz`
-	Two potential SQL injections in `controller/main.php`
-	The usage of deprecated or removed function call `get_user_avatar` in `controller/main.php`, it is `phpbb_get_avatar` now
-	Four potential SQL injections in `functions/events.php`
-	The addition of config variables using the `update_schema` function instead of `update_data` in `migrations/v_0_1_1.php`
-	Several hundred violations of the coding guidelines in `ext.php`, `controller/main.php`, `migrations/v_0_1_0.php`, `migrations/v_0_1_1.php`,
	`functions/events.php`, all language files, `event/main_listener.php`, `acp/main_info.php` and `acp/main_module.php`
-	The input of BBCodes and smilies due to a missing include of the editor

### Removed
-	Check with `function effectively_installed()` using the version number as criteria in migration files
  
  
## [0.1.2] - 2014-07-27
Version taken over from waldkatze
