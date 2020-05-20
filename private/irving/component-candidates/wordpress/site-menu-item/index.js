import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import Link from 'component-candidates/common/link';
import * as defaultStyles from './themes/default';

const MenuItem = (props) => {
  const {
    theme,
    title,
    url,
  } = props;

  const { Wrapper } = theme;

  return (
    <Wrapper>
      <li>
        <Link to={url}>
          {title}
        </Link>
      </li>
    </Wrapper>
  );
};

MenuItem.propTypes = {
  // attribute: PropTypes.string,
  // children: PropTypes.node.isRequired,
  // classes: PropTypes.array,
  // description: PropTypes.string,
  // id: PropTypes.int,
  // parent_id: PropTypes.int,
  // target: PropTypes.string,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.string,
  /**
   * Title.
   */
  title: PropTypes.string,
  /**
   * URL.
   */
  url: PropTypes.string,
};

MenuItem.defaultProps = {
  // attribute: '',
  // classes: [],
  // description: '',
  // target: '',
  theme: 'default',
  title: '',
  url: '',
};

const themeMap = {
  default: defaultStyles,
};

export default withThemes(themeMap)(MenuItem);
