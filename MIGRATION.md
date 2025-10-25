# Autonomie Theme - FSE Migration Guide

## Overview

Autonomie has been migrated from a classic PHP-based theme to a Full Site Editing (FSE) block theme in version 2.0. This document explains what has changed and how to use the new features.

## What's New in Version 2.0

### Full Site Editing Support
- **Block Templates**: All templates (index, single, page, archive, 404, search) are now built with blocks
- **Template Parts**: Header, footer, and post-meta are reusable template parts
- **Site Editor**: Customize your entire site layout through Appearance → Editor
- **No more PHP templates**: Classic PHP templates have been moved to `/classic-templates/` for reference

### Enhanced theme.json
- Complete design system defined in `theme.json`
- Color palette with 20 semantic colors
- Typography scale with Lato (sans-serif) and Merriweather (serif)
- Spacing scale for consistent margins and padding
- Layout settings (contentSize: 700px, wideSize: 900px)

### Semantic HTML Preservation
All microformats2 and Schema.org markup has been preserved through:
- Render block filters that inject semantic classes into WordPress blocks
- h-entry, h-feed, h-card classes automatically applied
- Schema.org itemscope/itemtype attributes maintained
- IndieWeb compatibility fully preserved

### Post Format Support
All 9 post formats are still supported through block patterns:
- Standard, Aside, Audio, Chat, Gallery, Image, Link, Quote, Status, Video
- Each format has its own block pattern in `/patterns/`
- Apply patterns when creating posts for format-specific styling

## Breaking Changes

### 1. Widgets → Blocks
Classic widgets are no longer supported. You'll need to:
- Recreate sidebars using blocks in the Site Editor
- Convert widget content to blocks manually
- Use Block-based widget areas in Appearance → Editor

### 2. Customizer → Site Editor
Theme options have moved:
- **Before**: Appearance → Customize
- **After**: Appearance → Editor
- Colors, typography, and spacing now managed via theme.json
- Site logo, title, and tagline in Site Editor

### 3. Custom Header/Hero Images Removed
Full-width featured images are no longer supported:
- The "Use as post cover (full-width)" option has been removed
- Use regular featured images instead
- Consider using cover blocks for hero sections

### 4. Menu System
Navigation has changed:
- **Before**: Appearance → Menus
- **After**: Appearance → Editor → Navigation
- Create navigation blocks directly in templates
- More flexible menu placement options

## Migration Checklist

### For Site Owners

- [ ] **Backup your site** before updating
- [ ] Test the theme on a staging site first
- [ ] Review customizer settings and note what needs to be recreated
- [ ] Export your menus if you want to preserve them
- [ ] Document any custom widgets you're using
- [ ] Update to WordPress 6.4+ (required)
- [ ] Ensure PHP 7.4+ (required)

### After Updating

- [ ] Visit Appearance → Editor to see your new Site Editor
- [ ] Recreate your navigation in the Site Editor
- [ ] Configure your header and footer template parts
- [ ] Test all templates (single post, page, archives)
- [ ] Verify semantic markup with https://indiewebify.me/
- [ ] Test with your IndieWeb plugins
- [ ] Check dark mode appearance
- [ ] Test responsive design on mobile devices

## Using the New Theme

### Editing Templates

1. Go to **Appearance → Editor**
2. Click **Templates** to see all available templates
3. Click any template to edit it with blocks
4. Use the **List View** (left sidebar) to navigate the block structure

### Editing Template Parts

1. Go to **Appearance → Editor**
2. Click **Patterns** → **Template Parts**
3. Edit header, footer, or post-meta parts
4. Changes apply globally across all templates

### Applying Post Format Patterns

When creating a post:
1. Set the post format (Aside, Audio, etc.) in the post sidebar
2. Insert the corresponding pattern from the patterns library
3. The pattern will apply appropriate styling and semantic markup

### Customizing Colors and Typography

1. Go to **Appearance → Editor**
2. Click **Styles** (paint brush icon)
3. Customize colors, typography, spacing, and layout
4. Save your style variations for different looks

## Post Format Patterns

### Available Patterns

| Pattern | Slug | Use Case |
|---------|------|----------|
| Standard | `autonomie/post-standard` | Regular blog posts with title and content |
| Aside | `autonomie/post-aside` | Short notes without titles (larger text) |
| Quote | `autonomie/post-quote` | Quoted text (larger, italic) |
| Link | `autonomie/post-link` | Link posts with titles |
| Image | `autonomie/post-image` | Image-focused posts (u-photo class) |
| Gallery | `autonomie/post-gallery` | Multiple images |
| Video | `autonomie/post-video` | Video embeds |
| Audio | `autonomie/post-audio` | Audio/podcast posts |
| Status | `autonomie/post-status` | Twitter-like status updates |
| Chat | `autonomie/post-chat` | Conversation transcripts |

## Semantic HTML Classes

The theme automatically adds these microformats2 classes to blocks:

### Feed/Archive Pages
- `h-feed` - Query block on archive pages
- `h-entry` - Individual posts in loops
- `hentry` - Backward compatibility

### Single Posts
- `h-entry` - Post wrapper
- `p-name` - Post title
- `e-content` - Post content
- `p-summary` - Post excerpt
- `dt-published` - Post date
- `p-author` + `h-card` - Author information
- `u-featured` - Featured images (standard posts)
- `u-photo` - Featured images (image/gallery posts)
- `p-category` - Tags and categories

