import * as materialComponents from '@material-ui/core';
import * as materialLabComponents from '@material-ui/lab';
import Fragment from 'component-candidates/fragment';

const mapping = {
  '': Fragment,
  'irving/fragment': Fragment,
  'irving/passthrough': Fragment,
  'irving/body-wrapper': Fragment,
  'irving/footer-wrapper': Fragment,
  'irving/header-wrapper': Fragment,
};

Object.keys(materialComponents).forEach((index) => {
  const name = index
    .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
    .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

  mapping[`material/${name}`] = materialComponents[index];
});

Object.keys(materialLabComponents).forEach((index) => {
  const name = index
    .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
    .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

  mapping[`material-lab/${name}`] = materialLabComponents[index];
});

export default mapping;
