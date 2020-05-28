import { Helmet } from 'react-helmet';
import * as materialComponents from '@material-ui/core';
import App from 'component-candidates/layouts/app';
import Fragment from 'component-candidates/layouts/fragment';
import HTML from 'component-candidates/common/html';
import Logo from 'component-candidates/modules/logo';
import Menu from 'component-candidates/modules/menu';

// Icons.
import { Search as SearchIcon } from '@material-ui/icons';

// Beta components.
const betaComponentMapping = {};

const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
  ...betaComponentMapping,
  '': Fragment,
  'irving/body-wrapper': Fragment,
  'irving/footer-wrapper': Fragment,
  'irving/fragment': Fragment,
  'irving/header-wrapper': Fragment,
  'irving/helmet': Helmet,
  'irving/logo': Logo,
  'irving/menu': Menu,
  'irving/post': Fragment,
  'irving/post-featured-media': Fragment,
  'irving/post-list': Fragment,
  'material-icon/search': SearchIcon,
  app: App,
  ...Object.keys(materialComponents)
    .reduce((acc, key) => {
      acc[`material/${transformName(key)}`] = materialComponents[key];
      return { ...acc };
    },
    {}),
};
