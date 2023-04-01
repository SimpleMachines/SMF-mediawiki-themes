## smfCurve - Curve Skin for MediaWiki
This is Mediawiki Skin based on Curve for SMF development repository.
Parts of this theme are licensed under [BSD 3-clause license](https://www.opensource.org/licenses/BSD-3-Clause), Others are [GPL](https://www.gnu.org/copyleft/gpl.html)

#### Versions
* **2.x**: Compatible with SMF2.1
* **1.x**: Compatible with SMF2.0 [No longer actively developed]

#### Branches organization:
* ***master*** - main branch, v2.1 (smfCurve2) compatible with **SMF2.1**, Supporting MediaWiki 1.39+
* ***2.0*** - main branch, v2.0 (smfCurve2) compatible with **SMF2.1**, Supporting MediaWiki 1.31-1.35
* ***1.4*** - for MediaWiki 1.31-1.35 (LTS)
* ***1.3*** - for MediaWiki 1.27 (LTS)
* ***1.2*** - for MediaWiki 1.25+
* ***1.1*** - for MediaWiki 1.23 (LTS)
* ***1.0*** - for old MediaWiki installs (Prior to 1.23)

#### Notes:
Feel free to fork this repository and make your desired changes.

Please see the [Developer's Certificate of Origin](https://github.com/SimpleMachines/smfcurve/blob/master/DCO.txt) in the repository:
by signing off your contributions, you acknowledge that you can and do license your submissions under the license of the project.

#### How to contribute:
* fork the repository. If you are not used to Github, please check out [fork a repository](http://help.github.com/fork-a-repo).
* branch your repository, to commit the desired changes.
* sign-off your commits, to acknowledge your submission under the license of the project.
* an easy way to do so, is to define an alias for the git commit command, which includes -s switch (reference: [How to create Git aliases](https://githacks.com/post/1168909216/how-to-create-git-aliases))
* send a pull request to us.

Finally, feel free to play around. That's what we're doing. ;)

## Installing
Add to your LocalSettings.php
```
wfLoadSkin( 'smfcurve2' );
```

To set as the default skin:
```
$wgDefaultSkin = "smfcurve2";
```

## Customizing
We provide a few simple and more advanced methods to customizing the skin.

### Simple

#### Adding the forum main menu
Add to your LocalSettings.php (After your wfLoadSkin line)
```
$wgsmfRoot = '/path/to/forum';
$wgshowSMFmenu = true;
```

#### Adding the Wiki logo where the SMF logo shows
```
$wguseLogoImage = true;
```

#### Adding the search to the sidebar
```
$wguseSideSearchBox = true;
```

### Advanced
To do more advance customizing, several files can be changed.  However they are designed to allow extending the base skin.

First set in your LocalSettings.php
```
$wgDefaultSkin = "smfcurve2custom";
```

You can modify the following files depending on your needs:
- /inclues/smfCurve2SkinCustom.php
- /inclues/smfCurve2TemplateCustom.php
- /resources/script/custom.js
- /resources/css/custom.css

We have included some sample code to show how modifications may be made.