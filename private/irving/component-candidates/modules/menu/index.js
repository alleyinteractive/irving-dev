import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import * as defaultStyles from './themes/default';
import * as defaultVerticalStyles from './themes/defaultVertical';

/**
 * Output links as menu items.
 */
const Menu = (props) => {
  const {
    children,
    displayName,
    menuName,
    location,
    theme,
  } = props;

  const {
    Wrapper,
    NameWrapper,
    Inner,
    ItemWrapper,
  } = theme;

  return (
    <Wrapper data-location={location}>
      {(displayName && menuName) && (
        <NameWrapper>
          {menuName}
        </NameWrapper>
      )}
      <Inner>
        {children.map((child) => (
          <ItemWrapper>
            {child}
          </ItemWrapper>
        ))}
      </Inner>
    </Wrapper>
  );
};

Menu.defaultProps = {
  displayName: false,
  location: '',
  menuName: '',
};

Menu.propTypes = {
  /**
   * Children of the component.
   */
  children: PropTypes.node.isRequired,
  /**
   * Flag to display the menu name.
   */
  displayName: PropTypes.bool,
  /**
   * Menu location.
   */
  location: PropTypes.string,
  /**
   * Menu name.
   */
  menuName: PropTypes.string,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object.isRequired,
};

const themeMap = {
  default: defaultStyles,
  defaultVertical: defaultVerticalStyles,
};

export default withThemes(themeMap)(Menu);
