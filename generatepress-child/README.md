# GeneratePress Child Theme

A professional child theme for GeneratePress.

## Installation

1. Upload the `generatepress-child` folder to `/wp-content/themes/`
2. Make sure the parent theme (GeneratePress) is also installed
3. Activate the child theme through the 'Themes' menu in WordPress

## Features

- **Professional Architecture**: Built using OOP patterns with singleton design
- **Performance Optimized**: Deferred script loading, critical CSS support
- **Customizable CSS**: Comprehensive CSS custom properties (variables)
- **Modern JavaScript**: ES6+ module pattern with utilities
- **Accessibility Ready**: WCAG 2.1 compliant focus styles and ARIA support
- **Translation Ready**: Full i18n support with POT file template
- **Editor Styles**: Block editor styling consistency
- **Admin Styles**: Clean admin interface customizations

## File Structure

```
generatepress-child/
├── assets/
│   ├── css/
│   │   ├── admin.css        # Admin panel styles
│   │   ├── custom.css       # Main custom styles
│   │   └── editor-style.css # Block editor styles
│   ├── js/
│   │   └── custom.js        # Custom JavaScript
│   └── fonts/               # Custom fonts (if needed)
├── languages/               # Translation files
├── functions.php            # Theme functions
├── style.css                # Theme header & base styles
├── screenshot.png           # Theme screenshot
└── README.md                # This file
```

## Customization

### CSS Variables

The theme uses CSS custom properties for easy customization. Edit the `:root` section in `assets/css/custom.css`:

```css
:root {
  --gp-child-primary: #2563eb;
  --gp-child-secondary: #64748b;
  /* ... more variables */
}
```

### JavaScript Modules

The JavaScript is organized into modules:

- `GPChild` - Main theme functionality
- `GPChildAjax` - AJAX request handling
- `GPChildUtils` - Utility functions

Access them globally:

```javascript
window.GPChild.scrollToTop();
window.GPChildUtils.getString("loading");
```

### Custom Events

The theme dispatches custom events:

- `gpChildReady` - Theme initialized
- `gpChildScroll` - Scroll position changed
- `gpChildBreakpointChange` - Viewport breakpoint changed
- `gpChildEscapePressed` - Escape key pressed

## Hooks & Filters

### PHP Filters

- `generatepress_child_critical_css` - Modify critical inline CSS

### PHP Actions

- `gp_child_before_template_part` - Before template part loads
- `gp_child_after_template_part` - After template part loads

## Helper Functions

```php
// Get asset URL
gp_child_asset_url( 'images/logo.png' );

// Get asset path
gp_child_asset_path( 'images/logo.png' );

// Include template part with data
gp_child_get_template_part( 'template-parts/content', 'page', $args );
```

## Requirements

- WordPress 6.5+
- PHP 7.4+
- GeneratePress parent theme

## Changelog

### 1.0.0 (2026-06-01)

- Initial release

## License

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

## Credits

Built as a child theme for [GeneratePress](https://generatepress.com).
