/**
 * Placeholders Block Editor Script
 * Registers all ad placeholder blocks for the block editor
 */

(function() {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps } = wp.blockEditor;
    const el = wp.element.createElement;

    // Define all ad sizes
    const adSizes = {
        'leaderboard': { width: 728, height: 90, label: 'Leaderboard' },
        'medium-rectangle': { width: 300, height: 250, label: 'Medium Rectangle' },
        'wide-skyscraper': { width: 160, height: 600, label: 'Wide Skyscraper' },
        'mobile-banner': { width: 320, height: 50, label: 'Mobile Banner' },
        'billboard': { width: 970, height: 250, label: 'Billboard' },
        'large-rectangle': { width: 336, height: 280, label: 'Large Rectangle' },
        'half-page': { width: 300, height: 600, label: 'Half Page' },
        'small-square': { width: 200, height: 200, label: 'Small Square' },
        'square': { width: 250, height: 250, label: 'Square' },
        'small-rectangle': { width: 180, height: 150, label: 'Small Rectangle' },
        'vertical-rectangle': { width: 240, height: 400, label: 'Vertical Rectangle' },
        'large-leaderboard': { width: 970, height: 90, label: 'Large Leaderboard' },
        'portrait': { width: 300, height: 1050, label: 'Portrait' },
        'netboard': { width: 580, height: 400, label: 'Netboard' }
    };

    // Register each block
    Object.keys(adSizes).forEach(function(slug) {
        const size = adSizes[slug];

        registerBlockType('placeholders/' + slug, {
            edit: function(props) {
                const blockProps = useBlockProps({
                    style: {
                        width: size.width + 'px',
                        height: size.height + 'px',
                        backgroundColor: props.attributes.backgroundColor || '#f0f0f0',
                        color: props.attributes.textColor || '#666666'
                    }
                });

                return el('div', blockProps,
                    el('div', { className: 'placeholder-ad-content' },
                        el('span', { className: 'placeholder-ad-label' }, size.label),
                        el('span', { className: 'placeholder-ad-dimensions' }, size.width + ' × ' + size.height)
                    )
                );
            },
            save: function() {
                return null; // Server-side rendering
            }
        });
    });
})();
