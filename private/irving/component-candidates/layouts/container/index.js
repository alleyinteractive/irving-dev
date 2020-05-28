import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import * as defaultStyles from './themes/default';

const widths = {
  xs: 0,
  sm: 600,
  md: 960,
  lg: 1280,
  xl: 1920,
};

/**
 * Render children in a React fragment or any other HTML tag.
 */
const Container = (props) => {
  const {
    children,
    // imageUrl,
    maxWidth,
    style,
    tag,
    theme,
  } = props;

  const { ContainerWrapper } = theme;

  if ('string' === typeof maxWidth) {
    style.maxWidth = `${widths[maxWidth]}px`;
  } else if ('number' === typeof maxWidth) {
    style.maxWidth = `${maxWidth}px`;
  }

  return (
    <ContainerWrapper as={tag} style={style}>
      {children}
    </ContainerWrapper>
  );
};

Container.defaultProps = {
  children: {},
  // imageUrl: '',
  maxWidth: 'lg',
  style: {},
  tag: 'div',
};

Container.propTypes = {
  /**
   * Children of the component.
   */
  children: PropTypes.node,
  /**
   * Image URL to use as a background.
   */
  // imageUrl: PropTypes.string,
  /**
   * Max width of the container.
   */
  maxWidth: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.number,
  ]),
  /**
   * CSS styles.
   */
  style: PropTypes.object,
  /**
   * Tag used to render.
   */
  tag: PropTypes.string,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object.isRequired,
};

const themeMap = {
  default: defaultStyles,
};

export default withThemes(themeMap)(Container);
