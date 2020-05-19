import React from 'react';
import PropTypes from 'prop-types';

const Body = (props) => {
  const {
    title,
    children,
  } = props;

  return (
    <main>
      <h2>{title}</h2>
      <div>{children}</div>
    </main>
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
  children: PropTypes.arrayOf(PropTypes.node).isRequired,
};

export default Body;
