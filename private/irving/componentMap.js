import * as materialComponents from '@material-ui/core';
import * as materialLabComponents from '@material-ui/lab';
import * as materialIconsComponents from '@material-ui/icons';
import Fragment from 'component-candidates/fragment';
import Logo from 'component-candidates/logo';
import Text from 'component-candidates/text';

const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
  '': Fragment,
  'irving/logo': Logo,
  'irving/text': Text,
  'irving/fragment': Fragment,
  'irving/passthrough': Fragment,
  'irving/body-wrapper': Fragment,
  'irving/footer-wrapper': Fragment,
  'irving/header-wrapper': Fragment,
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
};
