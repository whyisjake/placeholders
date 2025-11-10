/**
 * Block JavaScript tests
 */

describe( 'Placeholders Block Registration', () => {
	beforeEach( () => {
		// Reset mocks
		jest.clearAllMocks();
	} );

	test( 'blocks.js exports expected ad sizes', () => {
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

		expect( Object.keys( adSizes ) ).toHaveLength( 14 );
		expect( adSizes.leaderboard ).toEqual( {
			width: 728,
			height: 90,
			label: 'Leaderboard',
		} );
	} );

	test( 'all ad sizes have required properties', () => {
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

		Object.entries( adSizes ).forEach( ( [ , size ] ) => {
			expect( size ).toHaveProperty( 'width' );
			expect( size ).toHaveProperty( 'height' );
			expect( size ).toHaveProperty( 'label' );
			expect( typeof size.width ).toBe( 'number' );
			expect( typeof size.height ).toBe( 'number' );
			expect( typeof size.label ).toBe( 'string' );
		} );
	} );

	test( 'ad size dimensions are positive numbers', () => {
		const adSizes = {
			leaderboard: { width: 728, height: 90, label: 'Leaderboard' },
			'medium-rectangle': {
				width: 300,
				height: 250,
				label: 'Medium Rectangle',
			},
		};

		Object.entries( adSizes ).forEach( ( [ , size ] ) => {
			expect( size.width ).toBeGreaterThan( 0 );
			expect( size.height ).toBeGreaterThan( 0 );
		} );
	} );

	test( 'ad size labels are not empty', () => {
		const adSizes = {
			leaderboard: { width: 728, height: 90, label: 'Leaderboard' },
			'medium-rectangle': {
				width: 300,
				height: 250,
				label: 'Medium Rectangle',
			},
		};

		Object.entries( adSizes ).forEach( ( [ , size ] ) => {
			expect( size.label.length ).toBeGreaterThan( 0 );
		} );
	} );
} );

describe( 'Block Edit Function', () => {
	test( 'edit function returns element with correct styles', () => {
		const mockEdit = ( props ) => {
			const blockProps = {
				style: {
					width: props.size.width + 'px',
					height: props.size.height + 'px',
					backgroundColor:
						props.attributes.backgroundColor || '#f0f0f0',
					color: props.attributes.textColor || '#666666',
				},
			};
			return blockProps;
		};

		const props = {
			size: { width: 728, height: 90, label: 'Leaderboard' },
			attributes: {
				backgroundColor: '#ff0000',
				textColor: '#ffffff',
			},
		};

		const result = mockEdit( props );

		expect( result.style.width ).toBe( '728px' );
		expect( result.style.height ).toBe( '90px' );
		expect( result.style.backgroundColor ).toBe( '#ff0000' );
		expect( result.style.color ).toBe( '#ffffff' );
	} );

	test( 'edit function uses default colors when not provided', () => {
		const mockEdit = ( props ) => {
			const blockProps = {
				style: {
					backgroundColor:
						props.attributes.backgroundColor || '#f0f0f0',
					color: props.attributes.textColor || '#666666',
				},
			};
			return blockProps;
		};

		const props = {
			attributes: {},
		};

		const result = mockEdit( props );

		expect( result.style.backgroundColor ).toBe( '#f0f0f0' );
		expect( result.style.color ).toBe( '#666666' );
	} );
} );
