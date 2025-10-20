# AdRotate Banner Manager changelog

## 5.15.4 - October 19, 2025
* Fix - AdRotate Pro banner showing on too many dashboard pages
* Change - AdRotate Pro banner showing less often

## 5.15.3 - October 7, 2025
* Fix - Removed unreliable/broken WordPress redirect when activating the plugin
* Fix - Wrong function name in uninstall routine
* Update - Links to statistics manuals

## 5.15.2 - September 24, 2025
* Update - Activation/de-activation/uninstall routine

## 5.15.1 - August 22, 2025
* Fix - Output warning when activating the plugin for the first time

## 5.15 - August 11, 2025
* Performance - No more redundant queries when using any Manage Adverts dashboard
* Performance - Code cleanup for group editing
* Change - Reworked the Manage Adverts loading code for a massive speed increase on larger sites
* Change - Reworked the Manage Groups loading code for more uniform code
* Removed - Unused goosebox.js file

## 5.14.1 - August 5, 2025
* update - Simplified de-activation code
* update - Simplified uninstall code
* update - Revised footer credits
* update - Revised 'AdRotate Pro' page
* removed - Birthday banner
* removed - Review banner

## 5.14 - Jun 7, 2025
* fix - XSS security flaw when saving widgets
* fix - Warning for widget type not being set when adding more widgets
* update - Improved widget saving code and formatting

## 5.13.7 - March 18, 2025
* update - All support links to match current support system
* update - Compatibility info for WordPress and ClassicPress
* update - Reformatted AdRotate Credits area
* fix - Several formatting issues in dashboard
* i18n - Translations updated

## 5.13.6 & 5.13.6.1 - December 17, 2024
* update - Copyright notices for 2025
* update - Compatibility info for WordPress and ClassicPress
* change - Removed stray debug code

## 5.13.5 - October 28, 2024
* update - WordPress media uploader only lists and allows relevant files
* fix - Text indicating unfiltered html suggesting the wrong setting

## 5.13.4 - September 4, 2024
* fix - Image selection not kept when using the advert generator
* fix - webp images are now listed in the advert generator

## 5.13.3 - August 20, 2024
* change - Use WordPress native function when uploading files
* change - Use WordPress native function when creating folders
* change - Creating folders checks if folder already exists
* change - Uploading a file checks if file already exists
* fix - Missing confirm notice when creating a folder

## 5.13.2 - July 13, 2024
* fix - Missing mimetype when uploading banner images
* update - Rollback to v1.1 for clicktracker Javascript

## 5.13.1 - July 12, 2024
* fix - Unfiltered HTML indicator reversed in Maintenance
* fix - Missing _nonce value for maintenance functions

## 5.13 - July 5, 2024
* new - Upload webp banner images through the media manager
* security - Low level users can no longer access the dashboard
* security - Low level users can no longer upload html/js/zip files
* security - DISALLOW_UNFILTERED_HTML required for Javascript ads
* security - DISALLOW_UNFILTERED_HTML required for group wrapper
* change - Previews disabled for some Javascript ads
* change - Separated most dashboard code from the front-end
* change - Updated and merged various functions
* update - Enabled/disabled icons now based on generated svg
* update - Consistent use of single and double-quotes
* i18n - Translations updated
* removed - Removed setting to disable dynamic groups
* removed - Welcome pointer when first activating the plugin
* removed - Lots of unused or outdated code

## 5.12.10 - April 11, 2024
* i18n - Translations updated
* change - Dashboards and pricing updated
* change - Database upgrade for AdRotate Pro compatibility

## 5.12.9 - February 20, 2024
* fix - Wrong name for clicktracker click_object

## 5.12.8 - February 17, 2024
* change - No longer requires jQuery for clicktracker
* change - Dashboard updates

## 5.12.7 - December 11, 2023
* fix - Color coding of adverts when editing groups
* fix - Color coding labels below adverts
* fix - Color coding labels below schedules
* fix - Dynamic groups not always hiding first advert
* change - Reworked group output and made it more efficient
* change - Improved PHP 8 compatibility

