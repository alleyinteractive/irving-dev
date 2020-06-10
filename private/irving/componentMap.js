import { Helmet } from 'react-helmet';
import * as materialComponents from '@material-ui/core';
import {
  App,
  Byline,
  Container,
  Fragment,
  HTML,
  Link,
  Logo,
  Menu,
  Permalink,
} from '@irvingjs/styled-components';

// Icons.
import { Search as SearchIcon } from '@material-ui/icons';

const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
  '': Fragment,
  'irving/container': Container,
  'irving/fragment': Fragment,
  'irving/helmet': Helmet,
  'irving/html': HTML,
  'irving/link': Link,
  'irving/logo': Logo,
  'irving/menu': Menu,
  'irving/post-byline': Byline,
  'material-icon/search': SearchIcon,
  app: App,
  ...Object.keys(materialComponents)
    .reduce((acc, key) => {
      acc[`material/${transformName(key)}`] = materialComponents[key];
      return { ...acc };
    },
    {}),
};
