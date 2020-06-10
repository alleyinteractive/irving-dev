import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import Link from '../../../common/link';
import * as defaultStyles from './themes/default';

const SocialSharingItem = (props) => {
  const {
    theme,
    url,
  } = props;

  const {
    SocialSharingItemWrapper,
  } = theme;

  return (
    <SocialSharingItemWrapper>
      <Link
        to={url}
      >
        <span>how the heck do we get the icon?</span>
      </Link>
    </SocialSharingItemWrapper>
  );
};

SocialSharingItem.defaultProps = {
  theme: defaultStyles,
  url: '',
};


SocialSharingItem.propTypes = {
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object,
  /**
   * Where should this icon take the user?
   */
  url: PropTypes.string,
};

const socialSharingItemThemeMap = {
  default: defaultStyles,
};

export default withThemes(socialSharingItemThemeMap)(SocialSharingItem);
