import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import {
  Email,
  Facebook,
  LinkedIn,
  Pinterest,
  Reddit,
  Twitter,
  WhatsApp,
} from '@material-ui/icons';
import Link from '../../../common/link';
import * as defaultStyles from './themes/default';

const socialIconMap = {
  email: Email,
  facebook: Facebook,
  linkedin: LinkedIn,
  pinterest: Pinterest,
  reddit: Reddit,
  twitter: Twitter,
  whatsapp: WhatsApp,
};

const SocialSharingItem = (props) => {
  const {
    service,
    theme,
    url,
  } = props;
  const IconComponent = socialIconMap[service];

  const {
    IconWrapper,
    SocialSharingItemWrapper,
  } = theme;

  return (
    <SocialSharingItemWrapper>
      <Link
        href={url}
      >
        <IconWrapper>
          <IconComponent />
        </IconWrapper>
      </Link>
    </SocialSharingItemWrapper>
  );
};

SocialSharingItem.defaultProps = {
  url: '',
};


SocialSharingItem.propTypes = {
  /**
   * Service for the item.
   */
  service: PropTypes.string.isRequired,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object.isRequired,
  /**
   * Where should this icon take the user?
   */
  url: PropTypes.string,
};

const socialSharingItemThemeMap = {
  default: defaultStyles,
};

export default withThemes(socialSharingItemThemeMap)(SocialSharingItem);
