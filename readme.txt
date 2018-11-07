=== Complianz Privacy Suite (GDPR/CaCPA) premium ===
Contributors: Complianz Team
Tags: GPDR, AVG, Privacy, ePrivacy
Requires at least: 4.6
License: GPL2
Requires PHP: 5.6
Tested up to: 5.0
Stable tag: 2.0.0

Plugin to make your site GDPR compliant

== Description ==
The Complianz Privacy Suite (GDPR/CaCPA) Premium will help you make your site GDPR/CaCPA compliant

= Installation =
* Go to “plugins” in your Wordpress Dashboard, and click “add new”
* Click “upload”, and select the zip file you downloaded after the purchase.
* Activate
* Navigate to “Complianz”, and follow the instructions

== Frequently Asked Questions ==

== Change log ==
= 2.0.0 =
* Tested up to WP 5.0
* Tweak: updated Geo IP database to latest
* Tweak: Document styling als used in PDF's and post view.
* Tweak: Dropped Youtube "nocookie" support, Youtube places cookies after first interaction, without consent
* Tweak: feedback on active adblockers or anonymous window during scan
* Tweak: user locking of the wizard, preventing multiple users from editing the wizard at the same time
* Tweak: improvements in visual feedback on validation
* Tweak: user interface design
* Fix: bug in dataleak email sending
* Feature: reporting of unrecognized cookies
* Feature: CaCPA support
* Feature: US privacy statement
* Feature: Do Not Sell My Personal Information page
* Feature: Do Not Sell My Personal Information opt out form & dashboard
* Feature: US Processor agreement wizard
* Feature: US Security Breach notification wizard
* Feature: US dedicated cookie warning
* Feature: COPPA childrent's privacy statement

= 1.2.6 =
* Fix: missing space in privacy statement, incorrect reference to cookie statement

= 1.2.5 =
* Tweak: added monsterinsights integration
* Fix: Privacy Policy did not show the correct paragraph on sharing with other parties

= 1.2.4 =
* Tweak: added a hide revoke button option in the settings
* Tweak: moved statistics script to overridable templates, and included them using action hooks, to make overriding more easy.
* Fix: cookie policy text was not 100% matched when the categories option was selected for the banner.
* Fix: tracking of statistics added new user when the status was not changed.
* Fix: center revoke button not in same style as other revoke buttons

= 1.2.3 =
* Fix: centered banner introduction caused the revoke button to show very large for top position banners.

= 1.2.2 =
* Fix: when no social media was found, this could result in an error on showing the scan results

= 1.2.1 =
* Tweak: show social media and third party services from actual detected list, not from wizard.
* Tweak: calculation of best performer without no-warning status
* Fix: no-choice status not tracked

= 1.2.0 =
* Fix: deleted cookies were added again on the next scan
* Tweak: script center added below menu for fast editing
* Tweak: AB testing
* Tweak: Added new banner position: centered
* Tweak: Added categories in cookies
* Tweak: Added new template: minimal

= 1.1.11 =
* Fix: cookie warning with geo ip and caching cached a user requirement, while the site requirement needs to be cached.
* Tweak: email obfuscation in legal documents
* Tweak: cookie warning a/b testing

= 1.1.10 =
* Fix: statistics should also be loaded when do not track is enabled
* Fix: moved cookie policy change date to separate variable
* Tweak: improved security of cookie enabling script

= 1.1.9 =
* Fix: empty contact key in saving data
* Tweak: overlay over dashboard when wizard is not completed yet, to force using wizard
* Fix: compile_statistics_more_info usage in privacy policy
* Tweak: brand color not required anymore
* Tweak: full integration of Matomo in Complianz GDPR

= 1.1.8 =
* Fix WPML/polylang translation bug

= 1.1.7 =
* Fix: count of warnings bug in wizard completed percentage

= 1.1.6 =
* Added statistics for cookie warnings
* Added Google Fonts and ReCaptcha to third party list
* Tweaked Cookie Policy
* Improved geoip for cached websites
* Added custom CSS option and advanced editing options to cookie banner

= 1.1.5 =
* Tweak: cookie changes not adding +one nags, only in the dashboard
* Tweak: custom plugin texts moved to addendum

= 1.1.4 =s
* Fix: complete rework of third party cookie blocker, dropped domDocument in favor of regex

= 1.1.3 =
* Tweak: use accept text in cookie policy

= 1.1.2 =
* Tweak: added css styles for legal documents
* Tweak: added option to add consent box to CF 7 and Gravity forms
* Fix: several bugfixes
* Tweak: improved feedback on dataleak report
* Tweak: added emailing capability for dataleak reports
* Tweak: added push down style to cookie warning
* Tweak: added Sumo to third party blocked scripts

= 1.1.1 =
* Tweak: updates wizard complete texts
* Tweak: updated known cookies list

= 1.1.0 =
* new dashboard
* added check if consent checkbox is needed on forms
* integrated wp erase personal data and wp export data
* phone numbers not required anymore
* added a < PHP 5.6 warning
* improved dataleaks and dataprocessing

= 1.0.9 =
* Fix: change of textdomain
* Fix: output escaping of html strings
* Fix: scan freezing when http URL's loaded over https.

= 1.0.8 =
* Fix: cookieblocker removed script in incorrect way, causing a php error
* Tweak: set page as processed before the request is made during scan
* Fix: pre 4.9.6 version of wp could not show admin pages due to privacy capability not existing

= 1.0.7 =
* Tweak: complete block of third party scripts until user as accepted.
* Tweak: respect Do Not Track setting in browsers

= 1.0.6 =
* Tweak: added menu selection as option in the wizard

= 1.0.5 =
* Tweak: Improved plugins privacy policy additions: making it editable
* Tweak: hide settings popup for cookie warning on mobile, with revoke link in cookie policy
* Tweak: improved dismiss and revoke functionality
* Fix: some bugs in dataleak decision tree

= 1.0.4 =
* Added scan for social media widgets and buttons

= 1.0.3 =
* Fix: retention period not correctly shown in privacy statement

= 1.0.2 =
* optimized cookie scan

= 1.0.1 =
* Translation fixes

= 1.0.0 =

== Upgrade notice ==

== Screenshots ==


== Frequently asked questions ==
