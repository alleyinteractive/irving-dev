import * as materialComponents from '@material-ui/core';
import * as materialLabComponents from '@material-ui/lab';
import Fragment from 'component-candidates/fragment';

const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
  '': Fragment,
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
      acc[`material-lab/${transformName(key)}`] = materialComponents[key];
      return { ...acc };
    },
    {}),
};
