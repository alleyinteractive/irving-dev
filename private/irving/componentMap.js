import { Helmet } from 'react-helmet';
import * as materialComponents from '@material-ui/core';
import AdminBar from 'components/adminBar';
import App from 'component-candidates/layouts/app';
import Fragment from 'component-candidates/common/fragment';
import Logo from 'component-candidates/modules/logo';
import Menu from 'component-candidates/modules/menu';

// Icons
import { Search as SearchIcon } from '@material-ui/icons';

const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
  '': Fragment,
  'admin-bar': AdminBar,
  'irving/body-wrapper': Fragment,
  'irving/footer-wrapper': Fragment,
  'irving/fragment': Fragment,
  'irving/header-wrapper': Fragment,
  'irving/helmet': Helmet,
  'irving/logo': Logo,
  'irving/menu': Menu,
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
