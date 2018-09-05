# Sound Map
#### _Changelog & history_

This plugin was scaffolded from [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate) with a [custom shell script](https://github.com/codiceovvio/WordPress-Plugin-Boilerplate/blob/develop/wp-plugin-boilerplate.sh).

_Author_: Codice Ovvio

***

**v0.4.1**
- Add phpcs.xml custom coding standards ruleset
- Fix all PHPCS errors, except some in development stage

**v0.4.0**
- Add a content factory class to register custom content
- Refactor all the registered types and taxonomies to use the factory
- Remove all references and support to Place Markers, and move them to [Sound Map Places](https://github.com/codiceovvio/soundmap-places) plugin which extends Sound Map

**v0.3.3**
- Move global template functions to class methods
- Add a hook system to the frontend templates
- Add template hooks to the main loader
- Rewrite templates to use actions and template-parts

**v0.3.2**
- Add global functions and template tags
- Refactor Soundmap_Templates class
- Update templates

**v0.3.1**
- Develop and debug the templating system

**v0.3.0**
- Add initial templating system
- Add base default templates for single, archive, taxonomy

**v0.2.0**
- Add place marker content type
- Add leaflet plugins
- Fix map settings and edit screens

**v0.1.1**
- Setup initial content types

**v0.1.0**
- Add CMB2 library
- Add basic content types and a settings page
- Initial working version
