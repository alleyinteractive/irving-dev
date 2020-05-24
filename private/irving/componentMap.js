import { Helmet } from 'react-helmet';
import * as materialComponents from '@material-ui/core';
import App from 'component-candidates/layouts/app';
import Fragment from 'component-candidates/common/fragment';
import Logo from 'component-candidates/modules/logo';
import Menu from 'component-candidates/modules/menu';
import Byline from 'component-candidates/wordpress/post/byline';

// Icons
import { Search as SearchIcon } from '@material-ui/icons';

const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
  '': Fragment,
  'irving-common/fragment': Fragment,
  'irving-layouts/body-wrapper': Fragment,
  'irving-layouts/footer-wrapper': Fragment,
  'irving-layouts/header-wrapper': Fragment,
  'irving-modules/logo': Logo,
  'irving-modules/menu': Menu,
  'irving/post-byline': Byline,
  'irving/post-featured-media': Fragment,
  'irving/post-list': Fragment,
  'irving/post': Fragment,
  'irving/helmet': Helmet,
  'irving/html': Fragment,
  'material-icon/search': SearchIcon,
  app: App,
  ...Object.keys(materialComponents)
    .reduce((acc, key) => {
      acc[`material/${transformName(key)}`] = materialComponents[key];
      return { ...acc };
    },
    {}),
};
