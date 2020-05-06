/* global wp, React */

import PropTypes from 'prop-types';

const {
  components: {
    BaseControl,
    CheckboxControl,
    PanelBody,
  },
  editor: {
    AlignmentToolbar,
    InspectorControls,
  },
  i18n: {
    __,
  },
} = wp;

const Controls = (props) => {
  const {
    attributes: {
      showAuthors,
      showCta,
      showPostType,
      showPublished,
      textAlignment,
    } = {},
    isSelected,
    setAttributes,
  } = props;

  // Bail if we're not selected.
  if (! isSelected) {
    return null;
  }

  return (
    <InspectorControls key="inspector">
      <PanelBody
        title={
          __(
            'Post Block Settings',
            'alleypack'
          )
        }
      >
        <BaseControl
          label={__(
            'Set Block Text Alignment',
            'alleypack'
          )}
        >
          <AlignmentToolbar
            value={textAlignment}
            onChange={(nextAlign) => {
              setAttributes({
                textAlignment: nextAlign,
              });
            }}
          />
        </BaseControl>
        <CheckboxControl
          label={__(
            'Show Authors',
            'alleypack'
          )}
          onChange={(value) => {
            setAttributes({
              showAuthors: value ? 'true' : 'false',
            });
          }}
          checked={'true' === showAuthors}
        />
        <CheckboxControl
          label={__(
            'Show Publish Date',
            'alleypack'
          )}
          onChange={(value) => {
            setAttributes({
              showPublished: value ? 'true' : 'false',
            });
          }}
          checked={'true' === showPublished}
        />
        <CheckboxControl
          label={__(
            'Show Post Type',
            'alleypack'
          )}
          onChange={(value) => {
            setAttributes({
              showPostType: value ? 'true' : 'false',
            });
          }}
          checked={'true' === showPostType}
        />
        <CheckboxControl
          label={__(
            'Show Call To Action',
            'alleypack'
          )}
          onChange={(value) => {
            setAttributes({
              showCta: value ? 'true' : 'false',
            });
          }}
          checked={'true' === showCta}
        />
      </PanelBody>
    </InspectorControls>
  );
};

/**
 * Set initial props.
 * @type {object}
 */
Controls.defaultProps = {
  attributes: {
    ctaMessage: '',
    ctaUrl: '',
    excerpt: '',
    eybrowUrl: '',
    imageId: 0,
    imageUrl: '',
    postType: '',
    publishDate: '',
    showCta: 'false',
    showAuthors: 'true',
    showPostType: 'true',
    showPublished: 'true',
    textAlignment: 'left',
    url: '',
  },
  isSelected: false,
};

/**
 * Set PropTypes for this component.
 * @type {object}
 */
Controls.propTypes = {
  attributes: PropTypes.shape({
    ctaMessage: PropTypes.string,
    ctaUrl: PropTypes.string,
    excerpt: PropTypes.string,
    eybrowUrl: PropTypes.string,
    imageId: PropTypes.number,
    imageUrl: PropTypes.string,
    postType: PropTypes.string,
    publishDate: PropTypes.string,
    showCta: PropTypes.string,
    showAuthors: PropTypes.string,
    showPostType: PropTypes.string,
    showPublished: PropTypes.string,
    textAlignment: PropTypes.string,
    url: PropTypes.string,
  }),
  isSelected: PropTypes.bool,
  setAttributes: PropTypes.func.isRequired,
};

export default Controls;
