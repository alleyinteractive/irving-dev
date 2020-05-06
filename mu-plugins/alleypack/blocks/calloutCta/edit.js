/* global wp, React */

import classNames from 'classnames';
import PropTypes from 'prop-types';

const customColors = [
  {
    className: 'module--style-accent-white',
    color: '#FFFFFF',
    name: 'White',
    slug: 'white',
  },
  {
    className: 'module--style-accent-black',
    color: '#101010',
    name: 'Black',
    slug: 'black',
  },
  {
    className: 'module--style-accent-tan',
    color: '#F5F4F2',
    name: 'Tan',
    slug: 'tan',
  },
];

const {
  components: {
    BaseControl,
    PanelBody,
    TextControl,
    Button,
  },
  editor: {
    ColorPalette,
    getColorObjectByColorValue,
    InnerBlocks,
    InspectorControls,
    RichText,
    MediaPlaceholder,
  },
  i18n: {
    __,
  },
} = wp;

/**
 * A React component to render the edit view of the Callout widget.
 * @param {Object} props - Props for the component.
 * @returns {Object} - JSX for the edit view of the Callout widget.
 */
export default function CalloutCtaEdit(props) {
  // set default color for when value is cleared in palette.
  const defaultBackground = customColors[0];
  const {
    attributes,
    setAttributes,
  } = props;
  const {
    calloutBackgroundColor = defaultBackground.slug,
    calloutImageUrl,
    calloutButtonText,
    calloutButtonUrl,
    calloutImageAltText,
    contentSource,
    title,
  } = attributes;

  const template = [
    [
      'core/paragraph',
      {
        placeholder: __('Callout content goes here.', 'alleypack'), // eslint-disable-line max-len
        className: 'block__content--text-content',
      },
    ],
  ];

  return (
    <div
      data-callout-background-color={calloutBackgroundColor}
      className={classNames(
        'block__callout',
        `callout__background-color--${calloutBackgroundColor}`,
      )}
    >
      <div className="callout__container">
        <div className="callout__content">
          {
            ! calloutImageUrl ? (
              <MediaPlaceholder
                accept="image/*"
                allowedTypes={['image']}
                icon="format-image"
                labels={{
                  title: __('Select Callout Image', 'alleypack'),
                  instructions: __(
                    'Selecting an image will hide text',
                    'alleypack'
                  ),
                }}
                onSelect={({ url, sizes, alt }) => {
                  setAttributes({
                    calloutImageUrl: sizes ? sizes.full.url : url,
                    calloutImageAltText: alt,
                  });
                }}
              />
            ) : (
              <div className="callout__image">
                <img
                  className="callout__image-inner"
                  src={calloutImageUrl}
                  alt={calloutImageAltText}
                />
                <Button
                  isDefault
                  onClick={() => {
                    setAttributes({
                      calloutImageUrl: '',
                      calloutImageAltText: '',
                    });
                  }}
                >
                  {__('Remove Image', 'alleypack')}
                </Button>
              </div>
            )
          }
          {
            ! calloutImageUrl && (
              <div>
                <RichText
                  className="callout__title"
                  keepPlaceholderOnFocus
                  onChange={(newTitle) => {
                    setAttributes({
                      title: newTitle,
                    });
                  }}
                  placeholder={__(
                    'Optional Title For Callout',
                    'alleypack'
                  )}
                  tagName="h3"
                  value={title}
                />
                <div className="callout__content--text-content">
                  <InnerBlocks
                    allowedBlocks={
                      [
                        'core/paragraph',
                        'core/list',
                      ]
                    }
                    templateLock={false}
                    template={template}
                  />
                </div>
              </div>
            )
          }
          <RichText
            className="callout__content-text-source"
            keepPlaceholderOnFocus
            onChange={(newSource) => {
              setAttributes({
                contentSource: newSource,
              });
            }}
            placeholder={__(
              'Optional Source',
              'alleypack'
            )}
            tagName="p"
            value={contentSource}
          />
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
      <InspectorControls key="inspector">
        <PanelBody
          initialOpen
          title={__('Configure Callout', 'alleypack')}
        >
          <BaseControl
            label={__('Background Color', 'alleypack')}
          >
            <ColorPalette
              colors={customColors}
              onChange={(value = defaultBackground.color) => {
                const colorObject = getColorObjectByColorValue(
                  customColors,
                  value,
                );
                setAttributes({
                  calloutBackgroundColor: colorObject.slug,
                });
              }}
              disableCustomColors
              value={calloutBackgroundColor}
            />
          </BaseControl>
          <TextControl
            label={__('CTA Button Text', 'alleypack')}
            value={calloutButtonText}
            onChange={(newButtonText) => {
              setAttributes({
                calloutButtonText: newButtonText,
              });
            }}
          />
          <TextControl
            label={__('CTA Button URL', 'alleypack')}
            value={calloutButtonUrl}
            onChange={(newButtonUrl) => {
              setAttributes({
                calloutButtonUrl: newButtonUrl,
              });
            }}
          />
        </PanelBody>
      </InspectorControls>
    </div>
  );
}

CalloutCtaEdit.defaultProps = {
  attributes: {
    calloutBackgroundColor: '',
    calloutImageUrl: '',
    calloutImageAltText: '',
    calloutButtonText: '',
    calloutButtonUrl: '',
    contentSource: '',
    title: '',
  },
};

CalloutCtaEdit.propTypes = {
  attributes: PropTypes.shape({
    calloutBackgroundColor: PropTypes.string,
    calloutImageUrl: PropTypes.string,
    calloutImageAltText: PropTypes.string,
    calloutButtonText: PropTypes.string,
    calloutButtonUrl: PropTypes.string,
    contentSource: PropTypes.string,
    title: PropTypes.string,
  }),
  setAttributes: PropTypes.func.isRequired,
};
