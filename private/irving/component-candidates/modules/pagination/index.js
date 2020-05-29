import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import * as defaultStyles from './themes/default';

/**
 * Pagination.
 *
 * Pagination ui for an archive.
 */
const Pagination = (props) => {
  const {
    children,
    style,
    theme = defaultStyles,
  } = props;

  const {
    PaginationWrapper,
  } = theme;

  return (
    <PaginationWrapper style={style}>
      {children}
    </PaginationWrapper>
  );
};

Pagination.defaultProps = {
  style: {},
  theme: defaultStyles,
};

Pagination.propTypes = {
  /**
   * Children of the component.
   */
  children: PropTypes.node.isRequired,
  /**
   * CSS styles.
   */
  style: PropTypes.object,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object,
};

const themeMap = {
  default: defaultStyles,
};

export default withThemes(themeMap)(Pagination);
