# ed-embed

This plugin converts Media Hopper links into video embeds in the WordPress TinyMCE editor.

## Installation

### WordPress  

1. Copy the ed-embed folder to the plugins folder.
**Note:** for instructions on how to install a plugin manually, please visit [WordPress for dummies](https://www.dummies.com/web-design-development/wordpress/templates-themes-plugins/how-to-install-wordpress-plugins-manually/)
2. From **Network Admin > Plugins** select the plugin and click **Network Activate**.

## Usage

Edit the ed-embed.php file and provide your **$regex** and **$link** variables.
Copy and paste the Media Hooper video page url (not the video itself link) into the editor. The plugin should automatically recognize it and add the video.

## Requirements

The following versions of PHP are supported:

- PHP 7.0
- PHP 7.1
- PHP 7.2

The following versions of WordPress are supported.

 - WordPress 4.9
 
Note: It is very possible that this plugin will work with earlier versions of WordPress, but it has only been tested on the above.

### Important Note

This plugin will not work with WordPress 5.0.0 and the new Guttenberg editor. It is possible it will work with the classic editor in WP 5 but it has not been tested.

## Changelog

See the [project changelog](https://github.com/uoe-dlam/ed-embed/blob/master/CHANGELOG.md)

## Support

Bugs and feature requests are tracked on  [GitHub](https://github.com/uoe-dlam/ed-embed/issues).

If you have any questions about ed-embed _please_  open a ticket here; please  **don't**  email the address below.

## License

This package is released under the GNU License. See the bundled  [LICENSE](https://github.com/uoe-dlam/ed-lti/blob/master/LICENSE)  file for details.

## Credits

This code is principally developed and maintained by the University of Edinburgh's Digital Learning Applications and Media team .

This plugin also makes use of the OpenGraph developed by Scott MacVicar. For further details, please visit  [GitHub](https://github.com/scottmac/opengraph/blob/master/OpenGraph.php)