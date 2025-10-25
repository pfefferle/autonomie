# Includes & Integrations Cleanup - FSE Migration

## Summary

The `includes/` and `integrations/` directories have been cleaned up to remove classic theme dependencies that no longer function with Full Site Editing (FSE) block templates.

---

## Removed Files from includes/

### 1. customizer.php
- **Status**: ❌ REMOVED
- **Reason**: Empty/commented out functionality
- **Original Purpose**: Customizer settings for advanced theme features
- **Why removed**:
  - Function was already commented out (`// add_action`)
  - FSE uses theme.json and Site Editor instead of Customizer
  - No functionality was actually enabled
- **Impact**: None (was already disabled)

### 2. widgets.php
- **Status**: ❌ REMOVED
- **Reason**: Classic widgets not compatible with FSE
- **Original Purpose**:
  - Registered 4 sidebar widget areas (sidebar-1, sidebar-2, sidebar-3, entry-meta)
  - Registered custom widgets (Autonomie_Author_Widget, Autonomie_Taxonomy_Widget)
  - Set up default widgets on theme activation
- **Why removed**:
  - FSE uses block-based template parts instead of widget areas
  - Custom widgets would need to be converted to blocks
  - Sidebar registration is not needed in FSE (templates handle layout)
- **Impact**:
  - Widget areas no longer available
  - Custom Author and Taxonomy widgets removed
  - Users should use blocks in template parts instead
- **Replacement**:
  - Use block patterns in templates
  - Add blocks directly to template parts (header, footer, sidebar areas)

### 3. featured-image.php
- **Status**: ❌ REMOVED
- **Reason**: Full-width featured image functionality deprecated
- **Original Purpose**:
  - Added checkbox to use featured image as full-width post cover
  - Stored `full_width_featured_image` post meta
  - Applied inline CSS for background image on header
  - Added `has-full-width-featured-image` body class
- **Functions removed**:
  - `autonomie_the_post_thumbnail()` - Display featured image
  - `autonomie_content_post_thumbnail()` - Add small image to content
  - `autonomie_featured_image_meta()` - Checkbox in admin
  - `autonomie_save_post()` - Save meta field
  - `autonomie_has_full_width_featured_image()` - Check if enabled
  - `autonomie_enqueue_featured_image_scripts()` - Inline CSS
  - `autonomie_full_width_featured_image_post_class()` - Body class
  - `autonomie_register_meta()` - Register post meta
  - `autonomie_enqueue_block_editor_assets()` - Editor checkbox script
- **Why removed**:
  - Feature was decided to be removed (see COMPLETE.md)
  - Required classic template integration
  - Editor checkbox used deprecated WordPress hooks
  - Not compatible with FSE Cover block approach
- **Impact**:
  - No more full-width hero images on posts
  - Post meta `full_width_featured_image` no longer saved
- **Replacement**:
  - Use Cover block in post content
  - Use Featured Image block with wide/full alignment
  - Create custom pattern for hero post layout

### 4. template-functions.php
- **Status**: ❌ REMOVED
- **Reason**: Classic template helper functions not used in FSE
- **Original Purpose**: Helper functions for PHP templates
- **Functions removed** (17 total):
  - `autonomie_content_nav()` - Pagination links
  - `autonomie_posted_by()` - Author byline with microformats
  - `autonomie_posted_on()` - Post date with microformats
  - `autonomie_post_id()` - Post ID attribute
  - `autonomie_get_post_id()` - Get post ID class
  - `autonomie_main_class()` - Main element classes
  - `autonomie_get_main_class()` - Get main classes
  - `autonomie_get_the_archive_title()` - Archive title
  - `autonomie_show_page_banner()` - Check if show banner
  - `autonomie_get_post_format()` - Get post format icon
  - `autonomie_get_post_format_string()` - Format name
  - `autonomie_get_post_format_link()` - Format archive link
  - `autonomie_get_archive_type()` - Archive type detection
  - `autonomie_get_archive_author_meta()` - Author meta info
  - `autonomie_get_the_archive_description()` - Archive description
  - `autonomie_reading_time()` - Calculate reading time
  - `autonomie_the_content()` - Wrapped content output
- **Why removed**:
  - None of these functions are called in FSE block templates
  - Block templates use native WordPress blocks instead
  - Microformats2 classes now added via render_block filters (semantics.php)
- **Impact**: Functions no longer available for child themes or plugins
- **Replacement**:
  - Use WordPress core blocks (Post Author, Post Date, Post Title, etc.)
  - Semantic classes injected via render_block filters
  - Create custom blocks if advanced functionality needed

