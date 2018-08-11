=== Complianz GDPR ===
Contributors: RogierLankhorst, ComplianzTeam
Tags: GPDR, AVG,  Cookie warning, EU, Privacy, Cookie block,Cookie policy, Cookie scan
Requires at least: 4.6
License: GPL2
Requires PHP: 5.6
Tested up to: 4.9.7
Stable tag: 1.1.10

Plugin to help you make your site GDPR compliant with a conditional cookie warning and customized cookie policy based on the results of the built in cookie scan. Blocks third thirdparty cookies from all major third party services.

== Description ==
Complianz GDPR will help you to make your website quickly GDPR compliant.

= Features =
* Automatically detects if you need a cookie warning
* Integrated with Google Analytics in a way which makes it not necessary to place a cookie warning for analytics only
* Periodically scans your site for cookies and social media services
* Completely blocks third party cookies like Facebook, Google, Twitter
* Youtube videos are shown without placing cookies
* Generates a legally validated cookie policy
* Detected cookie data is prefilled from the shipped cookie database, which is continually updated

Complianz GDPR is on [GitHub](https://github.com/rlankhorst/complianz-gdpr) as well!

= Love Complianz GDPR? =
If you enjoy this plugin and you want your site to have the best compliancy features, consider purchasing the premium version
= Premium features =
* Cookie statistics: see how many visitors accept, decline, or do not need a cookie warning at all
* Customized and legally validated privacy statement, disclaimer, processing agreements, dataleak reporting tools, created by the Dutch law firm ICTRecht Groningen
* Geo ip cookie warning: show the cookie warning only to visitors from countries with a cookie law
* Respects Do Not track settings in users browsers
* Multilanguage support for the cookie warning
* Premium support

[Contact](https://www.complianz.io/contact/) us if you have any questions, issues, or suggestions. Complianz GDPR is developed by [Complianz BV](https://www.complianz.io).

= Installation =
* Go to “plugins” in your Wordpress Dashboard, and click “add new”
* Click “upload”, and select the downloaded zip file.
* Activate
* Navigate to “Complianz”, and follow the instructions

== Frequently asked questions ==
= Knowledgebase =
Complianz maintains a continuously growing knowledgebase about GDPR on [complianz.io](https://complianz.io)

= When do I need a cookie consent banner? =
Complianz GDPR will determine this automatically. When you are using cookies that store personal data you always have to explicitly ask consent to the user. When you anonymize every single bit of data you don’t have to. Functional cookies don’t require the consent of the user as they are only placed for functional purposes.

= Do I always need a consent checkbox on contact forms? =
Not always. The Complianz GDPR premium plugin can determine if you need this, based on your answers. It mainly depends on the type of information you request.

= What are functional cookies? =
A functional cookie is a cookie which is needed for the technical functioning of the website. Cookies that are used to track if something is placed in the cart, or if a user is logged in are functional cookies. There is no need to request permission for this kind of cookies, nor is there any need to describe them in your cookie policy (although we think that is a good idea).

= What are analytical cookies? =
Analytical cookies are used to track visitors on the website. How do they browse, how long are they staying, and what are they looking at, e.g. Also demography is part of an analytical cookie. They are essential in measuring the usage of a website and to optimize it. They can be seen as real management instruments.

= What are advertising, marketing or tracking cookies? =
Advertising, or marketing cookies, are cookies that are being placed for advertising purposes. Advertising cookies can never be placed without consent. These cookies are only being used for advertising purposes.

Our plugin decides whether a cookie consent banner has to be shown. So you shouldn’t need to worry when using our plugin.

== Change log ==
= 1.1.10 =
* Fix: call to non existing function from cookie config acceptance function

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
1. After installing, please answer the questions in our wizard. This will configure the cookie policy to your organization.
2. After the questions. Please start the website scan. The scan checks the cookies you are using on your website and places these in the cookie policy. You can adjust these afterwards.
3. You can also add scripts yourself. For instance when you are using iframes or other scripts.
4. You can customize the cookie consent banner's style and layout to your liking.
5. Here you can see our cookie consent banner in action. This cookie consent banner blocks all cookies until the visitor gives consent.
