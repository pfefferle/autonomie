# Build Scripts

## extract-icons.js

Extracts SVG icon paths from `@wordpress/icons` library and generates a static PHP file for use in server-side rendering.

### How it works

1. Reads icon definitions from `node_modules/@wordpress/icons/build-module/library/`
2. Extracts SVG path data from JavaScript files
3. Generates `src/post-format/icon-paths.php` with static icon paths
4. This file is then copied to `build/` during webpack build

### Why?

- **Production-safe**: Generated PHP file contains static data, no dependency on node_modules
- **Single source of truth**: Icons come from the same `@wordpress/icons` library used in JavaScript
- **Automatic sync**: Running `npm run build` regenerates icons from the latest library version
- **Zero runtime overhead**: Icon paths are hardcoded arrays, no file parsing at runtime

### Usage

The script runs automatically before every build:

```bash
npm run build  # Runs extract-icons.js first via prebuild hook
npm run dev    # Runs extract-icons.js first via predev hook
```

To run manually:

```bash
node build-scripts/extract-icons.js
```

### Generated file

Creates `/src/post-format/icon-paths.php`:

```php
function autonomie_get_post_format_icon_paths() {
    return array(
        'quote' => 'M13 6v6h5.2v4...',
        'image' => 'M19 3H5c-1.1...',
        // ... etc
    );
}
```

This file is committed to git and deployed to production.
