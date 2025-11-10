/**
 * Jest setup file
 */

/* global jest */

// Mock WordPress dependencies if needed
global.wp = {
	blocks: {
		registerBlockType: jest.fn(),
	},
	element: {
		createElement: jest.fn(),
	},
	blockEditor: {
		useBlockProps: jest.fn( ( props ) => props ),
	},
};
