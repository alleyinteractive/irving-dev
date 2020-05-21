import React from 'react';
import PropTypes from 'prop-types';

const AdminBar = (props) => {
  const {
    content,
    testContent,
  } = props;

  console.log('content', content);
  console.log('testContent', testContent);

  return (
    <div
      className="testing"
      dangerouslySetInnerHTML={{ __html: testContent }} // eslint-disable-line react/no-danger
    />
  );
};

AdminBar.propTypes = {
  content: PropTypes.string.isRequired,
  testContent: PropTypes.string.isRequired,
};

export default AdminBar;
