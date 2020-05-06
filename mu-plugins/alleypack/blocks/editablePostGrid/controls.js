/* global wp, React */

import PropTypes from 'prop-types';
import customThemes from '../config/themes';
import customColors from '../config/colors';

const {
  components: {
    BaseControl,
    PanelBody,
    SelectControl,
  },
  editor: {
    ColorPalette,
    getColorObjectByAttributeValues,
    getColorObjectByColorValue,
    InspectorControls,
  },
  i18n: {
    __,
  },
} = wp;

/**
 * A React component to render the edit view of the Inspector Controls.
 */
const Controls = (props) => {
  const defaultBackground = customColors[0];
  const {
    attributes: {
      backgroundColor = defaultBackground.slug,
      theme,
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
        initialOpen
        title={__('Configure Post Grid', 'alleypack')}
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
                backgroundColor: colorObject.slug,
              });
            }}
            disableCustomColors
            value={getColorObjectByAttributeValues(
              customColors,
              backgroundColor,
            ).color}
          />
        </BaseControl>
        <SelectControl
          label={__('Select Theme', 'alleypack')}
          onChange={(newValue) => {
            setAttributes({
              theme: newValue,
            });
          }}
          options={customThemes}
          value={theme}
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
    backgroundColor: '',
    heading: '',
    excerpt: '',
    theme: '',
  },
  isSelected: false,
};

/**
 * Set PropTypes for this component.
 * @type {object}
 */
Controls.propTypes = {
  attributes: PropTypes.shape({
    backgroundColor: PropTypes.string,
    heading: PropTypes.string,
    excerpt: PropTypes.string,
    theme: PropTypes.string,
  }),
  isSelected: PropTypes.bool,
  setAttributes: PropTypes.func.isRequired,
};

export default Controls;
