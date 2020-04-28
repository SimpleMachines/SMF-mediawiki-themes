##smfCurve - Curve Skin for MediaWiki


This is Mediawiki Skin based on Curve for SMF development repository.
Parts of this theme are licensed under [BSD 3-clause license](http://www.opensource.org/licenses/BSD-3-Clause), Others are [GPL] (http://www.gnu.org/copyleft/gpl.html)

######Branches organization:
* ***master*** - is the main branch, supporting MediaWiki 1.27 (LTS)
* ***1.0*** - for old MediaWiki installs (Prior to 1.23)
* ***1.1*** - for MediaWiki 1.23 (LTS)
* ***1.2*** - for MediaWiki 1.25+

[Installing] (https://github.com/SimpleMachines/smfcurve/wiki/Installing) - [Upgrading](https://github.com/SimpleMachines/smfcurve/wiki/Upgrading)

######Notes:

Feel free to fork this repository and make your desired changes.

Please see the [Developer's Certificate of Origin](https://github.com/SimpleMachines/smfcurve/blob/master/DCO.txt) in the repository:
by signing off your contributions, you acknowledge that you can and do license your submissions under the license of the project.

######Branches organization:
* ***master*** - is the main branch
* ***1.1*** - new version for MediaWiki 1.23.x and later

######Important Note for v1.1
This version is valid for MediaWiki 1.23 and later releases, version 1.0 is for 1.22 and before.
This version is for fixing compatibility issue with 1.23.x.
To install this skin;
* Download version 1.1
* Unzip the file rename to smfcurve
* Copy it into skins folder
* Open your LocalSettings.php file and add the following line to the end of the file

> require_once "$IP/skins/smfcurve/smfcurve.php";

If you are upgrading from 1.0;
* Download version 1.1
* Unzip the file rename to smfcurve
* Delete smfcurve folder - smfcurve.php and smfcurve.deps.php
* Copy your newly downloaded folder to skins folder
* Open your LocalSettings.php file and add the following line to the end of the file

> require_once "$IP/skins/smfcurve/smfcurve.php";

######How to contribute:
* fork the repository. If you are not used to Github, please check out [fork a repository](http://help.github.com/fork-a-repo).
* branch your repository, to commit the desired changes.
* sign-off your commits, to acknowledge your submission under the license of the project.
* an easy way to do so, is to define an alias for the git commit command, which includes -s switch (reference: [How to create Git aliases](http://githacks.com/post/1168909216/how-to-create-git-aliases))
* send a pull request to us.

Finally, feel free to play around. That's what we're doing. ;)