---

## Retained Files in includes/

### ✅ semantics.php (KEPT - CRITICAL)
- **Purpose**: Inject microformats2 and schema.org markup into blocks
- **How it works**: Uses `render_block` filters to add semantic classes
- **Filters added** (15 total):
  - `render_block_core/post-template` - h-entry, hentry classes
  - `render_block_core/post-title` - p-name, entry-title
  - `render_block_core/post-content` - e-content, entry-content
  - `render_block_core/post-excerpt` - p-summary
  - `render_block_core/post-date` - dt-published, dt-updated
  - `render_block_core/post-author` - p-author, h-card
  - `render_block_core/post-featured-image` - u-featured, u-photo
  - `render_block_core/post-terms` - p-category
  - `render_block_core/site-title` - p-name
  - `render_block_core/query` - h-feed on archives
  - `render_block_core/comment-template` - h-cite, p-comment
  - `render_block_core/search` - SearchAction schema
  - And more...
- **Why critical**: Core functionality for IndieWeb/semantic web support
- **Impact**: All blocks automatically get semantic classes

### ✅ feed.php (KEPT)
- **Purpose**: Customize RSS/Atom feed output
- **Functions**:
  - Adds rel="self" link to feeds
  - Customizes feed templates
  - Adds additional feed metadata
- **Why kept**: Feed customization still relevant in FSE
- **Impact**: RSS feeds continue to work properly

### ✅ compat.php (KEPT)
- **Purpose**: Compatibility and enhancement functions
- **Functions**:
  - `autonomie_comment_autocomplete()` - Add autocomplete to comment fields
  - `autonomie_comment_field_input_type()` - Add enterkeyhint attribute
  - `autonomie_query_format_standard()` - Fix standard post format archive
  - `autonomie_add_lazy_loading()` - Add lazy loading to content images
  - `get_self_link()` - Polyfill for older WordPress
- **Why kept**: Still useful for FSE sites
- **Impact**: Comment forms and content display enhanced

### ✅ webactions.php (KEPT)
- **Purpose**: IndieWeb Web Actions support
- **Functions**:
  - `autonomie_webaction_comment_reply_link()` - Wrap reply links
  - `autonomie_webaction_comment_form_before()` - Add <indie-action> wrapper
  - `autonomie_webaction_comment_form_after()` - Close wrapper
- **Filters**:
  - `comment_reply_link` - Adds <indie-action> to reply links
  - `comment_form_before` / `comment_form_after` - Wraps comment form
- **Why kept**: Comment blocks still use these hooks
- **Impact**: IndieWeb interactions continue to work

---

## Removed integrations/ Directory

The `integrations/` directory has been completely removed because the integration files relied on **custom action hooks** that were only fired from classic PHP templates. These hooks are **not present in FSE block templates**.

**Note**: All removed files are in Git history and can be restored if needed.

### Why Integration Files Don't Work in FSE

All three integration files use custom action hooks:
- `autonomie_archive_author_meta`
- `autonomie_archive_author_followers`
- `autonomie_before_entry_content`
- `autonomie_post_format`
- `autonomie_entry_footer`

These hooks were called via `do_action()` in classic PHP templates like:
- `author.php` - Author archive template
- `content.php` - Post content template parts
- `single.php` - Single post template

