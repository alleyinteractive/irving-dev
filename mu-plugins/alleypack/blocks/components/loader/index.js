/* global wp, React */

import PropTypes from 'prop-types';
import classNames from 'classnames';

const {
  i18n: {
    __,
  },
} = wp;

const Loader = (props) => {
  const {
    className,
    isLoading,
    text,
  } = props;

  if (! isLoading) {
    return null;
  }

  return (
    <div
      className={
        classNames(
          'loading__container',
          className,
        )
      }
      style={{ margin: '10px auto 0', textAlign: 'center' }}
    >
      <svg
        title={text}
        width="38"
        height="38"
        viewBox="0 0 38 38"
        xmlns="http://www.w3.org/2000/svg"
        stroke="#000"
      >
        <g fill="none" fillRule="evenodd">
          <g transform="translate(1 1)" strokeWidth="2">
            <circle strokeOpacity=".5" cx="18" cy="18" r="18" />
            <path d="M36 18c0-9.94-8.06-18-18-18">
              <animateTransform
                attributeName="transform"
                type="rotate"
                from="0 18 18"
                to="360 18 18"
                dur="1s"
                repeatCount="indefinite"
              />
            </path>
          </g>
        </g>
      </svg>
    </div>
  );
};

/**
 * Set initial props.
 * @type {object}
 */
Loader.defaultProps = {
  className: '',
  isLoading: true,
  text: __('Loading', 'alleypack'),
};

/**
  * Set PropTypes for this component.
  * @type {object}
  */
Loader.propTypes = {
  className: PropTypes.string,
  isLoading: PropTypes.bool,
  text: PropTypes.string,
};

export default Loader;
