# FSE Styling Guide for Autonomie

## How Styling Works in FSE Themes

### theme.json is the Primary Source

In Full Site Editing themes, **theme.json** handles almost all styling, including editor styles. This is different from classic themes.

#### What theme.json Controls:

✅ **Colors**
```json
"settings": {
  "color": {
    "palette": [...]
  }
}
```
→ Available in both editor AND frontend

✅ **Typography**
```json
"settings": {
  "typography": {
    "fontFamilies": [...],
    "fontSizes": [...]
  }
}
```
→ Fonts automatically loaded in editor

✅ **Spacing**
```json
"settings": {
  "spacing": {
    "spacingSizes": [...]
  }
}
```
→ Available in spacing controls

✅ **Layout**
```json
"settings": {
  "layout": {
    "contentSize": "700px",
    "wideSize": "900px"
  }
}
```
→ Editor respects these widths

✅ **Block Styles**
```json
"styles": {
  "blocks": {
    "core/heading": {...}
  }
}
```
→ Applied in both editor AND frontend

### What You DON'T Need

❌ **editor-style.css** - Removed from Autonomie FSE
- theme.json handles editor styling
- Reduces code duplication
- Ensures editor matches frontend

❌ **Separate editor color palette** - No longer in functions.php
```php
// REMOVED - Now in theme.json
add_theme_support( 'editor-color-palette', [...] );
```

❌ **Editor-specific font loading** - Automatic via theme.json
```php
// REMOVED - No longer needed
add_editor_style( 'assets/css/editor-style.css' );
```

### What You Still Have

✅ **style.css** - Main stylesheet
- Theme metadata in header
- Compiled CSS for frontend
- Legacy styles (normalize, utilities, etc.)

✅ **print.css** - Print-specific styles
- Loaded only for print media
- Not needed in editor

✅ **theme.json** - Complete design system
- Single source of truth
- Editor + Frontend styling
- Design tokens (colors, fonts, spacing)

## How Editor Styling Works Now

### 1. Design Tokens (theme.json)

Colors, fonts, spacing defined once:
```json
{
  "settings": {
    "color": {
      "palette": [
        {"slug": "primary", "color": "#0073aa"}
      ]
    }
  }
}
```

Available everywhere:
- Block editor color picker
- Style variations
- Frontend CSS variables
- Custom CSS

### 2. Block-Specific Styles (theme.json)

Styles for individual blocks:
```json
{
  "styles": {
    "blocks": {
      "core/heading": {
        "typography": {
          "fontFamily": "var(--wp--preset--font-family--serif)"
        }
      }
    }
  }
}
```

Applied to:
- ✅ Editor (WYSIWYG)
- ✅ Frontend (same appearance)

### 3. Global Styles (theme.json)

Site-wide styling:
```json
{
  "styles": {
    "color": {
      "background": "var(--wp--preset--color--background)",
      "text": "var(--wp--preset--color--text)"
    }
  }
}
```

Inherited by:
- All blocks
- Site canvas
- Editor frame

## When You MIGHT Need editor-style.css

### Rare Cases:

1. **Complex Editor-Only Tweaks**
   - Making editor look intentionally different
   - Adding visual guides for editors
   - Editor-specific UI enhancements

2. **Legacy Block Support**
   - If using classic editor blocks
   - Some third-party legacy blocks

3. **Plugin Compatibility**
   - Some plugins expect editor styles
   - Specific plugin integrations

### For Autonomie FSE:
**None of these apply** ✅
- Pure block theme
- No legacy blocks
- Plugin compatibility via theme.json

## File Structure

### What Autonomie Uses:

```
autonomie/
├── theme.json           # ✅ Primary: All design tokens & styles
├── style.css            # ✅ Secondary: Compiled frontend CSS
├── assets/
│   ├── css/
│   │   ├── print.css    # ✅ Print-specific only
│   │   └── editor-style.css  # ❌ Not needed (can remove)
│   └── js/
│       └── block-editor.js   # ✅ Editor scripts (if needed)
```

## CSS Loading Order

### In the Editor:

