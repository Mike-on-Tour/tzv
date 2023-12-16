# Change Log
All changes to `Tour Destinations Database` will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.2.0] - 2023-12-06

### Added
-	The usage of either toggles, checkboxes or radio buttons according to a general template variable called `TOGGLECTRL_TYPE` which will be implemented with a future
	ext `lukewcs/togglectrl`, default is still 'toggles'; affected file is `adm/style/acp_mot_tzv_settings.html`
-	A check in `controller/mot_tzv_main.php` to prevent displaying the extension through its route (`my-server/mot_tzv`) if it is completely disabled or just available
	for administrators

### Changed
-	Minimal PHP version to 7.4.0 and maximal PHP version to less than 8.4.0@dev
-	Minimal phpBB version to 3.3.1
-	Some changes in the ACP settings tab to make it more precise

### Fixed

### Removed
  
  
## [1.1.0] - 2023-01-24

### Added
-	A button leading to a website to find coordinates for named locations to the add/edit item tab

### Changed
-	The range of usable PHP versions to 7.2.0 up to all versions less than 8.3.0
-	The range of usable phpBB versions to 3.2.0 up to less than 3.4.0 dev
-	The radio buttons in the ACP settings tab into sliders with the "activated" state on the left and the "deactivated" state on the right side (according
	to the "Yes" and "No" radio buttons) with a variable `SLIDERS` which can display the old radio buttons if set to 'false' in the
	`adm/style/acp_mot_tzv_settings.html` file
-	The explicit usage of `ENT_COMPAT` in the call of `htmlspecialchars_decode()` to comply with the changes in PHP 8.1 and above in all notification PHP files

### Fixed

### Removed
  
  
## [1.0.0] - 2022-08-18

### Added
-	The language pack `de_x_sie`
-	The overlays for the used categories to the search result map

### Changed
-	The extensions name into the English translation
-	The method to return errors during activation in `ext.php` which is now compatible with phpBB 3.1.0
-	The output of the copyright line in the frontend if the extension is active to phpBB's copyright lines

### Fixed
-	A missing 'true' statement while requesting the variable for the support link in the ACP settings function

### Removed
  
  
## [0.6.0] - 2022-06-14

### Added
-	The possibility to display items on the main map in different layers with the layers named after the used categories; this includes a corresponding
	ACP setting
-	A 'Tourziel List' link to the moderator page

### Changed
-	The map's scale control element to display both metric and imperial distances

### Fixed

### Removed
  
  
## [0.5.2] - 2022-05-31

### Added

### Changed

### Fixed
-	A PHP Notice in debug mode if no moderators with the permission to edit or delete tour destinations exist; affected file is `functions/mot_tzv_events.php`
-	The length of the `homepage` column to 255 characters (formerly 50 characters) in the MOT_TOURZIEL_TABLE in order toprevent error messages while storing
	a new item in the database

### Removed
  
  
## [0.5.1] - 2022-05-29

### Added

### Changed
-	The error display management while storing a new item in `functions/mot_tzv_events.php` function `add_event` to display a general error message if an error
	other than error code 1062 (item already exists) occurs in order to get a valid error message
-	The behavior of the detailed view to display an error message if an item can not be found (e.g. because it was deleted prior to clicking the notification)

### Fixed
-	Requesting variables missing a `true` statement to handle non-ASCII characters correctly while deleting countries, regions, categories and WLAN options
	in `controller/mot_tzv_acp.php`
-	An issue which led to not displaying search results on the map due to missing includes of the 'Nominatim' search control element to the map used to
	display the search results

### Removed
  
  
## [0.5.0] - 2022-05-19

### Added
-	Transfer of GPS data to a route planner for hikers and bikers including starting that route planner in the user's language if it is supported
-	A search control element to the map to search the OSM database for addresses, features and so on with 'Nominatim'

### Changed
-	The sequence of settings for maps and data transfer to route planners

### Fixed
-	Queries in `functions/mot_tzv_events.php` to satisfy PostgreSQL queries (remove single quotes)

### Removed
  
  
## [0.4.2] - 2022-05-09

### Added
-	The ability to create a new item by right-clicking onto the desired map location and then be redirected to the input form with this location's coordinates

### Changed

### Fixed

### Removed
  
  
## [0.4.1] - 2022-05-03

### Added

### Changed

### Fixed
-	COUNT queries in `controller/mot_tzv_acp.php` and `controller/mot_tzv_main.php` to satisfy PostgreSQL queries (remove single quotes)
-	Inserting initial values into the COUNTRY_, REGION_, CATEGORY_ and WLAN_TABLEs by deleting the given ids in order to prevent error messages
	when using PostgreSQL (`migrations/v_0_1_0.php`)
-	Root paths in `controller/mot_tzv_main.php` and `styles/prosilver/template/mot_tzv_main_index.html` since the function
	`$path_helper->get_web_root_path()` only works correctly if the 'Enable URL Rewriting' setting is set to 'Yes' which is fixed by using the `$root_path`
	variable which works correctly independently from the 'Enable URL Rewriting' setting

### Removed
  
  
## [0.4.0] - 2022-05-02

### Added
-	A map to the search results to show search results listed in the results table
-	The possibility to display own images on the index page instead or in addition to the pre-installed images

### Changed
-	Changed the call to `kurviger.de` to open a new tab (or window), omitted the variables for routing in the call (these should be the user's choice and
	he can do it at `kurviger.de`) and added a location variable if location is not 'de' and in one of the supported languages of `kurviger.de`
	(currently ['en', 'es', 'fr', 'it', 'nl',])  
	Definition of the language variable in `language/xx/common.php`

### Fixed

### Removed
  
  
## [0.3.0] - 2022-04-27

### Added
-	An overview map (including marker clustering) and four new config variables for it
-	UCP tab to show the respective user his "own" tour destinations including the possibility to edit or delete them if the proper permissions are granted
-	Two settings to the ACP settigs tab to enable administrators to choose how the latest tour destination and the list of tour destinations will be displayed
	(either as a detailled view or as a table row) and two new config variables to hold these values
-	Notification of moderators permitted to edit or delete tour destinations in cases of a created, edited or deleted tour destination

### Changed

### Fixed
-	The calculation and the display of the OSM map within the detail view of a tour destination
-	A bug in the edit mode which changed the author of a tour destination if a moderator edited it
-	A bug with the user permissions which prevented authorised users to edit or delete their own tour destinations

### Removed
  
  
## [0.2.1] - 2022-04-19

### Added
-	A config variable holding the URL for the flags
-	A new column to the TOURZIEL_TABLE to hold the username of a deleted user for displaying the tour destinations

### Changed
-	The link in the forum lists display of the newest tour destination from the tour destinations list to this tour destinations's detail view in `event/mot_tzv_listener.php`
-	The link to the country flags from an internal directory to `https://flagcdn.com/16x12/`

### Fixed
-	Wrong link to former tour destinations list in `event/mot_tzv_listener.php` which resulted in a "page not found" error
-	Three wrong links to the tour destinations index generated with `generate_board_url` which resulted in a "page not found" error
-	A wrong location of the support link and its enabler definition in `event/mot_tzv_listener.php` which led to it being not dislayed if there were no new
	tour destinations
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
-	A Javascript file to check input entries while adding a new tour destination entry
-	A check for the tour destination's name already existing to the SQL query adding a new Tourziel to prevent multiple entries with the same name, affected file is
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
