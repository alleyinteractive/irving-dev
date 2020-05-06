/* global wp, React */

import classNames from 'classnames';
import PropTypes from 'prop-types';

const {
  editor: {
    InnerBlocks,
    RichText,
  },
} = wp;

/**
 * A React component to render the save view of a Post Grid block.
 */
class PostGrid extends React.PureComponent {
  /**
   * Renders this component.
   * @returns {object} - JSX for this component.
   */
  render() {
    const {
      backgroundColor,
      excerpt,
      heading,
      theme,
    } = this.props;

    return (
      <div
        data-background-color={backgroundColor}
        data-theme={theme}
        className={classNames(
          'block--type--post-grid',
          `post-grid--background-color--${backgroundColor}`,
          theme,
        )}
      >
        {(heading || excerpt) && (
          <header>
            {heading && (
              <h3 className="post-grid--heading block--heading-h3">
                {heading}
              </h3>
            )}
            {excerpt && (
              <RichText.Content
                tagName="div"
                className="block--excerpt post-grid--excerpt"
                value={excerpt}
              />
            )}
          </header>
        )}
        <div className="block--inner-block-container">
          <InnerBlocks.Content />
        </div>
      </div>
    );
  }
}

PostGrid.defaultProps = {
  backgroundColor: '',
  excerpt: '',
  heading: '',
  theme: '',
};

PostGrid.propTypes = {
  backgroundColor: PropTypes.string,
  excerpt: PropTypes.string,
  heading: PropTypes.string,
  theme: PropTypes.string,
};

export default PostGrid;
