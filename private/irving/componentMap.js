import { Helmet } from 'react-helmet';
import ComponentMap from '@irvingjs/styled-components';
import AdminBar from '@irvingjs/wp-admin-bar';

// Icons.
import { Search as SearchIcon } from '@material-ui/icons';

export default {
  ...ComponentMap,
  'irving/admin-bar': AdminBar,
  'irving/helmet': Helmet,
  'material-icon/search': SearchIcon,
};
