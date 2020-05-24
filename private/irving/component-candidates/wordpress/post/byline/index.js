import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import * as defaultStyles from './themes/default';

/**
 * Post byline.
 */
const Byline = (props) => {
  const {
    children,
    lastDelimiter,
    multiDelimiter,
    preText,
    singleDelimiter,
    theme,
    timestamp,
  } = props;

  const {
    Wrapper,
  } = theme;

  console.log(timestamp, lastDelimiter, multiDelimiter, singleDelimiter);

  switch (children.length) {
    default:
    case 0:
      break;

    case 1:
      return (
        <Wrapper>
          {preText && <span>{preText}</span>}
          {children}
        </Wrapper>
      );

    case 2:
      return (
        <Wrapper>
          {preText && <span>{preText}</span>}
          {children[0]}{singleDelimiter}{children[0]}
        </Wrapper>
      );
  }

  return (
    <Wrapper>
      {preText && <span>{preText}</span>}
      {children}
    </Wrapper>
  );
};

Byline.defaultProps = {
  lastDelimiter: ', and',
  multiDelimiter: ', ',
  preText: 'By ',
  singleDelimiter: ' and ',
  theme: defaultStyles,
  timestamp: '',
};

Byline.propTypes = {
  /**
   * Children of the component.
   */
  children: PropTypes.node.isRequired,
  /**
   * Last delimiter.
   */
  lastDelimiter: PropTypes.string,
  /**
   * Multi delimiter.
   */
  multiDelimiter: PropTypes.string,
  /**
   * Pre text.
   */
  preText: PropTypes.string,
  /**
   * Single delimiter.
   */
  singleDelimiter: PropTypes.string,
  /**
   * Flag to display the menu name.
   */
  timestamp: PropTypes.string,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object,
};

const menuThemeMap = {
  default: defaultStyles,
};

export default withThemes(menuThemeMap)(Byline);
