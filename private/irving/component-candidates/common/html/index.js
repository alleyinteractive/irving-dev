import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import * as defaultStyles from './themes/default';
import * as unstyled from './themes/unstyled';

/**
 * Output links as menu items.
 */
const HTML = (props) => {
  const {
    content,
    style,
    tag,
    theme = defaultStyles,
  } = props;

  const {
    HTMLWrapper,
  } = theme;

  return (
    <HTMLWrapper
      as={tag}
      dangerouslySetInnerHTML={{ __html: content }} // eslint-disable-line react/no-danger
      style={style}
    />
  );
};

HTML.defaultProps = {
  content: '',
  style: {},
  tag: 'div',
  theme: defaultStyles,
};

HTML.propTypes = {
  /**
   * Markup to render.
   */
  content: PropTypes.string,
  /**
   * CSS styles.
   */
  style: PropTypes.object,
  /**
   * Wrapping element.
   */
  tag: PropTypes.string,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object,
};

const themeMap = {
  default: defaultStyles,
  unstyled,
};

export default withThemes(themeMap)(HTML);
