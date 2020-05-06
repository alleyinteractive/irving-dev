/* global wp, React */

import classNames from 'classnames';
import PropTypes from 'prop-types';

const {
  date: {
    format,
  },
  editor: {
    RichText,
  },
} = wp;

/**
 * A React component to render a sample block.
 */
class EditablePostBlock extends React.PureComponent {
  /**
   * Render Image.
   */
  getImage() {
    const {
      imageId: imageIdRaw = '',
      imageSize,
      imageUrl,
      url,
    } = this.props;

    const imageId = parseInt(imageIdRaw, 10);

    if (! imageUrl) {
      return null;
    }

    return (
      <div className="post-block--image">
        <a
          className="post-block--link"
          href={url}
        >
          <img
            src={imageUrl}
            alt=""
            data-id={imageId}
            data-size={imageSize}
          />
        </a>
      </div>
    );
  }

  getPostType() {
    const {
      postType,
      showPostType,
    } = this.props;

    // Bail if we don't need to show this element.
    if ('' === postType) {
      return null;
    }

    return (
      <div className="post-block--type" data-visible={showPostType}>
        {postType}
      </div>
    );
  }

  /**
   * Render title.
   */
  getTitle() {
    const {
      title,
      url,
    } = this.props;

    if (! title) {
      return null;
    }

    return (
      url ? (
        <h3 className="post-block--title">
          <RichText.Content
            className="post-block--link"
            format="string"
            multiline={false}
            href={url}
            tagName="a"
            value={title}
          />
        </h3>
      ) : (
        <h3 className="post-block--title">
          <RichText.Content
            format="string"
            multiline={false}
            href={url}
            tagName="div"
            value={title}
          />
        </h3>
      )
    );
  }

  /**
   * Render Excerpt.
   */
  getExcerpt() {
    const {
      excerpt,
    } = this.props;

    if (! excerpt) {
      return null;
    }

    return (
      <RichText.Content
        className="post-block--excerpt"
        format="string"
        multiline
        tagName="div"
        value={excerpt}
      />
    );
  }

  /**
   * Render date.
   */
  getDate() {
    const {
      publishDate,
      showPublished,
    } = this.props;

    // Bail if we don't need to show this element.
    if ('false' === showPublished || '' === publishDate) {
      return null;
    }

    return (
      <div
        className="post-block--published"
        data-visible="true"
        data-date={publishDate}
      >
        {
          '' !== publishDate && (
            format('F j, Y', publishDate)
          )
        }
      </div>
    );
  }

  /**
   * Render cta.
   */
  getCta() {
    const {
      ctaMessage,
      showCta,
      url,
    } = this.props;

    // Bail if we don't need to show this element.
    if ('false' === showCta) {
      return null;
    }

    return (
      <div className="post-block--cta" data-visible="true">
        <a
          className="post-block--cta-link"
          href={url}
        >
          <span>{ctaMessage}</span>
        </a>
      </div>
    );
  }

  /**
   * Render Authors.
   */
  getAuthors() {
    const {
      authors,
      showAuthors,
    } = this.props;

    return (
      <div className="post-block--authors" data-visible={showAuthors}>
        {authors}
      </div>
    );
  }

  /**
   * Renders this component.
   * @returns {object} - JSX for the component.
   */
  render() {
    const {
      postType,
      textAlignment,
    } = this.props;

    const postTypeSlug = postType.replace(/\s+/g, '-').toLowerCase();

    return (
      <div
        data-text-align={textAlignment}
        className={
          classNames(
            'block--type--post-block',
            postTypeSlug,
          )
        }
      >
        {this.getImage()}

        <div className="post-block--inner">
          <div className="post-block--meta">
            {[
              this.getPostType(),
              this.getDate(),
            ]}
          </div>
          {[
            this.getTitle(),
            this.getExcerpt(),
            this.getCta(),
            this.getAuthors(),
          ]}
        </div>
      </div>
    );
  }
}

EditablePostBlock.defaultProps = {
  authors: '',
  ctaMessage: '',
  excerpt: '',
  imageId: 0,
  imageSize: 'full',
  imageUrl: '',
  postType: '',
  publishDate: '',
  showAuthors: 'true',
  showCta: 'false',
  showPostType: 'true',
  showPublished: 'true',
  textAlignment: 'left',
  url: '',
};

EditablePostBlock.propTypes = {
  authors: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.arrayOf(
      PropTypes.shape(),
    ),
  ]),
  ctaMessage: PropTypes.string,
  excerpt: PropTypes.string,
  imageId: PropTypes.string,
  imageSize: PropTypes.string,
  imageUrl: PropTypes.string,
  postType: PropTypes.string,
  publishDate: PropTypes.string,
  showAuthors: PropTypes.string,
  showCta: PropTypes.string,
  showPostType: PropTypes.string,
  showPublished: PropTypes.string,
  textAlignment: PropTypes.string,
  title: PropTypes.string.isRequired,
  url: PropTypes.string,
};

export default EditablePostBlock;