## 5.12.6 - November 6, 2023
* fix - Advert block now use the correct advert ID
* fix - Group block now use the correct group ID

## 5.12.5 - November 2, 2023
* new - Blocks now support fragmented cache
* change - Requires PHP 7.4 or newer
* change - Removed CSV ad exports, use portability instead
* i18n - Updated translations

## 5.12.4 - August 14, 2023
* fix - Wrong 'update failed' error when updating to version 5.12.3
* fix - No longer redirects to Settings page when update completes

## 5.12.3 - July 23, 2023
* fix - Typo in DONOTCACHEOBJECT constant name

## 5.12.2 - May 25, 2023
* change - Dashboard updates
* change - Updated readme, WordPress compatibility
* i18n - Updated translations

## 5.12.1 - April 15, 2023
* i18n - All translations updated
* fix - Several spelling errors

## 5.12 - April 5, 2023
* change - Dashboard updates
* tweak - All but the first ads are set to display:none; for dynamic groups
* new - Automated trigger after updates to finish the update
* removed - wp_cache_set() for stats

## 5.11 - January 27, 2023
* new - Error indicators in Manage Groups
* fix - Offset notice for some post injection combinations
* change - Updated Google Tag Manager references for GA4
* change - Updated Google Global Tag to depreciated

## 5.10.3 - January 23, 2023
* fix - Some variables being the wrong type
* tweak - Better handling of saving group settings

## 5.10.2 - January 16, 2023
* fix - Post Injection multiple groups for 'inside' options
* fix - Post Injection no redundant looping through post_content
* fix - Faulty function call when uninstalling the plugin

## 5.10.1 - January 14, 2023
* fix - Dashboard error when adding new group
* fix - Blocks not working for Post Injection
* fix - Post Injection not always showing every advert
* fix - Post Injection sometimes selecting the wrong ads
* fix - Missing nonce value in maintenance link

## 5.10 - January 11, 2023
* new - Add adverts between up to 20 paragraphs with Post Injection
* fix - Better advert selection for Post Injection (internal)
* fix - Occasional double or missing adverts with Post Injection

## 5.9.1 & 5.9.1.1 - November 14, 2022
* fix - Removed left over debug code causing warnings on some servers
* fix - Removed unused $archive variable from stats
* fix - Nonce verification for certain maintenance buttons
* fix - Status indicators for maintenance functions
* change - Removed unused maintenance function
* i18n - Updated translations

## 5.9 - October 18, 2022
* fix - 'auto' not working for the advert size in advert groups
* fix - Better handling when group css is not loading
* fix - Several internal references to AdRotate
* fix - Several version indicators for AdRotate
* fix - File uploads to media manager when editing ads
* change - Use WP provided version for plugin_version
* change - Updated support dashboard page
* change - Updated help tabs
* i18n - Updated all included translations

## 5.8.26 - July 19, 2022
* fix - Advert generator dropdown now looks in subfolders for images
* change - Double quotation marks no longer allowed in names
* change - Added nonces to maintenance features
* i18n - Fixed several Dutch phrases

## 5.8.25 - May 18, 2022
* update - Tested with WordPress 6
* fix - Double file extension when uploading file
* fix - Uploading zip files now reliably detects mime type
* fix - Schedules not always saving when editing adverts
* fix - Schedules not always deleting when deleting adverts

## 5.8.24 - April 18, 2022
* new - Group and Advert block support
* fix - Selecting adverts in groups not working
* fix - Better handling of caching constants
* tweak - Replaced sanitize_title with sanitize_text_field for advert names
* tweak - Replaced sanitize_title with sanitize_text_field for group names
* update - Update database to be in sync with AdRotate Pro

## 5.8.23 - April 7, 2022
* fix - Replaced 'adrotate-pro' language strings with 'adrotate'
* fix - Better validate uploaded files
* fix - Low risk security fixes for saving adverts and groups
* update - Borlabs cache code - Requires Borlabs Cache 1.6.5+
* update - Removed unused 'save options' button in Maintenance dashboard

