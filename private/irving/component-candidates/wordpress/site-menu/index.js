/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import * as defaultStyles from './themes/default';

const Menu = (props) => {
  const {
    children,
    theme,
  } = props;

  const { Wrapper, Inner } = theme;

  return (
    <Wrapper>
      <Inner>
        {children}
      </Inner>
    </Wrapper>
  );
};

Menu.defaultProps = {
  theme: 'default',
  location: '',
};

Menu.propTypes = {
  /**
   * Menu location.
   */
  location: PropTypes.string,
  /**
   * Children of the component.
   */
  children: PropTypes.node.isRequired,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object.isRequired,
};

const themeMap = {
  default: defaultStyles,
};

export default withThemes(themeMap)(Menu);
