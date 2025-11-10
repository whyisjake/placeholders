module.exports = {
	preset: '@wordpress/jest-preset-default',
	testMatch: [ '**/tests/jest/**/*.test.js' ],
	setupFilesAfterEnv: [ '<rootDir>/tests/jest/setup.js' ],
	testPathIgnorePatterns: [ '/node_modules/', '/vendor/' ],
	coveragePathIgnorePatterns: [ '/node_modules/', '/vendor/', '/tests/' ],
	collectCoverageFrom: [
		'**/*.js',
		'!**/node_modules/**',
		'!**/vendor/**',
		'!**/tests/**',
		'!jest.config.js',
	],
};
