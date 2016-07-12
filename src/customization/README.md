# Customization Module

This module provides customization functionality for themes.  See the feature list for details.  This module is part of the [Theme API](https://github.com/hellofromtonya/Theme-API).
	 	
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

## Configuration

The configuration file(s) is(are) found in `config\customization\`.

## Test Mode

As themes do not have the above `customization` parameter in `hook_extra`, this module includes a testing submodule, which adds the `customization` parameters.  Through the configuration file, you can turn on and off using the testing submodule.

## Contributions

All feedback, bug reports, and pull requests are welcome.