# Enable Subtitles

Creates new the_subtitle function

## Changelog 

### 2.0.0
- added support for Git Updater
- major code refactor to follow WordPress standards
- changed retrieval function name to `get_the_subtitle()` to mirror native post titles
- changed display function name to `the_subtitle()` to mirror native post titles
- updated shortcode `[subtitle]` to reflect new function names
- added better support for custom post types
- added filter `enable_subtitles_output` to enable modification of subtitle retrieval
- added filter `subtitle_heading_tag` to enable modification of subtitle display tag (e.g. `<h2>` etc)
- added filter `enable_subtitles_post_types` to allow developers to specify which custom post types should support subtitles
- cleaned up HTML output in post editor to mirror native post title input
- various security and coding improvements
- supports PHP 7.0 to PHP 8.3
- supports Multisite

### 1.0.1
- added "Enter subtitle here" field placeholder

### 1.0.0
- initial release
- plugin uses PHP namespaces
- object oriented code
