import { Helmet } from 'react-helmet';
import * as materialComponents from '@material-ui/core';
import * as materialLabComponents from '@material-ui/lab';
import * as materialIconsComponents from '@material-ui/icons';
import App from 'component-candidates/layouts/app';
import Fragment from 'component-candidates/common/fragment';
import Logo from 'component-candidates/modules/logo';
import Script from 'component-candidates/common/script';
import Menu from 'component-candidates/wordpress/site-menu';
import MenuItem from 'component-candidates/wordpress/site-menu-item';
// import Text from 'component-candidates/common/text';

const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
  '': Fragment,
  'irving/body-wrapper': Fragment,
  'irving/footer-wrapper': Fragment,
  'irving/fragment': Fragment,
  'irving/header-wrapper': Fragment,
  'irving/helmet': Helmet,
  'irving/logo': Logo,
  'site/menu': Menu,
  'site/menu-item': MenuItem,
  'irving/passthrough': Fragment,
  'irving/script': Script,
  'irving/text': Fragment,
  ...Object.keys(materialComponents)
    .reduce((acc, key) => {
      acc[`material/${transformName(key)}`] = materialComponents[key];
      return { ...acc };
    },
    {}),
  ...Object.keys(materialLabComponents)
    .reduce((acc, key) => {
      acc[`material-lab/${transformName(key)}`] = materialLabComponents[key];
      return { ...acc };
    },
    {}),
  ...Object.keys(materialIconsComponents)
    .reduce((acc, key) => {
      acc[`material-icon/${transformName(key)}`] = materialIconsComponents[key];
      return { ...acc };
    },
    {}),
  app: App,
};
