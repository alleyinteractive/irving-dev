import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import Link from 'component-candidates/common/link';
import * as defaultStyles from './themes/default';

/**
 * Logo.
 *
 * Display the site name or logo.
 *
 * @todo Update with a proper <Heading> component.
 * @todo Update with a proper image component.
 */
const Logo = (props) => {
  const {
    href,
    logoImageUrl,
    siteName,
    style,
    theme,
  } = props;

  const { LogoWrapper, LinkWrapper } = theme;

  return (
    <LogoWrapper style={style}>
      <LinkWrapper
        as={Link}
        href={href}
      >
        {logoImageUrl ? (
          <img src={logoImageUrl} alt={siteName} />
        ) : (
          <h2>{siteName}</h2>
        )}
      </LinkWrapper>
    </LogoWrapper>
  );
};

Logo.defaultProps = {
  href: '/',
  logoImageUrl: '',
  siteName: '',
  style: {},
  theme: defaultStyles,
};

Logo.propTypes = {
  /**
   * URL the logo should link to.
   */
  href: PropTypes.string,
  /**
   * URL of the image.
   */
  logoImageUrl: PropTypes.string,
  /**
   * Site name.
   */
  siteName: PropTypes.string,
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

export default withThemes(themeMap)(Logo);
