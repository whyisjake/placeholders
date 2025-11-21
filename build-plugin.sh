#!/bin/bash
# Build script for WordPress.org plugin submission
# This script creates a clean zip file excluding development files

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Building WordPress.org plugin package...${NC}\n"

# Get plugin slug from the main plugin file
PLUGIN_SLUG="placeholders"
VERSION=$(grep "Version:" placeholders.php | awk '{print $3}')

echo "Plugin: $PLUGIN_SLUG"
echo "Version: $VERSION"
echo ""

# Create build directory
BUILD_DIR="build"
PLUGIN_DIR="$BUILD_DIR/$PLUGIN_SLUG"
ZIP_FILE="$PLUGIN_SLUG-$VERSION.zip"

# Clean up old build and zip files
if [ -d "$BUILD_DIR" ]; then
    echo -e "${YELLOW}Cleaning up old build directory...${NC}"
    rm -rf "$BUILD_DIR"
fi

if [ -f "$ZIP_FILE" ]; then
    echo -e "${YELLOW}Removing old zip file...${NC}"
    rm -f "$ZIP_FILE"
fi

# Create fresh build directory
mkdir -p "$PLUGIN_DIR"

echo -e "${YELLOW}Copying plugin files...${NC}"

# Copy all files except development/build files
rsync -av \
    --exclude='.git' \
    --exclude='.github' \
    --exclude='.wordpress-org' \
    --exclude='.claude' \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='coverage' \
    --exclude='bin' \
    --exclude='tests' \
    --exclude='.DS_Store' \
    --exclude='.gitignore' \
    --exclude='.gitattributes' \
    --exclude='.distignore' \
    --exclude='composer.json' \
    --exclude='composer.lock' \
    --exclude='package.json' \
    --exclude='package-lock.json' \
    --exclude='phpunit.xml.dist' \
    --exclude='jest.config.js' \
    --exclude='README.md' \
    --exclude='*.map' \
    --exclude='*.log' \
    --exclude='.env' \
    --exclude='.env.*' \
    --exclude='*.zip' \
    --exclude='build' \
    --exclude='build-plugin.sh' \
    ./ "$PLUGIN_DIR/"

# Create zip file
echo -e "\n${YELLOW}Creating zip file: $ZIP_FILE${NC}"

cd "$BUILD_DIR"
zip -r "../$ZIP_FILE" "$PLUGIN_SLUG" -q

cd ..

# Show results
echo -e "\n${GREEN}✓ Build complete!${NC}"
echo -e "${GREEN}Package created: $ZIP_FILE${NC}"
echo ""
echo "File size: $(du -h "$ZIP_FILE" | cut -f1)"
echo ""
echo -e "${GREEN}Ready to upload to WordPress.org!${NC}"
echo ""

# List what's included
echo -e "${YELLOW}Package contents:${NC}"
unzip -l "$ZIP_FILE" | head -n 30
echo ""
echo -e "${YELLOW}Run 'unzip -l $ZIP_FILE' to see full contents${NC}"
