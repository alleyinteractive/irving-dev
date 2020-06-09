import { Helmet } from 'react-helmet';
import * as materialComponents from '@material-ui/core';
import {
  HTML,
  Link,
  App,
  Container,
  Fragment,
  Logo,
  Menu,
  Byline,
  Permalink,
} from '@irvingjs/styled-components';
// import App from '@irvingjs/core/component-candidates/layouts/app';
// import Byline from '@irvingjs/core/component-candidates/wordpress/post/byline';
// import Permalink from
//   '@irvingjs/core/component-candidates/wordpress/post/permalink';
// import Container from '@irvingjs/core/component-candidates/layouts/container';
// import Fragment from '@irvingjs/core/component-candidates/layouts/fragment';
// import HTML from '@irvingjs/core/component-candidates/common/html';
// import Link from '@irvingjs/core/component-candidates/common/link';
// import Logo from '@irvingjs/core/component-candidates/modules/logo';
// import Menu from '@irvingjs/core/component-candidates/modules/menu';

// Icons.
import { Search as SearchIcon } from '@material-ui/icons';

const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
  '': Fragment,
  'irving/archive-title': Fragment,
  'irving/body-wrapper': Fragment,
  'irving/container': Container,
  'irving/footer-wrapper': Fragment,
  'irving/fragment': Fragment,
  'irving/header-wrapper': Fragment,
  'irving/helmet': Helmet,
  'irving/html': HTML,
  'irving/link': Link,
  'irving/logo': Logo,
  'irving/menu': Menu,
  'irving/menu-item': Fragment,
  'irving/post': Fragment,
  'irving/post-byline': Byline,
  'irving/post-featured-media': Fragment,
  'irving/post-list': Fragment,
  'irving/post-permalink': Permalink,
  'material-icon/search': SearchIcon,
  app: App,
  ...Object.keys(materialComponents)
    .reduce((acc, key) => {
      acc[`material/${transformName(key)}`] = materialComponents[key];
      return { ...acc };
    },
    {}),
};
