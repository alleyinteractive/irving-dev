import { Helmet } from 'react-helmet';
import * as materialComponents from '@material-ui/core';
import AdminBar from '@irvingjs/wp-admin-bar';
import App from 'component-candidates/layouts/app';
import Byline from 'component-candidates/wordpress/post/byline';
import Container from 'component-candidates/layouts/container';
import Fragment from 'component-candidates/layouts/fragment';
import HTML from 'component-candidates/common/html';
import Link from 'component-candidates/common/link';
import Logo from 'component-candidates/modules/logo';
import Menu from 'component-candidates/modules/menu';
import SocialSharingItem from
  'component-candidates/wordpress/post/socialSharingItem';

// Icons.
import {
  Facebook as FacebookIcon,
  Search as SearchIcon,
} from '@material-ui/icons';


const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
  '': Fragment,
  'irving/admin-bar': AdminBar,
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
  'irving/post': Fragment,
  'irving/post-byline': Byline,
  'irving/post-featured-media': Fragment,
  'irving/post-list': Fragment,
  'irving/social-sharing-item': SocialSharingItem,
  'material-icon/facebook': FacebookIcon,
  'material-icon/search': SearchIcon,

  app: App,
  ...Object.keys(materialComponents)
    .reduce((acc, key) => {
      acc[`material/${transformName(key)}`] = materialComponents[key];
      return { ...acc };
    },
    {}),
};