## 5.8.22 - February 4, 2022
* fix - Overall stats now only count active adverts
* fix - Potential security issue when renewing adverts
* update - Redid dashboard help tabs

## 5.8.21 - September 3, 2021
* new - Warning if %image% is used in adverts
* update - Updated dashboard
* change - Removed support for %image% tag, use %asset% instead
* fix - Error status for script/ins/iframe ads with statistics enabled
* fix - Error status for ads without a link with statistics enabled
* fix - Improved PHP8 compatibility
* fix - Dashboard tweaks

## 5.8.20 - July 8, 2021
* fix - Advert no longer fails evaluation if a script tag is present
* i18n - Corrected 'Post Injection' labels in Dutch translation

## 5.8.19 - May 21, 2021
* i18n - Improved Italian translation (Thanks Davide)
* i18n - Improved Dutch translation
* i18n - All translations updated using auto-translate
* change - More consistent use of the word advert vs ad
* change - Simplified settings text in General Settings
* change - Cleaner dashboard when creating adverts
* fix - Tabindex on dashboards now in the right order

## 5.8.18 - April 26, 2021
* i18n - Added Portuguese translation (pt_BR)

## 5.8.17 - March 15, 2021
* fix - Labels for all checkboxes
* update - HTML dashboard code cleanup

## 5.8.16 - February 22, 2021
* change - Dashboard tweaks
* i18n - Updated Dutch, French, German and Italian translation texts
* i18n - Updated Polish translation strings

## 5.8.15 - February 1, 2021
* fix - Dashboard notifications screwing up dashboard layout

## 5.8.14 - January 26, 2021
* fix - Dashboard tweaks
* fix - Added Telegram as a 'news&updates' option

## 5.8.13 - November 23, 2020
* change - Removed mentions of Google Universal Tracker
* change - Only count impressions if stats are explicitly enabled

## 5.8.12 - November 4, 2020
* tweak - Rely more on WordPress time settings

## 5.8.11 - October 27, 2020
* tweak - Improved file placement for unzipped adverts
* new - Automatically remove unexpected files from unzipped adverts

## 5.8.10 - October 14, 2020
* tweak - More consistent use of date() and date_i18n()
* change - Now requires WordPress 5.0 or newer

## 5.8.9.1 - October 2, 2020
- Tested to work on WordPress 5.5.1
* update - Translation titles now match AdRotate

## 5.8.9 - September 21, 2020
* tweak - Reworded advert error notifications

## 5.8.8 - September 8, 2020
* fix - Replaced adrotate_now() with current_time('timestamp')

## 5.8.7 - August 21, 2020
* i18n - Updated and rewritten Dutch translation to make more sense

## 5.8.6.2 - July 29, 2020
- Tested to work on WordPress 5.5

## 5.8.6.1 - July 22, 2020
* CAUTION - PHP snippet syntax has changed, check manual!
* fix - Zip uploads not always working
* change - Dashboard tweaks
* i18n - Updated translations

## 5.8.6 - July 14, 2020
* CAUTION - PHP snippet syntax has changed, check manual!
* new - Added/Unlocked AdRotate Media Manager
* new - Create folders from the Media Manager
* change - Redesigned Info dashboard
* change - Removed affiliate offers
* change - Re-arranged AdRotate Pro page

## 5.8.5 - June 29, 2020
* fix - Improved MySQL 8 compatibility
* change - Improved dashboard notifications

## 5.8.4 - June 2, 2020
* fix - Possible SQL injection vulnerability for certain urls

## 5.8.3.1 - May 25, 2020
* i18n - Updated Dutch translation
* i18n - Updated French translation
* i18n - Updated Italian translation


## 5.8.3 - May 18, 2020
* new - Configuration notifications when editing groups
* new - Portable adverts
* change - Dashboard tweaks
* fix - 'Unexpected output' notice on activation of the plugin
* i18n - Updated Dutch, French, German and Italian

