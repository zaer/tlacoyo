BrowserDetection
================

The [Wolfcast](http://wolfcast.com/) BrowserDetection PHP class facilitates the identification of the user's environment such as Web browser, version, platform family, platform version or if it's a mobile device or not.

This class will try to detect what the user is using from the `HTTP_USER_AGENT` string sent by the Web browser. A good way to use the class would be to gather user statistics or to report the browser and version used for informational purposes. A bad way to use the class would be to serve content based on the browser and version used. **Sites that rely on the user-agent string should be updated to modern techniques, such as feature detection, adaptive layout, and other modern practices.**

Always keep in mind that `HTTP_USER_AGENT` can be easily spoofed by the user.

Features
--------

The [Wolfcast](http://wolfcast.com/) BrowserDetection PHP class is the most accurate detection class. **It has been tested with 14252 different user agent strings and it have a 99.95% accuracy ratio!**

Detects the following broswers:
  * Amaya
  * Android
  * Bingbot
  * BlackBerry
  * BlackBerry Tablet OS
  * Chrome 0.2 - 48+
  * Edge
  * Firebird
  * Firefox 0.10 - 44+
  * Galeon
  * GNU IceCat
  * GNU IceWeasel
  * Googlebot
  * iCab
  * Internet Explorer 1 - 11
  * Internet Explorer Mobile
  * Konqueror
  * Lynx
  * Mozilla
  * MSN TV
  * MSNBot
  * NetPositive
  * Netscape
  * Nokia Browser
  * OmniWeb
  * Opera 4.02 - 35+
  * Opera Mini
  * Opera Mobile
  * Phoenix
  * Safari 1 - 9+
  * W3C Validator (W3C-checklink, Jigsaw, W3C-mobileOK & W3C_Validator)
  * Yahoo! Multimedia
  * Yahoo! Slurp

Detects the following platforms:
  * Android
  * BeOS
  * BlackBerry
  * FreeBSD
  * iPad
  * iPhone
  * iPod
  * Linux
  * Macintosh
  * NetBSD
  * Nokia
  * OpenBSD
  * OpenSolaris
  * OS/2
  * SunOS
  * Symbian
  * Windows
  * Windows CE
  * Windows Phone

Demo and full documentation
---------------------------

You can try the [live demo](http://wolfcast.com/open-source/browser-detection/tutorial.php) of the class and you can read the [documentation](http://wolfcast.com/open-source/browser-detection/doc/Browser_Detection/BrowserDetection.html).

Installation
------------

To install, simply upload `BrowserDetection.php` (found in the `lib` directory) to your web host and `require_once` it in your PHP script.

Usage
-----

```
require_once('BrowserDetection.php');

$browser = new BrowserDetection();

if ($browser->getName() == BrowserDetection::BROWSER_FIREFOX &&
    $browser->compareVersions($browser->getVersion(), '5.0') >= 0) {
    echo 'You are using FireFox version 5 or greater.';
}
```

History
-------

Correctly identifying what Web browser your users are using is an incredibly complex task. If you ever tried to implement something like this you quickly saw how this can become a code mess. Only a few libraries exists and they often get deprecated and becomes abandonware. This is why we created our own detection engine. We didn't started from scratch. The class is a heavily updated version of Chris Schuld's Browser class version 1.9 (which was unmaintained for a couple of years). Chris' class was based on the original work from Gary White.

License
-------

This program is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version (if any).

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details at: [http://www.gnu.org/licenses/lgpl.html](http://www.gnu.org/licenses/lgpl.html)
