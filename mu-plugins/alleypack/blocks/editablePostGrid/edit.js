/* global wp, React */

import classNames from 'classnames';
import PropTypes from 'prop-types';
import customColors from '../config/colors';
import Controls from './controls';

const {
  data: {
    select,
    withDispatch,
  },
  editor: {
    InnerBlocks,
    RichText,
  },
  i18n: {
    __,
  },
} = wp;

/**
 * A React component to render the edit view of a Post Grid block.
 */
class PostGridEdit extends React.PureComponent {
  /**
   * On initial mount, set innerBlock image size.
   */
  componentDidMount() {
    const {
      updateChildBlockImageSize,
    } = this.props;

    // Set thumbnail size for children.
    updateChildBlockImageSize('medium');
  }

  /**
   * Renders this component.
   * @returns {object} - JSX for this component.
   */
  render() {
    // Get default background as first color.
    const defaultBackground = customColors[0];

    const {
      attributes,
      attributes: {
        backgroundColor = defaultBackground.slug,
        heading,
        excerpt,
        theme,
      } = {},
      isSelected,
      setAttributes,
    } = this.props;

    return (
      <div
        data-background-color={backgroundColor}
        data-theme={theme}
        className={classNames(
          'block--type--post-grid',
          `block--style-accent-${backgroundColor}`,
          theme,
        )}
      >
        <RichText
          className="post-grid--heading block--heading-h3"
          keepPlaceholderOnFocus={false}
          onChange={(newValue) => {
            setAttributes({
              heading: newValue,
            });
          }}
          tagName="h3"
          value={heading}
          format="string"
          placeholder={__('Add optional title')}
        />
        <RichText
          className="block--excerpt post-grid--excerpt--input"
          keepPlaceholderOnFocus={false}
          onChange={(newValue) => {
            setAttributes({
              excerpt: newValue,
            });
          }}
          tagName="div"
          value={excerpt}
          format="string"
          placeholder={__('Add optional description')}
        />
        <div className="block--inner-block-container">
          <InnerBlocks
            allowedBlocks={['alleypack/block-post-block']}
          />
        </div>
        <Controls
          attributes={attributes}
          isSelected={isSelected}
          setAttributes={setAttributes}
        />
      </div>
    );
  }
}

PostGridEdit.defaultProps = {
  attributes: {
    backgroundColor: '',
    heading: '',
    excerpt: '',
    theme: '',
  },
};

PostGridEdit.propTypes = {
  attributes: PropTypes.shape({
    backgroundColor: PropTypes.string,
    heading: PropTypes.string,
    excerpt: PropTypes.string,
    theme: PropTypes.string,
  }),
  isSelected: PropTypes.bool.isRequired,
  setAttributes: PropTypes.func.isRequired,
  updateChildBlockImageSize: PropTypes.func.isRequired,
};

/**
 * Inject children (InnerBlocks) with thumbnail size for use.
 */
export default withDispatch((dispatch, { clientId }) => { // eslint-disable-line arrow-body-style
  return {
    /**
     * Update all child innerBlocks based on dropdown value.
     *
     * @param  {string} size block image size (default full).
     */
    updateChildBlockImageSize(size) {
      // Get Post Block.
      const innerBlocks = select('core/editor')
        .getBlocks(clientId);

      innerBlocks.forEach((block) => {
        // Update the correct block's attributes
        // block.attributes.blockImageSize = size;
        dispatch('core/editor').updateBlockAttributes(block.clientId, {
          imageSize: size,
        });
      });
    },
  };
})(PostGridEdit);
