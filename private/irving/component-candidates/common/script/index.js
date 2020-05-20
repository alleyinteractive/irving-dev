import React from 'react';
import PropTypes from 'prop-types';
import { Helmet } from 'react-helmet';

const Script = (props) => {
  const { content, type } = props;

  return (
    <Helmet>
      <script type={type}>{content}</script>
    </Helmet>
  );
};

Script.defaultProps = {
  content: '',
  type: 'application/ld+json',
};

Script.propTypes = {
  /**
   * Script content.
   */
  content: PropTypes.string,
  /**
   * Script type.
   */
  type: PropTypes.string,
};

export default Script;
