const styledComponentsConfig = require('@irvingjs/styled');

const config = {
  name: 'irving-dev-app',
  packages: [
    styledComponentsConfig,
  ],
  customizeRedirect: {
    subDomain: 'www',
    protocol: 'https',
  },
};

module.exports = config;
