// import { Helmet } from 'react-helmet';
import * as materialComponents from '@material-ui/core';
import * as materialLabComponents from '@material-ui/lab';
import * as materialIconsComponents from '@material-ui/icons';
import AdminBar from './components/adminBar';

const transformName = (original) => original
  .replace(/(^[A-Z])/, ([first]) => first.toLowerCase())
  .replace(/([A-Z])/g, ([letter]) => `-${letter.toLowerCase()}`);

export default {
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
  'admin-bar': AdminBar,
};
