=== Complianz Privacy Suite (GDPR/CaCPA) ===
Contributors: RogierLankhorst, ComplianzTeam
Donate link: https://paypal.me/complianz
Tags: GDPR, AVG, E-Privacy, eprivacy, CaCPA, COPPA, Cookie warning, Cookie Consent, Cookie categories, categories, EU, European Union, US, United States, Privacy, Cookie block, Cookie policy, Cookie scan, cookie, cookie law, analytics, ads, adsense, tagmanager ,facebook, social, youtube
Requires at least: 4.6
License: GPL2
Requires PHP: 5.6
Tested up to: 5.0
Stable tag: 2.0.6

Complianz Privacy Suite (GDPR/CaCPA) with a Cookie Consentbanner and customized Cookie Policy based on the results of the built in Cookie Scan.

== Description ==
Complianz Privacy Suite (GDPR/CaCPA) with a conditional cookie warning and customized cookie policy based on the results of the built in cookie scan. Blocks thirdparty cookies from all major third party services.

= Features =
* New: Ready for CaCPA.
* Automatically detects if you need a cookie warning.
* Anonymizes IP-addresses for Google Analytics if needed.
* Periodically scans your site for cookies and social media services.
* Blocks third party cookies like Facebook, Google, Twitter.
* Blocks iFrames, like YouTube embedded video’s.
* Generate your own legally validated cookie policy.
* Detected cookie data is prefilled from the shipped cookie database, which is continually updated.
* We closely follow the latest developments in the E-Privacy legislation.
* Integrates seamlessly with Monsterinsights, Gravity Forms, Contact Form 7, Woocommerce, Easy Digital Downloads.
* Integrated with WordPress privacy features.

IMPORTANT! Complianz Privacy Suite can help you meet compliance requirements, but you as user must ensure that all requirements are met.