1. **WordPress Core Styles**
2. **theme.json styles** ← Primary
3. **Block styles from theme.json** ← Specific blocks
4. **Custom CSS (if any)**

### On the Frontend:

1. **WordPress Core Styles**
2. **style.css** ← Main theme CSS
3. **theme.json styles** (as CSS variables)
4. **Inline styles from blocks**

## Best Practices for FSE Styling

### 1. Start with theme.json

Define everything in theme.json first:
```json
{
  "settings": { /* Design tokens */ },
  "styles": { /* Default styles */ }
}
```

### 2. Use CSS Variables

Reference theme.json values:
```css
.my-element {
  color: var(--wp--preset--color--primary);
  font-family: var(--wp--preset--font-family--sans-serif);
}
```

### 3. Block-Specific Styles in theme.json

Instead of CSS selectors:
```json
{
  "styles": {
    "blocks": {
      "core/button": {
        "border": {
          "radius": "50px"
        }
      }
    }
  }
}
```

### 4. Minimal Custom CSS

Only add to style.css what theme.json can't handle:
- Complex selectors
- Pseudo-elements
- Animations
- Legacy styles

## Autonomie's Approach

### What We Migrated to theme.json:

✅ Color palette (20 colors)
✅ Typography system (Lato + Merriweather)
✅ Font sizes (5 sizes)
✅ Spacing scale (6 levels)
✅ Layout widths (700px / 900px)
✅ Block-specific styling
✅ Element styling (headings, links, buttons)

### What Stays in style.css:

✅ Normalize.css
✅ OpenWeb Icons font
✅ Utility classes
✅ Legacy component styles
✅ Complex CSS that theme.json can't handle
✅ Dark mode media queries

### What We Removed:

❌ editor-style.css enqueue
❌ editor-color-palette support declaration
❌ Duplicate styling definitions

## Testing Editor Styles

### Verify Styling Works:

1. **Open Site Editor**
   ```
   http://localhost:8887/wp-admin → Appearance → Editor
   ```

2. **Check Color Picker**
   - Select any block
   - Open color settings
   - Should see all 20 colors from theme.json

3. **Check Typography**
   - Select heading block
   - Should see font family options
   - Should see font size options

4. **Check Layout**
   - Add blocks to template
   - Content should respect 700px width
   - Wide blocks should use 900px

5. **Check Block Styles**
   - Headings should use Merriweather
   - Buttons should have rounded borders
   - Quotes should have left border

### If Something Looks Wrong:

1. **Clear Browser Cache**
2. **Check theme.json syntax** (must be valid JSON)
3. **Check for PHP errors** in functions.php
4. **Verify file permissions**
5. **Test in incognito/private window**

## Customization

### To Change Editor Appearance:

#### Option 1: theme.json (Recommended)
```json
{
  "styles": {
    "blocks": {
      "core/paragraph": {
        "color": {
          "text": "#333"
        }
      }
    }
  }
}
```

#### Option 2: style.css (For Complex Cases)
```css
/* Only if theme.json can't handle it */
.editor-styles-wrapper .wp-block-paragraph::first-letter {
  font-size: 2em;
}
```

#### Option 3: Custom editor-style.css (Last Resort)
Only if absolutely necessary for editor-specific styling.

## Summary

### For Autonomie FSE:

✅ **theme.json handles all editor styling**
- No separate editor-style.css needed
- Cleaner codebase
- Single source of truth
- Better maintainability

✅ **style.css for frontend**
- Main theme CSS
- Legacy styles
- Complex CSS

✅ **print.css for printing**
- Print-specific only

### Result:

🎨 **Consistent styling** between editor and frontend
🚀 **Better performance** (less CSS to load)
🔧 **Easier maintenance** (one place to edit)
✨ **Modern approach** (FSE best practices)

---

**References:**
- [Theme.json Documentation](https://developer.wordpress.org/block-editor/reference-guides/theme-json-reference/)
- [FSE Best Practices](https://developer.wordpress.org/themes/block-themes/templates-and-template-parts/)
- [Block Editor Styling](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/)
