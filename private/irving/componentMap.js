import { Helmet } from 'react-helmet';
import ComponentMap from '@irvingjs/styled-components';
import AdminBar from '@irvingjs/wp-admin-bar';
import userThemes from './themes';

// Icons.
import { FiSearch } from 'react-icons/fi';

export default {
  ...ComponentMap(userThemes),
  'irving/admin-bar': AdminBar,
  'irving/helmet': Helmet,
  'irving-icon/search': FiSearch,
};
