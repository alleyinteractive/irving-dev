import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import * as defaultStyles from './themes/default';

/**
 * Menu item.
 *
 * Render a single menu item.
 *
 * @todo Replace <a> with a proper <Link> component.
 */
const MenuItem = (props) => {
  const {
    children,
    id,
    target,
    theme,
    title,
    url,
  } = props;

  const { Wrapper, Dropdown } = theme;

  return (
    <Wrapper key={id}>
      <li>
        <a href={url} target={target}>
          {title}
        </a>
        {children && (
          <Dropdown>
            {children}
          </Dropdown>
        )}
      </li>
    </Wrapper>
  );
};

MenuItem.defaultProps = {
  // attribute: '',
  // classes: [],
  // description: '',
  target: '',
  title: '',
  url: '',
};

MenuItem.propTypes = {
  // attribute: PropTypes.string,
  /**
   * Children of the component.
   */
  children: PropTypes.node.isRequired,
  /**
   * Classnames.
   */
  // classes: PropTypes.array,
  /**
   * Unique key.
   */
  id: PropTypes.number.isRequired,
  // parent_id: PropTypes.int,
  /**
   * Target attribute value.
   */
  target: PropTypes.string,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.string.isRequired,
  /**
   * Title.
   */
  title: PropTypes.string,
  /**
   * URL.
   */
  url: PropTypes.string,
};

const themeMap = {
  default: defaultStyles,
};

export default withThemes(themeMap)(MenuItem);
