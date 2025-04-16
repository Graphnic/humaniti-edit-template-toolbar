# Humaniti Edit Template Toolbar

A WordPress plugin that adds an "Edit Template" button to the admin bar for block themes, allowing direct access to edit the current template in the site editor.

## ⚠️ WARNING

**THIS IS EXPERIMENTAL SOFTWARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND.**

- This plugin is currently in development and should **NOT** be used on production sites
- No warranty is offered, expressed or implied
- Use at your own risk
- Always backup your site before installing any plugin

## Features

- Adds an "Edit Template" button to the WordPress admin bar
- Shows a dropdown of all available templates
- Highlights the current template being viewed
- Direct access to the template editor
- Works with block themes only

## Requirements

- WordPress 5.9 or higher
- Block theme
- User must have `edit_theme_options` capability

## Installation

1. Download the plugin
2. Upload to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

## Usage

1. Ensure you're using a block theme
2. Look for the "Edit Template" button in your admin bar
3. Click to edit the current template or choose another template from the dropdown

## Security

- Only users with `edit_theme_options` capability can see or use the plugin
- All output is properly escaped
- Direct file access is prevented

## License

GPL v2 or later
