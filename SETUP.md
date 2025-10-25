# Autonomie FSE - Setup Complete! 🎉

## WordPress Environment is Running

Your local WordPress environment is now running with the Autonomie FSE theme activated!

### Access Information

- **WordPress Site**: http://localhost:8887
- **WordPress Admin**: http://localhost:8887/wp-admin
- **Test Site**: http://localhost:8890

### Login Credentials

- **Username**: `admin`
- **Password**: `password`

### What's Installed

#### Theme
✅ **Autonomie v2.0.0** - FSE Block Theme
- Full Site Editing enabled
- All 9 post formats supported
- Microformats2 & Schema.org markup
- IndieWeb compatible

#### Plugins (All Active)
✅ **ActivityPub** (7.5.0) - Fediverse integration
✅ **IndieWeb** (4.0.5) - IndieWeb toolkit
✅ **IndieWeb Post Kinds** (3.7.3) - Post type extensions
✅ **Semantic Linkbacks** (3.12.0) - Rich comment display
✅ **Syndication Links** (4.5.3) - Cross-posting indicators
✅ **Webmention** (5.5.0) - Comment/mention protocol

#### Sample Content
📝 Created 4 sample posts with different formats:
- Standard post: "Welcome to Autonomie FSE"
- Aside: Quick note
- Quote: William Gibson quote
- Status: Theme migration update

## Development Commands

### Start/Stop Environment

```bash
# Start WordPress
npm run start

# Stop WordPress
npm run stop

# Clean and rebuild
npm run clean && npm run start

# View logs
npm run logs
```

### WP-CLI Commands

```bash
# Run any WP-CLI command
npm run wp -- [command]

# Examples:
npm run wp -- plugin list
npm run wp -- theme list
npm run wp -- user list
npm run wp -- post list
npm run wp -- option get blogname
```

### Common Tasks

```bash
# Create a new post
npm run wp -- post create --post_title="My Post" --post_content="Content here" --post_status=publish

# Create a page
npm run wp -- post create --post_type=page --post_title="About" --post_content="About page" --post_status=publish

# Install a plugin
npm run wp -- plugin install [plugin-slug] --activate

# Update WordPress
npm run wp -- core update

# Export database
npm run wp -- db export

# Flush rewrite rules
npm run wp -- rewrite flush
```

## Testing the Theme

### 1. Visit the Site
Open http://localhost:8887 to see your site with the Autonomie theme.

### 2. Explore the Site Editor
1. Go to http://localhost:8887/wp-admin
2. Navigate to **Appearance → Editor**
3. Explore:
   - Templates (index, single, page, archive, etc.)
   - Template Parts (header, footer, post-meta)
   - Styles (colors, typography, spacing)

### 3. Test Post Formats
1. Go to **Posts → Add New**
2. In the sidebar, find **Post Format**
3. Try different formats: Aside, Quote, Status, etc.
4. Publish and view on the front-end

### 4. Verify Semantic Markup

#### Check Microformats2
Visit your homepage and view source. You should see:
- `h-feed` on the query block
- `h-entry` on each post
- `p-name` on titles
- `e-content` on content
- `dt-published` on dates
- `p-author` + `h-card` on author info

#### Validate with Tools
1. **IndieWebify.me**: https://indiewebify.me/
   - Paste your site URL
   - Check for h-entry, h-card markup

2. **Google Rich Results Test**: https://search.google.com/test/rich-results
   - Paste a post URL
   - Verify Schema.org markup

3. **Microformats Parser**: https://php.microformats.io/
   - Parse your homepage
   - Verify h-feed structure

### 5. Test IndieWeb Features

#### Webmentions
1. Go to **Settings → Discussion**
2. Configure Webmention settings
3. Send a test webmention from another site

#### ActivityPub
1. Go to **Settings → ActivityPub**
2. Configure your profile
3. Test federation with Mastodon/fediverse

#### Post Kinds
1. Enable Post Kinds in **Settings → IndieWeb**
2. Try creating different kinds of posts:
   - Note, Article, Reply, Like, Bookmark, etc.

## Customizing the Theme

### Edit Templates
1. **Appearance → Editor**
2. Click **Templates**
3. Select a template to edit
4. Use blocks to customize layout
5. Save changes

### Edit Template Parts
1. **Appearance → Editor**
2. Click **Patterns → Template Parts**
3. Edit header, footer, or post-meta
4. Changes apply globally

### Customize Styles
1. **Appearance → Editor**
2. Click **Styles** (paint brush icon)
3. Modify:
   - Color palette
   - Typography
   - Spacing
   - Layout settings
4. Save style variations

### Create Block Patterns
Create patterns in `/patterns/` directory:

```php
<?php
/**
 * Title: My Custom Pattern
 * Slug: autonomie/my-pattern
 * Categories: posts
 */
?>
<!-- Your blocks here -->
```

## Troubleshooting

### Site Not Loading
```bash
# Check if Docker is running
docker ps

# Restart environment
npm run stop
npm run start
```

### Database Issues
```bash
# Clean environment
npm run clean
npm run start
```

### Port Conflicts
Edit `.wp-env.json` and change ports:
```json
{
  "port": 8887,
  "testsPort": 8890
}
```

### Plugin Errors
The warning about `MAX_INLINE_MENTION_LENGTH` is harmless and from the semantic-linkbacks plugin.

### Clear Cache
```bash
# Flush WordPress rewrite rules
npm run wp -- rewrite flush

# Clear WordPress transients
npm run wp -- transient delete --all
```

## File Structure Reference

```
autonomie/
├── .wp-env.json           # WordPress environment config
├── theme.json             # Design system & settings
├── style.css              # Theme metadata & compiled styles
├── functions.php          # Theme functions & hooks
├── templates/             # Block templates
│   ├── index.html         # Blog listing
│   ├── single.html        # Single post
│   ├── page.html          # Pages
│   ├── archive.html       # Archives
│   ├── 404.html           # Error page
│   └── search.html        # Search results
├── parts/                 # Template parts
│   ├── header.html        # Site header
│   ├── footer.html        # Site footer
│   └── post-meta.html     # Post metadata
├── patterns/              # Block patterns
│   ├── post-standard.php  # Standard format
│   ├── post-aside.php     # Aside format
│   ├── post-quote.php     # Quote format
│   └── ... (9 total)
└── includes/              # PHP includes
    └── semantics.php      # Semantic markup filters
```

## Next Steps

1. **Explore the Site Editor** - Customize templates and styles
2. **Create Content** - Test all post formats
3. **Verify Semantics** - Use validation tools
4. **Test Responsiveness** - Check mobile/tablet views
5. **Configure IndieWeb** - Set up webmentions, ActivityPub
6. **Customize Theme** - Modify colors, fonts, layouts

## Documentation

- **Migration Guide**: `MIGRATION.md` - Full migration documentation
- **Summary**: `FSE-MIGRATION-SUMMARY.md` - Technical details
- **WordPress Docs**: https://developer.wordpress.org/block-editor/
- **IndieWeb Wiki**: https://indieweb.org/

## Support

- **GitHub Issues**: https://github.com/pfefferle/autonomie/issues
- **IndieWeb Chat**: https://chat.indieweb.org/

---

**Enjoy your new FSE theme!** 🎨✨

**Default Login**: http://localhost:8887/wp-admin (admin/password)
