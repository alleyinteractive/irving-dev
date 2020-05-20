/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import * as defaultStyles from './themes/default';

const SiteLogo = (props) => {
  const {
    siteName,
    logo,
    logoUrl,
    theme,
  } = props;

  const { Wrapper, Link } = theme;

  const component = logo ?
    <Logo /> :
    logoUrl ?
      <img src={logoUrl} alt={siteName} /> :
        <h2>{siteName}</h2>;

  return (
    <Wrapper>
      <Link href="/">{component}</Link>
    </Wrapper>
  );
};

SiteLogo.defaultProps = {
  logo: null,
  logoUrl: '',
  siteName: '',
  theme: 'default',
};

SiteLogo.propTypes = {
  /**
   * Logo component to override the image.
   */
  logo: PropTypes.element,
  /**
   * URL of the image.
   */
  logoUrl: PropTypes.string,
  /**
   * Site name.
   */
  siteName: PropTypes.string,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object.isRequired,
};

const themeMap = {
  default: defaultStyles,
};

export default withThemes(themeMap)(SiteLogo);
