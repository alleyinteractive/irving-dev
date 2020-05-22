import React from 'react';
import PropTypes from 'prop-types';

const AdminBar = (props) => {
  const {
    content,
  } = props;

  console.log('content', content);

  /* eslint-disable */
  return (
    <div>
      <link rel='stylesheet' id='admin-bar-css' href='https://irving-dev.alley.test/wp-includes/css/admin-bar.min.css?ver=5.4.1' type='text/css' media='all' />
      <div
        className="admin-bar-testing"
        dangerouslySetInnerHTML={{ __html: content }} // eslint-disable-line react/no-danger
      />
    </div>
  );
};

AdminBar.propTypes = {
  content: PropTypes.string.isRequired,
};

export default AdminBar;
