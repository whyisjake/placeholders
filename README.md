# Placeholders

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/placeholders.svg)](https://wordpress.org/plugins/placeholders/)
[![WordPress Plugin: Tested WP Version](https://img.shields.io/wordpress/plugin/tested/placeholders.svg)](https://wordpress.org/plugins/placeholders/)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

A WordPress plugin that provides Gutenberg blocks for common ad placeholder sizes.

## Description

Placeholders adds 14 customizable Gutenberg blocks for standard advertising sizes. Perfect for wireframing, prototyping, or reserving space for future ad placements.

## Available Block Sizes

- **Leaderboard** - 728×90px
- **Medium Rectangle** - 300×250px
- **Wide Skyscraper** - 160×600px
- **Mobile Banner** - 320×50px
- **Billboard** - 970×250px
- **Large Rectangle** - 336×280px
- **Half Page** - 300×600px
- **Small Square** - 200×200px
- **Square** - 250×250px
- **Small Rectangle** - 180×150px
- **Vertical Rectangle** - 240×400px
- **Large Leaderboard** - 970×90px
- **Portrait** - 300×1050px
- **Netboard** - 580×400px

## Installation

### From WordPress.org

1. Go to Plugins → Add New in your WordPress admin
2. Search for "Placeholders"
3. Click Install Now and then Activate

### Manual Installation

1. Upload the `placeholders` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the block editor, search for any ad size (e.g., "Leaderboard") in the inserter

## Usage

1. Open any post or page in the block editor
2. Click the '+' button to add a new block
3. Search for an ad size (e.g., "leaderboard", "medium rectangle")
4. Insert the block
5. Customize the background and text colors using the block settings panel

## Features

- ✅ 14 standard IAB ad sizes
- ✅ Customizable background and text colors
- ✅ Support for wide and full alignment
- ✅ Responsive design
- ✅ Clean, minimal styling
- ✅ Shows dimensions and ad name
- ✅ Grouped in dedicated "Placeholders" category

## Development

The plugin uses WordPress Block API version 3 and follows modern WordPress development best practices.

### File Structure

```
placeholders/
├── .github/
│   └── workflows/
│       ├── deploy-to-wordpress-org.yml
│       └── asset-update.yml
├── .wordpress-org/
├── blocks/
│   ├── leaderboard/
│   │   └── block.json
│   └── [other ad sizes]
├── blocks.js
├── editor.css
├── style.css
├── placeholders.php
├── readme.txt
└── README.md
```

### Local Development

```bash
# Clone the repository
git clone https://github.com/whyisjake/placeholders.git

# Symlink to your local WordPress installation
ln -s /path/to/placeholders /path/to/wordpress/wp-content/plugins/placeholders
```

### Deployment to WordPress.org

This plugin uses GitHub Actions for automated deployment to WordPress.org.

#### Setup

1. Add your WordPress.org SVN credentials as GitHub secrets:
   - `SVN_USERNAME`: Your WordPress.org username
   - `SVN_PASSWORD`: Your WordPress.org password

2. To deploy a new version:
   ```bash
   # Update version in placeholders.php and readme.txt
   # Commit changes
   git add .
   git commit -m "Bump version to 1.1.0"

   # Create and push a tag
   git tag 1.1.0
   git push origin main --tags
   ```

3. The GitHub Action will automatically deploy to WordPress.org

#### Assets

Place plugin assets in the `.wordpress-org/` directory:
- `banner-772x250.png` - High resolution banner
- `banner-1544x500.png` - Retina banner
- `icon-128x128.png` - Small icon
- `icon-256x256.png` - Large icon
- `screenshot-1.png` - Screenshot 1
- `screenshot-2.png` - Screenshot 2
- etc.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

GPL v2 or later

## Author

**Jake Spurlock**
- Website: [https://whyisjake.com](https://whyisjake.com)
- GitHub: [@whyisjake](https://github.com/whyisjake)

## Support

- [WordPress.org Support Forum](https://wordpress.org/support/plugin/placeholders/)
- [GitHub Issues](https://github.com/whyisjake/placeholders/issues)

## Changelog

### 1.0.0
- Initial release
- 14 ad placeholder blocks
- Color customization support
- Alignment support
- Custom "Placeholders" block category