Complianz Privacy Suite is on [GitHub](https://github.com/rlankhorst/complianz-gdpr) as well!

= Love Complianz Privacy Suite? =
If you enjoy this plugin and you want your site to have the best compliancy features, consider purchasing the [premium version](https://complianz.io/pricing), also available for multisite users.

= Premium features =
* Select both US and EU as target region
* Cookie statistics: see how many visitors accept, decline, or do not need a cookie warning at all.
* A/B testing: which banner has the best conversion ratio? Choose the best one and create an amazing user experience.
* Documents: Customized and legally validated privacy statement, disclaimer, processing agreements, dataleak reporting tools, created by the law firm ICTRecht Groningen.
* Geo IP Cookie Consent: Cookie Consent is different everywhere. Show the correct banner based on IP location, but only if a banner is needed.
* CaCPA Consent and Policies.
* COPPA ready with Children's Privacy Policy.
* Respects Do Not track settings in users browsers.
* Multilanguage support for the cookie warning.
* Premium support.
* Premium Updates.

[Contact](https://complianz.io/contact/) us if you have any questions, issues, or suggestions. Complianz Privacy Suite (GDPR/CaCPA) is developed by [Complianz BV](https://complianz.io).

= Installation =
* Go to “plugins” in your Wordpress Dashboard, and click “add new”.
* Click “upload”, and select the downloaded zip file.
* Activate.
* Navigate to “Complianz”, and follow the instructions.

== Frequently asked questions ==
= Knowledgebase =
Complianz Privacy Suite (GDPR/CaCPA) maintains a continuously growing knowledgebase about GDPR, CaCPA and COPPA on [complianz.io](https://complianz.io)

= Is my website GDPR, COPPA & CaCPA compliant with this plugin? =
We cannot guarantee GDPR/COPPA/CaCPA compliancy for your website.

= When do I need a cookie consent banner? =
Complianz Privacy Suite will determine this automatically. Regarding the GDPR, when you are using cookies that store personal data you always have to explicitly ask consent to the user. When you anonymize every single bit of data you don’t have to. Functional cookies don’t require the consent of the user as they are only placed for functional purposes.

Regarding CaCPA, you always have to show which cookies you are using but there's no obligation in asking consent.
= Do I always need a consent checkbox on contact forms? =
Not always. The Complianz Privacy Suite premium plugin can determine if you need this, based on your answers. It mainly depends on the type of information you request.

= What are functional cookies? =
A functional cookie is a cookie which is needed for the technical functioning of the website. Cookies that are used to track if something is placed in the cart, or if a user is logged in are functional cookies. There is no need to request permission for this kind of cookies, nor is there any need to describe them in your cookie policy (although we think that is a good idea).

= What are analytical cookies? =
Analytical cookies are used to track visitors on the website. How do they browse, how long are they staying, and what are they looking at, e.g. Also demography is part of an analytical cookie. They are essential in measuring the usage of a website and to optimize it. They can be seen as real management instruments.

= What are advertising, marketing or tracking cookies? =
Advertising, or marketing cookies, are cookies that are being placed for advertising purposes. Advertising cookies can never be placed without consent. These cookies are only being used for advertising purposes.

Our plugin decides whether a cookie consent banner has to be shown. So you shouldn’t need to worry when using our plugin.

= What is the GDPR? =
The GDPR is a regulation within the EU law on privacy and data protection for any citizen within the EU and European Economic Area. It aims primarily on giving control to individuals over their personal data. The GDPR also addresses the export of personal data outside the EU.

= What is the CaCPA? =
The CaCPA (Californian Privacy Act) is a law set up by the Californian government. The law is adjudged to be one of the toughest and farthest-reaching consumer privacy laws in the US. It is mostly focused on giving insights on what personal data business gather and how to protect and control these personal data.

= What is COPPA? =
The Children’s Online Privacy Protection Act (COPPA) is a law designed to protect the online privacy of children under 13. It was set up in the 1990's and states that website owners have to meet certain requirements regarding visitors with the age under 13.

== Change log ==
= 2.0.6 =
* Tweak: added some new cookies to the database
* Tweak: changed site_url into home_url in the documents output
* Tweak: add support for blocking of instagram cookies
* Fix: third party privacy statements not inserted in cookie policy
* Tweak: less strict policies for websites who do not target California
* Fix: privacy policy URL's not showing in cookie policy

= 2.0.5 =
* Tweak: ajax call for user data only on first visit
* Fix: Cookie blocker inserting class within escaped strings.

= 2.0.4 =
* Fix: Tag manager events not firing outside selected regions
* Tweak: set default region after upgrade from pre-2.0 version
* Fix: showing empty privacy link in US cookie banner
* Fix: count nr of forms, when forms option empty throwing an error.
* Tweak: split checked docs date from edited docs date

= 2.0.3 =
Fix: section count missing the "purpose" section

= 2.0.2 =
Tweak: first reported cookies added to the cookie database

= 2.0.1 =
* Fix: due to commit issue missing file

= 2.0.0 =
* Tested up to WP 5.0
* Tweak: updated Geo IP database to latest
* Tweak: Dropped Youtube "nocookie" support, Youtube places cookies after first interaction, without consent
* Tweak: feedback on active adblockers or anonymous window during scan
* Tweak: user locking of the wizard, preventing multiple users from editing the wizard at the same time
* Tweak: improvements in visual feedback on validation
* Tweak: user interface design
* Feature: reporting of unrecognized cookies
* Feature: CaCPA support
* Feature: Do Not Sell My Personal Information page
* Feature: Do Not Sell My Personal Information opt out form & dashboard
* Feature: US dedicated cookie warning

= 1.2.5 =
* Fix: typo in cookie policy:  add vs ad

= 1.2.4 =
* Tweak: added monsterinsights integration
* Tweak: added a hide revoke button option in the settings
* Tweak: moved statistics script to overridable templates, and included them using action hooks, to make overriding more easy.
* Fix: cookie policy text was not 100% matched when the categories option was selected for the banner.
* Fix: tracking of statistics added new user when the status was not changed.
* Fix: center revoke button not in same style as other revoke buttons
* Fix: Privacy Policy did not show the correct paragraph on sharing with other parties

= 1.2.3 =
* Fix: revoke button showed too large because of changes for the center template

= 1.2.2 =
* Fix: when no social media was found, this could result in an error on showing the scan results

= 1.2.1 =
* Tweak: show social media and third party services from actual detected list, not from wizard.

= 1.2.0 =
* Fix: deleted cookies were added again on the next scan
* Tweak: when cookie database is updated, empty fields get populated from the new data
* Tweak: script center added below menu for fast editing
* Tweak: Added new banner position: centered
* Tweak: Added categories in cookies
* Tweak: Added category/cookie execution management from Tag Manager
* Tweak: Added new template: minimal

= 1.1.14 =
* Readme.txt adjustment

= 1.1.12 =
* Added extra notice about user responsibility regarding GDPR compliancy

= 1.1.11 =
* Adjusted readme as not to claim GDPR compliancy, as per WordPress regulations.

= 1.1.10 =
* Fix: cookie scan time showed UTC time instead of local time
* Fix: call to non existing function from cookie config acceptance function
* Fix: moved cookie policy change date to separate variable
* Tweak: improved security of cookie enabling script

= 1.1.9 =
* Fix: empty contact key in saving data
* Tweak: overlay over dashboard when wizard is not completed yet, to force using wizard
* Tweak: brand color not required anymore
* Tweak: full integration of Matomo in Complianz GDPR

= 1.1.8 =
* Tweak: directory structure

= 1.1.7 =
* Fix WPML/polylang translation bug

= 1.1.6 =
* Added Google Fonts and ReCaptcha to third party list
* Tweaked Cookie Policy
* Added custom CSS option and advanced editing options to cookie banner

= 1.1.5 =
* No + one's anymore for cookie changes, this will only be shown in the dashboard

= 1.1.4 =
* Fix: complete rework of third party cookie blocker, dropped domDocument in favor of regex

= 1.1.3 =
* Fix: accepting the cookie policy did not properly unblock third party scripts
* Tweak: use accept text in cookie policy

= 1.1.2 =
* Tweak: added css styles for cookie policy
* Tweak: added push down style to cookie warning
* Tweak: added Sumo to third party blocked scripts
* Fix: some bugfixes

= 1.1.1 =
* Tweak: updates wizard complete texts
* Fix: youtube nocookie replace

= 1.1.0 =
* new dashboard
* added check if consent checkbox is needed on forms
* integrated wp erase personal data and wp export data
* phone numbers not required anymore
* added a < PHP 5.6 warning

= 1.0.17 =
* Added < PHP 5.6 warning
*
= 1.0.16 =
* Fix: output escaping of html strings, causing the html to show in plain text.

= 1.0.15 =
* Fix: cookieblocker removed script in incorrect way, causing a php error

* 1.0.14
* Tweak: set page as processed before the request is made during scan
* Fix: pre 4.9.6 version of wp could not show admin pages due to privacy capability not existing

= 1.0.12 =
* Fix: scan freezing when URL is loaded http over https.

= 1.0.11 =
* Fix: missing file

= 1.0.10 =
* Fix: missing textdomain

= 1.0.9 =
* Fix: bug due to split between premium and free

= 1.0.8 =
* Added WordPress banner and icon assets

= 1.0.7 =
* Tweak: complete block of third party scripts until user as accepted.

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
1. Dashboard for quick overview over your status, and quick access to privacy features.
2. The scan checks the cookies you are using on your website and places these in the cookie policy. You can adjust these afterwards.
3. Wysiwyg cookie banner editor: you can customize the cookie consent banner's style and layout to your liking
4. Script center to add or block scripts depending on users cookie acceptance.
5. General settings page
6. An example of a cookie banner, hovering over the generated cookie policy.