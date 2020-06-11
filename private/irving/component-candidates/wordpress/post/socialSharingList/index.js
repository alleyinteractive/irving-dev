import React from 'react';
import PropTypes from 'prop-types';

const SocialSharingList = (props) => {
  const {
    children,
    text,
  } = props;

  return (
    <div>
      <span>{text}</span>
      <ul>
        {children}
      </ul>
    </div>
  );
};

SocialSharingList.propTypes = {
  /**
   * Component children, usually Social Sharing Item components
   */
  children: PropTypes.arrayOf(
    PropTypes.element
  ).isRequired,
  text: PropTypes.string,
};

SocialSharingList.defaultProps = {
  text: '',
};


export default SocialSharingList;
