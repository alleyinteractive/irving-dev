import React from 'react';
import PropTypes from 'prop-types';

const Text = (props) => {
  const {
    content,
  } = props;

  return (
    <>
      {content}
    </>
  );
};

Text.defaultProps = {
  content: '',
};

Text.propTypes = {
  /**
   * Text string.
   */
  content: PropTypes.string,
};

export default Text;
