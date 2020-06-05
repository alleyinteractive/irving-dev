import React from 'react';
import PropTypes from 'prop-types';
import { withStyles } from 'critical-style-loader/lib';
import styles from './test.css';

const TestComponent = (props) => {
  const {
    children,
  } = props;

  return (
    <div className={styles.wrapper}>{children}</div>
  );
};

TestComponent.propTypes = {
  children: PropTypes.arrayOf(PropTypes.element).isRequired,
};

export default withStyles(styles)(TestComponent);
