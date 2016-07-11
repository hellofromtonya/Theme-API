# Theme API Plugin

This plugin adds functionality and features for themes which is currently missing in WordPress core.
	 	
## Features
It's really quite simply as it adds the following functionality:

### Update/upgrade - Customization folder handling
Currently, if a theme is customized, there is no automatic means for the themer or theme shop to push an update to the site without blowing away all of the customizations.  This API provides the means for themers:

- to specify a customization folder, where all customization can be placed and loaded from a bootstrap
- push updates and not overwrite the customization folder

#### Update Request Packet - New Parameter Required
Within the upgrade request packet, the themer will specify an array of  `customization` parameters in the `hook_extras` parameter.  Currently, the packet is as follows:

```
$args = array(
 	'source'                      => '',
 	'destination'                 => '',
 	'clear_destination'           => false,
 	'clear_working'               => false,
 	'abort_if_destination_exists' => true,
 	'hook_extra'                  => array(
 		'theme'  => 'your theme name',
 		'type'   => 'theme',
 		'action' => 'update',
 	),
 );
 ```

To use a customization folder and preserve it, the themer will add the following new parameters to the `hook_extra` parameter:

```
 	'hook_extra'                  => array(
 		'theme'  => 'your theme name',
 		'type'   => 'theme',
 		'action' => 'update',
 		// this is the new parameter when you want to preserve a customization bucket
 		// for developers to do their thang
 		'customization' => array(
 			'preserve'    => true,
 			'folder_name' => 'customization', // name of the customization folder
 			'path'        => '', // path to the customization folder
 		),
 	),
```

## Installation

To install this plugin, you can either download or clone the GitHub repository onto your local machine.

Installation from GitHub is as simple as cloning the repo onto your local machine.  To clone the repo, do the following:

1. Using PhpStorm, open your project and navigate to `wp-content/plugins/`. (Or open terminal and navigate there).
2. Then type: `git clone https://github.com/hellofromtonya/Theme-API.git`.
3. Make sure you that you rename the folder to just `theme-api`.

## Configuration
Everything is configurable using the configuration files found in the `config` folder.

## Contributions

All feedback, bug reports, and pull requests are welcome.

## Status

This plugin is in development and is not ready to use just yet.