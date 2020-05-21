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
    attributeTitle,
    classes,
    children,
    id,
    target,
    theme,
    title,
    url,
  } = props;

  const { Wrapper, Dropdown } = theme;

  return (
    <Wrapper key={id} classNames={classes}>
      <li>
        <a href={url} target={target} title={attributeTitle}>
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
  attributeTitle: '',
  classes: [],
  target: '',
  title: '',
  url: '',
};

MenuItem.propTypes = {
  /**
   * Value of the title attribute.
   */
  attributeTitle: PropTypes.string,
  /**
   * Children of the component.
   */
  children: PropTypes.node.isRequired,
  /**
   * Classnames.
   */
  classes: PropTypes.array,
  /**
   * Unique key.
   */
  id: PropTypes.number.isRequired,
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
