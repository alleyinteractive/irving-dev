import * as materialComponents from '@material-ui/core';
import * as materialLabComponents from '@material-ui/lab';
import Fragment from 'component-candidates/fragment';
import Logo from 'component-candidates/logo';
// import HTML from 'component-candidates/html';

const mapping = {
  '': Fragment,
  'irving/logo': Logo,
  'irving/fragment': Fragment,
  'irving/passthrough': Fragment,
  'irving/body-wrapper': Fragment,
  'irving/footer-wrapper': Fragment,
  'irving/header-wrapper': Fragment,
  // 'html': HTML,
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
