/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import * as defaultStyles from './themes/default';

const SiteLogo = (props) => {
  const {
    logoUrl,
    theme,
  } = props;

  const { Wrapper } = theme;

  return (
    <Wrapper>
      <img src={logoUrl} />
    </Wrapper>
  );
};

SiteLogo.propTypes = {
  /**
   * Link for the logo.
   */
  logoUrl: PropTypes.string.isRequired,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object.isRequired,
};

const themeMap = {
  default: defaultStyles,
};

export default withThemes(themeMap)(SiteLogo);