## 5.8.2 - April 30, 2020
- Improved WordPress 5.4.1 compatibility
* i18n - Updated french translation

## 5.8.1 - April 28, 2020
* fix - Uninstall not working for some people

## 5.8 - April 22, 2020
* change - Maintenance dashboard optimised and reworded
* change - Removed old/unused debug code
* change - Compacted Javascript files
* new - Check if AdRotate Pro is installed
* new - Check if faulty adrotate plugin folders exist
* new - Skip uninstall procedure if AdRotate Pro is installed
* i18n - Added French translation using pre-translation
* i18n - Added Italian translation using pre-translation
* i18n - Added Dutch translation using pre-translation
* i18n - Added German translation using pre-translation
* i18n - Updated Spanish translation using pre-translation

## 5.7.1 - March 16, 2020
* fix - Better check for Borlabs Cache availability
* fix - Manual update and Background task reset button
* fix - More reliable trigger for database update script
* fix - Missing $wp_roles when deactivating the plugin
* change - Updated export advert script

## 5.7 - February 12, 2020
* fix - Vulnerability related to groups
* fix - Asset sometimes gets deselected when editing adverts
* new - Tooltips for useful tags when editing adverts
* change - Dashboard tweaks

## 5.6.2 - December 3, 2019
* reversal - Date format (dd-mm-yyyy) for date pickers
* new - Datepicker months can be numbers (01-12) or names (Jan-Dec)

## 5.6.1 - October 26, 2019
* change - More modern and polished look for notifications
* change - Clearer date format (dd-mmm-yyyy) for date pickers
* change - Improved WordPress 5.3 compatibility
* change - Dashboard tweaks
* fix - Several PHP Warnings on new installs
* fix - AdRotate now better removes all database settings on uninstall

## 5.6 - October 21, 2019
* new - Support for HTML5 folder structures
* change - Asset dropdown menu no longer looks for flash files
* fix - Column width for Post Injection settings
* fix - Removed obsolete database options

## 5.5.1 - September 23, 2019
* i18n - Added several language mo files
* i18n - Renamed/converted en_US to proper pot file

## 5.5 - September 20, 2019
* change - Removed many unused Debug code
* change - Tweaks to the installation script
* fix - Dashboard widgets

## 5.4.2 - August 19, 2019
* fix - Minutes showing Hour when scheduling adverts
* fix - Missing semicolon in CSS for groups
* change - Dashboard tweaks

## 5.4.1 - July 22, 2019
* fix - Properly removed 'paid' status from the plugin when creating adverts

## 5.4 - July 22, 2019
* change - Now uses wp_is_mobile instead of Mobile Detect
* change - Updated Settings Dashboards
* removed - Obsolete "Responsive Advert" code and database column

## 5.3 - July 11, 2019
* fix - Possible vulnerability for users with privileged access
* change - Dashboard tweaks

## 5.2 - June 24, 2019
* i18n - Now supports WP Language Packs
* i18n - po/mo files removed from AdRotate
* change - Now requires WordPress 4.6 or newer
* change - Dashboard tweaks

## 5.1.4 - June 17, 2019
* update - Dashboard updates
* update - Various links to my site

## 5.1.3 - June 1, 2019
* fix - Outputting generated CSS code more efficient

## 5.1.2 - May 15, 2019
* fix - Group CSS not always rendered when saving groups

## 5.1.1 - May 13, 2019
* fix - Blinking adverts in Dynamic Groups

## 5.1 - May 2, 2019
* new - Support Dashboard
* new - Facebook Support Group widget
* update - No longer render dynamic CSS for groups on pageload
* update - Menu labels
* update - 150ms fading effect for adverts in dynamic groups
* fix - Graphs showing wrong date ranges for some users
* i18n - Updated all translation files

## 5.0.1 - April 4, 2019
* fix - Stats displaying wrong dates
* fix - Stats dropdowns showing wrong dates

## 5.0 - March 26, 2019
* new - Advertising Partners dashboard
* update - Better localized dates for stats
* update - Dashboard formatting and layout
