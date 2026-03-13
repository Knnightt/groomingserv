module.exports = {
  preset: 'react-native',
  transformIgnorePatterns: [
    'node_modules/(?!(react-native|@react-navigation|@react-native|react-redux|redux-persist)/)',
  ],
  setupFiles: ['<rootDir>/jest.setup.js'],
};
