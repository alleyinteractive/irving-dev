import React from 'react';
import PropTypes from 'prop-types';

const GutenbergContent = (props) => {
  const { children } = props;

  return (
    <div>
      {children}
    </div>
  );
};

GutenbergContent.propTypes = {
  children: PropTypes.arrayOf(PropTypes.element).isRequired,
};

export default GutenbergContent;
