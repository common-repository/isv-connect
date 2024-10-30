=== ISV Connect ===
Contributors: __jester, acsnaterse
Donate link: https://www.radishconcepts.com/isv-connect/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: ISV, drivingschool, drivingeducation, API
Requires at least: 4.9
Tested up to: 4.9.4
Stable tag: 1.1
Requires PHP: 5.2.4

Improve your WordPress SEO: Write better content and have a fully optimized WordPress site using the Yoast SEO plugin.

== Description ==

### ISV Connect: they way to connect your website to the ISV Software system

Need to connect to the ISV Software system whele keeping your own styling? This plugin connects to ISV by use of an API, so you wouldn't need to use iframes anymore. We have a lot of features for you to use. You can either use our default CSS, templates and/template settings. Or you could override them with your own templates.

### Bug reports

Bug reports for ISV Connect are [welcomed on GitHub](https://github.com/radishconcepts/isv-connect). Please note GitHub is not a support forum, and issues that aren’t properly qualified as bugs will be closed.

== Installation ==

=== From within WordPress ===

1. Visit 'Plugins > Add New'
1. Search for 'ISV Connect'
1. Activate ISV Connect from your Plugins page.
1. Go to "after activation" below.

=== Manually ===

1. Upload the `isv-connect` folder to the `/wp-content/plugins/` directory
1. Activate the ISV Connect plugin through the 'Plugins' menu in WordPress
1. Go to "after activation" below.

=== After activation ===

1. You should see a new menu item labeled 'ISV' in the wp-admin area.
1. Add your API settings
1. Configure your templates
1. Add a shortcode to your pages like this [isv-feed feedcode="XXXXXXXX" filters="true"] where XXXXXXXX is your own personal feedcode, or use our Widget.
1. You're done!

== Changelog ==

= 1.4 =
Release Date: June 1st, 2018

Feature: Added the raw xml body in the ajax return for debugging purposes

= 1.3 =
Release Date: May 15th, 2018

Bugfix: Make sure that the mktime function doens't produce the next day instead of the current requested day
Bugfix: Make sure that only one result also gets displayed properly
Feature: Added the functionality for a custom 'nothing found' message

= 1.2 =
Release Date: March 20th, 2018

Bugfix: make sure alle loopable resultsets are numerical arrays of sets

= 1.1 =
Release Date: March 1st, 2018

Initial release

= 1.0 =
Release Date: February 9th, 2018

Initial release