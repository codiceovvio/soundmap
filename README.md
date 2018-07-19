# Sound Map

A custom WordPress Plugins to handle Sound Maps.

## Contents

Scaffolded from WordPress Plugin Boilerplate, includes some extra features:


## Features

* This plugin was scaffolded from [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate) with a [custom shell script](https://github.com/codiceovvio/WordPress-Plugin-Boilerplate/blob/develop/wp-plugin-boilerplate.sh), and includes some extra features.
* The boilerplate is based on the [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* The boilerplate uses a strict file organization scheme that makes it easy to organize the files that compose the plugin.
* External libraries are managed with git submodules within the repository.
* A work in progress [Gulp](https://gulpjs.com/) workflow.
* The project includes all needed files for internationalization.

## Installation

Sound Map plugin can be installed directly into your plugins folder like any other plugin.

It's safe to activate the plugin at this point. All code on master branch will not have breaking errors or changes.

## License

Sound Map is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE.txt`.

## Important Notes

### Includes

Note that if you include your own classes, or third-party libraries, there are three locations in which said files may go:

* `soundmap/includes` is where functionality shared between the admin area and the public-facing parts of the site reside
* `soundmap/admin` is for all admin-specific functionality
* `soundmap/public` is for all public-facing functionality

### What About Other Features?

More on these here later on...

# Credits

The initial Sound Map plugin was developed in 2005-2008 by [Enrike Hurtado](http://www.ixi-audio.net/), then  by Xavier Balderas (2009-2015), [Luca Rullo](https://git.audio-lab.org/lrullo) (2015-2018), and in the act of being completely rewritten with this version by [Codice Ovvio](https://github.com/codiceovvio). For this reason was decided to restart tag versioning from the very beginning.

The [old version](https://audio-lab.org/argitalpenak/software/soundmap-plugin/?lang=es) of Sound Map plugin works with [WordPress](http://wordpress.org/) up to version 3.4.2, is [hosted here](http://git.audio-lab.org/lrullo/soundmap_wordpress_plugin) and mantained by [AudioLab](http://www.audio-lab.org/).

## Development, Documentation, and More

If you’re interested in helping out with the development or with writing documentation, please [let me know](codiceovvio@gmail.com) .