### Author Information
- `h-card` - Author card
- `p-name` / `fn` - Author name
- `u-url` - Author URL
- `u-photo` - Author avatar

### Comments
- `h-entry` + `h-cite` - Comment wrapper
- `p-comment` - Comment class

## Schema.org Microdata

Automatically applied to:
- **BlogPosting** - Single posts
- **Blog** - Archive pages
- **WebPage** - Static pages
- **Person** - Author information
- **Organization** - Publisher information
- **SearchAction** - Search forms
- **ImageObject** - Featured images

## Troubleshooting

### Styles Look Different
1. Check that theme.json loaded correctly
2. Clear browser cache and any caching plugins
3. Verify your custom CSS doesn't conflict
4. Check for plugin style conflicts

### Semantic Classes Missing
1. Ensure you're using WordPress 6.4+
2. Check `includes/semantics.php` is loading
3. Verify render_block filters are active
4. Test with IndieWeb validator tools

### Site Editor Not Working
1. Confirm WordPress 6.4+ and PHP 7.4+
2. Disable conflicting plugins temporarily
3. Switch to a default theme and back
4. Check for JavaScript errors in browser console

### Post Formats Not Applying
1. Ensure post format is set in post editor
2. Manually insert the corresponding pattern
3. Check pattern files exist in `/patterns/`
4. Verify functions.php registered patterns

## Developer Information

### File Structure

```
autonomie/
├── theme.json              # Design system & settings
├── style.css               # Compiled styles + metadata
├── functions.php           # Theme setup & hooks
├── templates/              # Block templates
│   ├── index.html
│   ├── single.html
│   ├── page.html
│   ├── archive.html
│   ├── 404.html
│   └── search.html
├── parts/                  # Template parts
│   ├── header.html
│   ├── footer.html
│   └── post-meta.html
├── patterns/               # Block patterns
│   ├── post-standard.php
│   ├── post-aside.php
│   └── ... (9 total)
├── includes/              # PHP functions
│   ├── semantics.php      # Microformats & Schema.org
│   ├── template-functions.php
│   └── ...
├── assets/                # Static assets
│   ├── css/
│   ├── font/
│   └── images/
└── classic-templates/     # Reference only (archived)
```

### Customization Hooks

The theme provides these filters for customization:

```php
// Modify semantic classes
add_filter( 'autonomie_semantics', $classes, $id );
add_filter( 'autonomie_semantics_body', $classes, $id );

// Modify block output
add_filter( 'render_block_core/post-title', $content, $block );
add_filter( 'render_block_core/post-content', $content, $block );
// ... all core blocks supported

// Modify post format output
add_filter( 'autonomie_post_format', $html );
```

### Creating Child Themes

To create a child theme:

1. Create a new directory: `autonomie-child/`
2. Create `style.css`:

```css
/*
Theme Name: Autonomie Child
Template: autonomie
Version: 1.0.0
*/
```

3. Create `theme.json` to override settings:

```json
{
	"$schema": "https://schemas.wp.org/trunk/theme.json",
	"version": 3,
	"settings": {
		"color": {
			"palette": [
				{
					"name": "Primary",
					"slug": "primary",
					"color": "#your-color"
				}
			]
		}
	}
}
```

4. Create custom templates in `/templates/` to override parent

### Adding Custom Patterns

Create a PHP file in `/patterns/`:

```php
<?php
/**
 * Title: My Custom Pattern
 * Slug: autonomie-child/my-pattern
 * Categories: posts
 */
?>

<!-- wp:group {"className":"my-custom-class"} -->
<div class="wp-block-group my-custom-class">
	<!-- Add your blocks here -->
</div>
<!-- /wp:group -->
```

### Extending Semantic Markup

Add your own render_block filters in child theme:

```php
function my_custom_semantics( $content, $block ) {
	// Add your custom classes
	$content = preg_replace(
		'/class="([^"]*)"/i',
		'class="$1 my-custom-class"',
		$content,
		1
	);
	return $content;
}
add_filter( 'render_block_core/post-title', 'my_custom_semantics', 10, 2 );
```

## Resources

- **WordPress Block Editor Handbook**: https://developer.wordpress.org/block-editor/
- **theme.json Reference**: https://developer.wordpress.org/themes/advanced-topics/theme-json/
- **Microformats2 Spec**: https://microformats.org/wiki/microformats2
- **Schema.org**: https://schema.org/
- **IndieWeb**: https://indieweb.org/
- **Autonomie GitHub**: https://github.com/pfefferle/autonomie

## Getting Help

- **GitHub Issues**: https://github.com/pfefferle/autonomie/issues
- **WordPress Support**: https://wordpress.org/support/theme/autonomie/
- **IndieWeb Chat**: https://chat.indieweb.org/

## Changelog

### Version 2.0.0 (2025)
- 🎉 Complete migration to Full Site Editing (FSE)
- ✨ New theme.json design system
- 🔧 Semantic HTML via render_block filters
- 📦 Block patterns for all 9 post formats
- 🎨 Site Editor support
- ⚡ Improved performance
- 🚫 Removed classic widgets support
- 🚫 Removed custom header/hero images
- ⬆️ Requires WordPress 6.4+ and PHP 7.4+

### Version 1.x.x
- Classic PHP theme architecture
- See git history for older changes

---

**Thank you for using Autonomie!** If you have questions or need help, please open an issue on GitHub.
