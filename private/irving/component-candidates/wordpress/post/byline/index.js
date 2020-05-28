import React from 'react';
import { decode } from 'he';
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
    BylineWrapper,
    AuthorsWrapper,
    TimestampWrapper,
  } = theme;

  console.log(
    preText,
    timestamp,
    lastDelimiter,
    multiDelimiter,
    singleDelimiter
  );

  switch (children.length) {
    default:
    case 0:
      break;

    case 1:
      return (
        <BylineWrapper>
          <AuthorsWrapper>
            {preText && <span>{decode(preText)}</span>}
            {children}
          </AuthorsWrapper>
          <TimestampWrapper>{timestamp}</TimestampWrapper>
        </BylineWrapper>
      );

    case 2:
      return (
        <BylineWrapper>
          <AuthorsWrapper>
            {preText && <span>{decode(preText)}</span>}
            <span>{children[0]}{singleDelimiter}{children[0]}</span>
          </AuthorsWrapper>
          <TimestampWrapper>{timestamp}</TimestampWrapper>
        </BylineWrapper>
      );
  }

  return (
    <BylineWrapper>
      <AuthorsWrapper>
        {preText && <span>{decode(preText)}</span>}
        {children.map((child, index) => {
          // First through second to last author.
          if (index < (children.length - 2)) {
            return (
              <>
                {child}
                {decode(multiDelimiter)}
              </>
            );
          }

          // Second to last author.
          if (index < (children.length - 1)) {
            return (
              <>
                {child}
                {decode(lastDelimiter)}
              </>
            );
          }

          // Last author.
          return child;
        })}
      </AuthorsWrapper>
      <TimestampWrapper>{timestamp}</TimestampWrapper>
    </BylineWrapper>
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
