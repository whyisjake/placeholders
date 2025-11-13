/**
 * Placeholders Block Editor Script
 * Registers all ad placeholder blocks for the block editor
 */

( function () {
	const { registerBlockType } = wp.blocks;
	const { useBlockProps, InspectorControls } = wp.blockEditor;
	const { PanelBody, ToggleControl, SelectControl } = wp.components;
	const { Fragment } = wp.element;
	const el = wp.element.createElement;

	// Define all ad sizes
	const adSizes = {
		leaderboard: { width: 728, height: 90, label: 'Leaderboard' },
		'medium-rectangle': {
			width: 300,
			height: 250,
			label: 'Medium Rectangle',
		},
		'wide-skyscraper': {
			width: 160,
			height: 600,
			label: 'Wide Skyscraper',
		},
		'mobile-banner': { width: 320, height: 50, label: 'Mobile Banner' },
		billboard: { width: 970, height: 250, label: 'Billboard' },
		'large-rectangle': {
			width: 336,
			height: 280,
			label: 'Large Rectangle',
		},
		'half-page': { width: 300, height: 600, label: 'Half Page' },
		'small-square': { width: 200, height: 200, label: 'Small Square' },
		square: { width: 250, height: 250, label: 'Square' },
		'small-rectangle': {
			width: 180,
			height: 150,
			label: 'Small Rectangle',
		},
		'vertical-rectangle': {
			width: 240,
			height: 400,
			label: 'Vertical Rectangle',
		},
		'large-leaderboard': {
			width: 970,
			height: 90,
			label: 'Large Leaderboard',
		},
		portrait: { width: 300, height: 1050, label: 'Portrait' },
		netboard: { width: 580, height: 400, label: 'Netboard' },
	};

	// Create size options for selects
	const sizeOptions = [
		{ label: 'None (use desktop size)', value: '' },
	].concat(
		Object.keys( adSizes ).map( function ( slug ) {
			return {
				label:
					adSizes[ slug ].label +
					' (' +
					adSizes[ slug ].width +
					'×' +
					adSizes[ slug ].height +
					')',
				value: slug,
			};
		} )
	);

	// Register each block
	Object.keys( adSizes ).forEach( function ( slug ) {
		const size = adSizes[ slug ];

		registerBlockType( 'placeholders/' + slug, {
			edit: ( props ) => {
				const { attributes, setAttributes } = props;
				const {
					responsive,
					mobileSize,
					tabletSize,
					backgroundColor,
					textColor,
				} = attributes;

				// eslint-disable-next-line react-hooks/rules-of-hooks
				const blockProps = useBlockProps( {
					style: {
						width: size.width + 'px',
						height: size.height + 'px',
						backgroundColor: backgroundColor || '#f0f0f0',
						color: textColor || '#666666',
					},
				} );

				// Inspector controls for responsive settings
				const inspectorControls = el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{
							title: 'Responsive Size Mapping',
							initialOpen: true,
						},
						el( ToggleControl, {
							label: 'Enable Responsive Mode',
							help: 'Show different ad sizes for different screen sizes (like Google Ad Manager size mapping)',
							checked: responsive,
							onChange: ( value ) =>
								setAttributes( { responsive: value } ),
						} ),
						responsive &&
							el( SelectControl, {
								label: 'Tablet Size (768px - 1024px)',
								value: tabletSize,
								options: sizeOptions,
								onChange: ( value ) =>
									setAttributes( { tabletSize: value } ),
								help: 'Ad size to display on tablet devices',
							} ),
						responsive &&
							el( SelectControl, {
								label: 'Mobile Size (< 768px)',
								value: mobileSize,
								options: sizeOptions,
								onChange: ( value ) =>
									setAttributes( { mobileSize: value } ),
								help: 'Ad size to display on mobile devices',
							} )
					)
				);

				// Editor preview
				const adPreview = el(
					'div',
					blockProps,
					el(
						'div',
						{ className: 'placeholder-ad-content' },
						el(
							'span',
							{ className: 'placeholder-ad-label' },
							size.label + ( responsive ? ' (Desktop)' : '' )
						),
						el(
							'span',
							{ className: 'placeholder-ad-dimensions' },
							size.width + ' × ' + size.height
						)
					)
				);

				return el( Fragment, {}, inspectorControls, adPreview );
			},
			save() {
				return null; // Server-side rendering
			},
		} );
	} );
} )();
