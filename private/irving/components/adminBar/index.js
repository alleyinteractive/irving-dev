import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import * as defaultStyles from './themes/default';

const AdminBar = (props) => {
  const {
    iframeSrc,
    theme,
  } = props;
  const { Iframe } = theme;

  return (
    <Iframe
      title="Admin Bar Iframe"
      src={iframeSrc}
    />
  );
};

AdminBar.defaultProps = {
  theme: 'default',
};

AdminBar.propTypes = {
  iframeSrc: PropTypes.string.isRequired,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object,
};

const themeMap = {
  default: defaultStyles,
};

export default withThemes(themeMap)(AdminBar);
