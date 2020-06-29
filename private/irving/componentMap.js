import { Helmet } from 'react-helmet';
import ComponentMap from '@irvingjs/styled-components';
import AdminBar from '@irvingjs/wp-admin-bar';

// Icons.
import { FiSearch } from 'react-icons/fi';

export default {
  ...ComponentMap,
  'irving/admin-bar': AdminBar,
  'irving/helmet': Helmet,
  'irving-icon/search': FiSearch,
};
