const path = require('path');
const createStyleguideConfig = require('@irvingjs/styleguide');
const config = {
  title: 'Create Irving App',
  sections: [
    {
      name: 'Example Components',
      content: path.join(__dirname, '../example-components/readme.md'),
      components: path.join(__dirname, '../example-components/**/*.js'),
    },
  ],
};
module.exports = createStyleguideConfig(config);
