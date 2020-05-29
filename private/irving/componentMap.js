import { Helmet } from 'react-helmet';
import * as materialComponents from '@material-ui/core';
import App from 'component-candidates/layouts/app';
import Byline from 'component-candidates/wordpress/post/byline';
import Container from 'component-candidates/layouts/container';
import Fragment from 'component-candidates/layouts/fragment';
import HTML from 'component-candidates/common/html';
import Link from 'component-candidates/common/link';
import Logo from 'component-candidates/modules/logo';
import Menu from 'component-candidates/modules/menu';
import Pagination from 'component-betas/pagination';

// Icons.
import { Search as SearchIcon } from '@material-ui/icons';

const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
  'irving/pagination': Pagination,
  '': Fragment,
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
  'irving/post': Fragment,
  'irving/post-byline': Byline,
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