**FSE block templates use HTML files** (templates/*.html) which cannot execute PHP code or fire WordPress hooks.

### Integration Files Removed

#### 1. activitypub.php
- **Purpose**: ActivityPub plugin integration
- **Original functionality**:
  - Added Fediverse follow button to author archives
  - Counted ActivityPub followers
- **Hooks used**:
  - `autonomie_archive_author_meta` - Add follow button
  - `autonomie_archive_author_followers` - Add follower count
- **Why removed**: Author archive template is now HTML, doesn't fire hooks
- **Plugin still works**: ActivityPub plugin functions independently
- **Lost functionality**: Theme-specific follow button and follower display

#### 2. post-kinds.php
- **Purpose**: Post Kinds plugin integration
- **Original functionality**:
  - Disabled Post Kinds default display
  - Added reply context above post content
  - Replaced post format icon with post kind icon
- **Hooks used**:
  - `autonomie_before_entry_content` - Show kind context
  - `autonomie_post_format` - Replace format with kind
- **Why removed**: Single post template is now HTML, doesn't fire hooks
- **Plugin still works**: Post Kinds plugin functions independently
- **Lost functionality**: Custom kind display and reply context positioning

#### 3. syndication-links.php
- **Purpose**: Syndication Links plugin integration
- **Original functionality**:
  - Removed plugin's default content filter
  - Removed plugin's CSS
  - Added syndication links to post footer
- **Hooks used**:
  - `autonomie_entry_footer` - Display syndication links
- **Why removed**: Post footer in template is now HTML, doesn't fire hooks
- **Plugin still works**: Plugin functions independently
- **Lost functionality**: Theme-styled syndication links in footer

### How Plugins Still Work

All three plugins (ActivityPub, Post Kinds, Syndication Links) are **fully functional** without the integration files. They:
- Register their own post types and taxonomies
- Add their own admin interfaces
- Process webmentions and federation
- Store and retrieve data

**What's lost**: Only the theme-specific **display customization** is lost. The plugins will use their default display methods instead.

---

## Restoring Integration Functionality

If you want to restore the integration features in FSE, you have several options:

### Option 1: Use Plugin Default Display (Easiest)
- Let each plugin handle its own display
- ActivityPub, Post Kinds, and Syndication Links all have built-in display
- No theme code needed
- **Pros**: No work required, plugins handle everything
- **Cons**: Less control over styling and placement

### Option 2: Create Block Patterns
Create patterns that include plugin shortcodes or functions:

```php
// patterns/post-with-kinds.php
<?php
/**
 * Title: Post with Kind Display
 * Slug: autonomie/post-with-kinds
 * Categories: posts
 */
?>
<!-- wp:group {"className":"entry-reaction"} -->
<div class="wp-block-group entry-reaction">
  <?php if ( function_exists( 'Kind_View::get_display' ) ) {
    echo Kind_View::get_display();
  } ?>
</div>
<!-- /wp:group -->
```

### Option 3: Create Custom Blocks
Convert integration functionality to custom blocks:

```php
// blocks/syndication-links-block.php
register_block_type( 'autonomie/syndication-links', array(
  'render_callback' => function() {
    if ( function_exists( 'get_syndication_links' ) ) {
      return get_syndication_links();
    }
    return '';
  }
));
```

### Option 4: Use render_block Filters (Most Complex)
Add integration via render_block filters like semantics.php does:

```php
// Add syndication links after post content
function autonomie_add_syndication_links( $block_content, $block ) {
  if ( is_singular() && function_exists( 'get_syndication_links' ) ) {
    $syndication = get_syndication_links( null, array( 'show_text_before' => null ) );
    $block_content .= '<div class="syndication-links">' . $syndication . '</div>';
  }
  return $block_content;
}
add_filter( 'render_block_core/post-content', 'autonomie_add_syndication_links', 10, 2 );
```

### Option 5: Hybrid Approach
Create a template part that includes PHP:

```php
// parts/post-footer.php (PHP file, not HTML)
<?php
/**
 * Template part for post footer with integrations
 */

// Syndication Links
if ( function_exists( 'get_syndication_links' ) ) {
  echo '<div class="syndication-links">';
  _e( 'Syndication Links', 'autonomie' );
  echo get_syndication_links( null, array( 'show_text_before' => null ) );
  echo '</div>';
}

// Post Kinds
if ( function_exists( 'Kind_View::get_display' ) ) {
  echo '<div class="entry-reaction">' . Kind_View::get_display() . '</div>';
}
```

Then use Template Part block with slug "post-footer".

**Recommendation**: Use **Option 4 (render_block filters)** as it's most consistent with the theme's semantic approach and requires no user intervention.

---

## Current includes/ Structure

```
includes/
├── compat.php           ✅ Comment form enhancements, lazy loading
├── feed.php             ✅ RSS/Atom feed customization
├── semantics.php        ✅ Microformats2/Schema.org via render_block filters
└── webactions.php       ✅ IndieWeb comment interactions
```

**4 files remaining** (down from 8 files)

---

## functions.php Updates

### Before
```php
// Semantic HTML functions (microformats2, schema.org)
require get_template_directory() . '/includes/semantics.php';

// Template functions and custom template tags
require get_template_directory() . '/includes/template-functions.php';

// Feed customization
require get_template_directory() . '/includes/feed.php';

// Compatibility functions
require get_template_directory() . '/includes/compat.php';

// WebActions support
if ( file_exists( get_template_directory() . '/includes/webactions.php' ) ) {
	require get_template_directory() . '/includes/webactions.php';
}

// Featured image functionality (for backward compatibility)
if ( file_exists( get_template_directory() . '/includes/featured-image.php' ) ) {
	require get_template_directory() . '/includes/featured-image.php';
}
```

### After
```php
// Semantic HTML functions (microformats2, schema.org)
require get_template_directory() . '/includes/semantics.php';

// Feed customization
require get_template_directory() . '/includes/feed.php';

// Compatibility functions (comment form enhancements, etc.)
require get_template_directory() . '/includes/compat.php';

// WebActions support (IndieWeb comment interactions)
require get_template_directory() . '/includes/webactions.php';
```

**Removed**:
- template-functions.php (not used in FSE)
- featured-image.php (functionality deprecated)
- Conditional file_exists checks (files always present now)

---

## Plugin Compatibility

### Still Works ✅
- **ActivityPub** - Full federation support, default display
- **IndieWeb Post Kinds** - All functionality, default display
- **Syndication Links** - Cross-posting, default display
- **Webmention** - Receiving and sending webmentions
- **Semantic Linkbacks** - Enhanced comment display
- **IndieWeb Plugin** - All IndieWeb functionality

### Lost Features ⚠️
- Custom ActivityPub follow button in author archives
- Custom Post Kinds display positioning
- Custom Syndication Links footer display
- Author follower count from ActivityPub

**All lost features are cosmetic** - core functionality remains intact.

---

## Testing Checklist

After cleanup, verify:

### Core Functionality
- [ ] Site loads without PHP errors
- [ ] Posts display correctly
- [ ] Comments work
- [ ] RSS feeds work
- [ ] Search works

### Semantic HTML
- [ ] Microformats2 classes present (check with view-source)
- [ ] Schema.org markup present
- [ ] h-entry, h-card, h-feed classes on appropriate elements
- [ ] Validate with https://php.microformats.io/

### IndieWeb Features
- [ ] Webmentions can be sent
- [ ] Webmentions can be received
- [ ] ActivityPub federation works
- [ ] Post Kinds display (may look different)
- [ ] Syndication links appear (may look different)

### Comment Forms
- [ ] Comment form displays
- [ ] Autocomplete attributes present
- [ ] Reply links work
- [ ] <indie-action> wrappers present (if using webactions.php)

---

## Migration Impact

### Positive Changes ✅
- Cleaner codebase (4 files vs 8)
- Removed unused classic template functions
- Removed deprecated featured image functionality
- All essential functionality preserved
- Better separation of concerns

### Neutral Changes ⚠️
- Plugin integrations use default display instead of custom
- Some visual customizations lost
- Functionality can be restored via blocks/patterns if needed

### No Negative Impact ✅
- All plugins continue to function
- Semantic markup preserved
- IndieWeb support intact
- RSS feeds unchanged
- Comment system works

---

## Recommendations

### For Most Users
**No action needed**. The cleanup removes only unused code and deprecated features. All essential functionality is preserved.

### For Advanced Users
If you want custom plugin display:
1. Review Git history for removed integration files
2. Choose restoration method (see "Restoring Integration Functionality")
3. Implement using render_block filters or custom blocks
4. Test thoroughly

### For Developers
Consider creating:
- Custom blocks for plugin integrations
- Block patterns for common layouts
- render_block filters for automatic integration
- Theme-specific plugin compatibility layer

---

## File Comparison

### Before Cleanup
```
includes/
├── compat.php               ✅ Kept
├── customizer.php           ❌ Removed
├── featured-image.php       ❌ Removed
├── feed.php                 ✅ Kept
├── semantics.php            ✅ Kept
├── template-functions.php   ❌ Removed
├── webactions.php           ✅ Kept
└── widgets.php              ❌ Removed

integrations/
├── activitypub.php          ❌ Removed
├── post-kinds.php           ❌ Removed
└── syndication-links.php    ❌ Removed
```

### After Cleanup
```
includes/
├── compat.php               ✅ Active
├── feed.php                 ✅ Active
├── semantics.php            ✅ Active (CRITICAL)
└── webactions.php           ✅ Active
```

---

## Related Documentation

- **semantics.php** - See file comments for render_block filter details
- **FSE-MIGRATION-SUMMARY.md** - Technical migration overview
- **ASSETS-CLEANUP.md** - Asset cleanup details
- **COMPLETE.md** - Full migration summary

---

**Last Updated**: 2025-10-25
**Version**: 2.0.0
**Status**: ✅ Complete
