import { Helmet } from 'react-helmet';
import ComponentMap from '@irvingjs/styled-components';
import * as materialComponents from '@material-ui/core';
import AdminBar from '@irvingjs/wp-admin-bar';

// Icons.
import { Search as SearchIcon } from '@material-ui/icons';

const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
  ...ComponentMap,
  'irving/admin-bar': AdminBar,
  'irving/helmet': Helmet,
  'material-icon/search': SearchIcon,
  ...Object.keys(materialComponents)
    .reduce((acc, key) => {
      acc[`material/${transformName(key)}`] = materialComponents[key];
      return { ...acc };
    },
    {}),
};
