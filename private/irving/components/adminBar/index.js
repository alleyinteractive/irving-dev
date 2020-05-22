import React from 'react';
import PropTypes from 'prop-types';
import withThemes from '@irvingjs/styled/components/withThemes';
import * as defaultStyles from './themes/default';

const AdminBar = (props) => {
  const {
    content,
    iframeSrc,
    theme,
  } = props;
  const { Iframe } = theme;

  console.log('content', content);
  console.log('iframeSrc', iframeSrc);

  if (iframeSrc) {
    return (
      <Iframe
        title="Admin Bar Iframe"
        src={iframeSrc}
      />
    );
  }

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

AdminBar.defaultProps = {
  theme: 'default',
};

AdminBar.propTypes = {
  content: PropTypes.string.isRequired,
  iframeSrc: PropTypes.string.isRequired,
  /**
   * Theme (styles) to apply to the component.
   */
  theme: PropTypes.object.isRequired,
};

const themeMap = {
  default: defaultStyles,
};

export default withThemes(themeMap)(AdminBar);
