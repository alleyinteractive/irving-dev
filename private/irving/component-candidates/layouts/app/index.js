import React from 'react';
import { Helmet } from 'react-helmet';
import PropTypes from 'prop-types';
import CssReset from 'styles/reset';
import {
  AudioElement,
  PlayPauseButton,
} from '@irvingjs/audio-player';

/**
 * Top-level app component.
 */
const App = (props) => {
  const {
    IrvingApp,
  } = props;

  return (
    <>
      <Helmet />
      <CssReset />
      <IrvingApp />
      <AudioElement />
      <PlayPauseButton
        src="https://file-examples.com/wp-content/uploads/2017/11/file_example_MP3_700KB.mp3"
      />
    </>
  );
};

App.propTypes = {
  IrvingApp: PropTypes.func.isRequired,
};

export default App;
