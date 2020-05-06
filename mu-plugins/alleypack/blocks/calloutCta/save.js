/* global React, wp */

import classNames from 'classnames';
import PropTypes from 'prop-types';

const {
  editor: {
    InnerBlocks,
    RichText,
  },
} = wp;

/**
 * A React component to render a callout.
 * @param {Object} props - Props for this component.
 * @returns {Object} - JSX for this component.
 */
export default function CalloutCtaBlock(props) {
  const {
    calloutBackgroundColor,
    calloutButtonText,
    calloutButtonUrl,
    calloutImageUrl,
    calloutImageAltText,
    contentSource,
    title,
  } = props;

  const showTitle = title && title.length && ! calloutImageUrl;
  const showContent = ! calloutImageUrl;

  return (
    <div
      data-callout-background-color={calloutBackgroundColor}
      className={classNames(
        'block__callout',
        `callout__background-color--${calloutBackgroundColor}`,
        (title && title.length) ? '' : 'callout__no-title',
        (calloutButtonText && calloutButtonText.length)
          ? '' : 'callout__no-cta-link',
        (contentSource && contentSource.length) ? 'callout__has-source' : ''
      )}
    >
      <div className="callout__container">
        <div className="callout__content">
          {
            !! calloutImageUrl && (
              <div className="callout__image">
                <img
                  className="callout__image-inner"
                  src={calloutImageUrl}
                  alt={calloutImageAltText}
                />
              </div>
            )
          }
          {
            !! showTitle && (
              <RichText.Content
                className="callout__title"
                tagName="h3"
                value={title}
              />
            )
          }
          {
            !! showContent && (
              <div className="callout__content--text-content">
                <InnerBlocks.Content />
              </div>
            )
          }
          {
            !! contentSource && (
              <RichText.Content
                className="callout__content-text-source"
                tagName="p"
                value={contentSource}
              />
            )
          }
        </div>
        {
          !! calloutButtonText && (
            <a
              href={calloutButtonUrl}
              className="callout__cta-button-link"
            >
              {calloutButtonText}
            </a>
          )
        }
      </div>
    </div>
  );
}

CalloutCtaBlock.defaultProps = {
  calloutBackgroundColor: '',
  calloutButtonText: '',
  calloutButtonUrl: '',
  calloutImageAltText: '',
  calloutImageUrl: '',
  contentSource: '',
  title: '',
};

CalloutCtaBlock.propTypes = {
  calloutBackgroundColor: PropTypes.string,
  calloutButtonText: PropTypes.string,
  calloutButtonUrl: PropTypes.string,
  calloutImageAltText: PropTypes.string,
  calloutImageUrl: PropTypes.string,
  contentSource: PropTypes.string,
  title: PropTypes.string,
};
