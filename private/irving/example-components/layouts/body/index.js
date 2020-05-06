import React from 'react';
import PropTypes from 'prop-types';

const Body = (props) => {
  const {
    title,
  } = props;

  return (
    <main>{title}</main>
  );
};

Body.propTypes = {
  /**
   * Title.
   */
  title: PropTypes.string,
};

Body.defaultProps = {
  title: 'Default Title',
};

export default Body;
