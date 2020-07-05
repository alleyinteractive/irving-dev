import { Helmet } from 'react-helmet';
import ComponentMap from '@irvingjs/styled-components';
import AdminBar from '@irvingjs/wp-admin-bar';
import { FiSearch } from 'react-icons/fi';
import userThemes from './themes';

export default {
  ...ComponentMap(userThemes),
  'irving/admin-bar': AdminBar,
  'irving/helmet': Helmet,
  'irving-icon/search': FiSearch,
};
