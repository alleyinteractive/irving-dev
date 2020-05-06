/* global wp, React */

import classNames from 'classnames';
import PropTypes from 'prop-types';
import PostSelector from 'components/postSelector';
import Loader from 'components/loader';

// Import components.
import getPostAuthors from 'services/getPostAuthors';
import getPostThumbnail from 'services/getPostThumbnail';
import getImageThumbnail from 'services/media/getImageThumbnail'; // eslint-disable-line max-len
import Controls from './controls';

const {
  apiFetch,
  components: {
    DatePicker,
    IconButton,
    PanelBody,
    TextControl,
  },
  data: {
    withSelect,
  },
  date: {
    format,
  },
  editor: {
    MediaPlaceholder,
    MediaUploadCheck,
    PlainText,
    RichText,
    URLInput,
  },
  i18n: {
    __,
  },
  url: {
    addQueryArgs,
  },
} = wp;

/**
 * A React component to render the edit view of a Editable Post block.
 */
class EditablePostBlockEdit extends React.PureComponent {
  /**
   * Constructor. Binds function scope.
   * @param {object} props - Props for this component.
   */
  constructor(props) {
    super(props);

    this.state = {
      loading: false,
    };

    this.handleAddCard = this.handleAddCard.bind(this);
  }

  /**
   * Return the Image Editing fields.
   */
  getImageEdit() {
    const {
      attributes: {
        imageId: imageIdRaw = '',
        imageSize,
        imageUrl,
      } = {},
      setAttributes,
      media,
    } = this.props;

    const imageId = parseInt(imageIdRaw, 10);
    let imageSrc = '';

    if (imageUrl) {
      imageSrc = imageUrl;
    } else if (media && media.id) {
      imageSrc = getImageThumbnail(media, imageSize);
    }

    return [
      // Handle the images
      // display uploader if we don't have an image.
      (imageUrl || (media && media.id)) && (
        <div className="race--photo-wrapper--container">
          {(imageUrl || media) && [
            <IconButton
              icon="no-alt"
              label={__('Remove Image', 'alleypack')}
              type="submit"
              onClick={() => setAttributes({
                imageUrl: '',
                imageId: 0,
              })}
            />,
            <img src={imageSrc} alt="" />,
          ]}
        </div>
      ),
      (! imageUrl || ! imageId) && (
        <MediaUploadCheck>
          <MediaPlaceholder
            accept="image/*"
            allowedTypes={['image']}
            icon="format-image"
            onSelect={
              (mediaObject) => setAttributes({
                imageId: mediaObject.id,
                imageUrl: getImageThumbnail(mediaObject, imageSize),
              })
            }
          />
        </MediaUploadCheck>
      ),
    ];
  }

  /**
   * Render "Add Featured Image" placeholder, else...
   */
  getImageRender() {
    const {
      attributes: {
        imageId: imageIdRaw = '',
        imageUrl,
        url,
      } = {},
    } = this.props;

    const imageId = parseInt(imageIdRaw, 10);

    if ('' === imageUrl) {
      return null;
    }

    return (
      <div className="post-block--image">
        <a
          className="post-block--link"
          href={url}
        >
          <img src={imageUrl} alt="" data-id={imageId} />
        </a>
      </div>
    );
  }

  /**
   * Get Post Type Edit Render.
   */
  getPostTypeEdit() {
    const {
      attributes: {
        postType,
        showPostType,
      } = {},
      setAttributes,
    } = this.props;

    // Bail if we don't need to show this element.
    if ('false' === showPostType) {
      return null;
    }

    return (
      <PlainText
        className="post-block--type--input" /* eslint-disable-line max-len */
        onChange={(newPostType) => {
          setAttributes({
            postType: newPostType,
          });
        }}
        placeholder={__('Post Type', 'alleypack')}
        value={postType}
      />
    );
  }

  /**
   * Render Post Type.
   */
  getPostTypeRender() {
    const {
      attributes: {
        postType,
        showPostType,
      } = {},
    } = this.props;

    // Bail if we don't need to show this element.
    if ('false' === showPostType || ! postType) {
      return null;
    }

    return (
      <div className="post-block--type" data-visible={showPostType}>
        {postType}
      </div>
    );
  }

  /**
   * Return the title fields.
   */
  getTitleEdit() {
    const {
      attributes: {
        title,
      } = {},
      setAttributes,
    } = this.props;

    return (
      <RichText
        className="post-block--title"
        placeholder={__(
          'Add Post Title',
          'alleypack'
        )}
        muliline={false}
        onChange={(newTitle) => setAttributes({ title: newTitle })}
        tagName="h3"
        value={title}
      />
    );
  }

  /**
   * Render title.
   */
  getTitleRender() {
    const {
      attributes: {
        title,
        url,
      } = {},
    } = this.props;

    if (! title) {
      return null;
    }

    return (
      <h3 className="post-block--title">
        <RichText.Content
          className="post-block--link"
          multiline={false}
          href={url}
          tagName="a"
          value={title}
        />
      </h3>
    );
  }

  /**
   * Return excerpt edit fields.
   */
  getExcerptEdit() {
    const {
      attributes: {
        excerpt,
      } = {},
      setAttributes,
    } = this.props;

    return (
      // Adds editable excerpt field.
      <RichText
        className="post-block--excerpt-input"
        onChange={(newExcerpt) => setAttributes({ excerpt: newExcerpt })}
        tagName="div"
        placeholder={
          __('Add an Optional Excerpt', 'alleypack')
        }
        value={excerpt}
      />
    );
  }

  /**
   * Render Excerpt.
   */
  getExcerptRender() {
    const {
      attributes: {
        excerpt,
      } = {},
    } = this.props;

    if (! excerpt) {
      return null;
    }

    return (
      <RichText.Content
        className="post-block--excerpt"
        multiline
        tagName="div"
        value={excerpt}
      />
    );
  }

  /**
  * Get Date Edit Render.
  */
  getDateEdit() {
    const {
      attributes: {
        publishDate,
        showPublished,
      } = {},
      setAttributes,
    } = this.props;

    // Bail if we don't need to show this element.
    if ('false' === showPublished) {
      return null;
    }

    return (
      <PanelBody
        initialOpen={false}
        title={__(
          'Override Displayed Date',
          'alleypack'
        )}
      >
        <DatePicker
          currentDate={publishDate}
          onChange={(value) => {
            setAttributes({
              publishDate: value,
            });
          }}
        />
      </PanelBody>
    );
  }

  /**
    * Render date.
    */
  getDateRender() {
    const {
      attributes: {
        publishDate,
        showPublished,
      } = {},
    } = this.props;

    // Bail if we don't need to show this element.
    if ('false' === showPublished || '' === publishDate) {
      return null;
    }

    return (
      <div
        className="post-block--published"
        data-date={publishDate}
      >
        {format('F j, Y', publishDate)}
      </div>
    );
  }

  /**
   * Get CTA edit render.
   */
  getCtaEdit() {
    const {
      attributes: {
        ctaMessage,
        showCta,
      } = {},
      setAttributes,
    } = this.props;

    // Bail if we don't need to show this element.
    if ('false' === showCta) {
      return null;
    }

    return (
      <TextControl
        label="Call To Action Text:"
        value={ctaMessage}
        onChange={(value) => {
          setAttributes({
            ctaMessage: value,
          });
        }}
      />
    );
  }

  /**
   * Render CTA.
   */
  getCtaRender() {
    const {
      attributes: {
        ctaMessage,
        showCta,
        url,
      } = {},
    } = this.props;

    // Bail if we don't need to show this element.
    if ('false' === showCta || ! ctaMessage) {
      return null;
    }

    return (
      <div className="post-block--cta">
        <a
          clasName="post-block--cta-link"
          href={url}
        >
          <span>{ctaMessage}</span>
        </a>
      </div>
    );
  }

  /**
   * Fetch RSS Feed items from endpoint.
   * @param {object} post single post entry.
   */
  handleAddCard(post) {
    const {
      id,
      subtype,
    } = post;

    this.setState({
      loading: true,
    });

    // `/v2/posts` vs. '/v2/post` - one works, one doesn't.
    const actualSubtype = 'post' === subtype ? 'posts' : subtype;
    const path = addQueryArgs(`/wp/v2/${actualSubtype}/${id}`, {
      _embed: null,
    });

    apiFetch({ path })
      .then((postObject) => {
        const {
          attributes: {
            imageSize,
          } = {},
          setAttributes,
        } = this.props;
        const {
          date,
          excerpt,
          featured_media, /* eslint-disable-line camelcase */
          link,
          title,
          type,
        } = postObject;

        // Set attributes.
        setAttributes({
          authors: getPostAuthors(postObject),
          excerpt: excerpt ? excerpt.rendered : '',
          imageId: featured_media,
          imageUrl: getPostThumbnail(postObject, imageSize),
          postType: type ? type.replace(/-/g, ' ') : '',
          publishDate: date,
          title: title ? title.rendered : '',
          url: link,
        });

        this.setState({
          loading: false,
        });
      });
  }

  /**
   * Render components when block isSelected.
   */
  renderSelectedContent() {
    const {
      attributes: {
        url,
      } = {},
      setAttributes,
    } = this.props;

    const {
      loading,
    } = this.state;

    return [
      <p className="field-notice">
        {__('Select a post or manually fill in the fields below', 'alleypack') /* eslint-disable-line max-len */ }
      </p>,
      // Add option to select post.
      <PostSelector
        onChange={this.handleAddCard}
        postTypes={[
          'post',
          'page',
        ]}
      />,

      // Show loader.
      loading && (
        <Loader />
      ),

      // Render selected edit functions.
      this.getImageEdit(),
      this.getPostTypeEdit(),
      this.getTitleEdit(),
      this.getExcerptEdit(),
      this.getCtaEdit(),

      // Add ability to overwrite post url.
      <form
        className="components-base-control post-block--form" /* eslint-disable-line max-len */
        onSubmit={(event) => event.preventDefault()}
      >
        <div className="components-base-control__field">
          <div className="components-base-control__label">
            {
              __(
                'Post URL overide.',
                'alleypack'
              )
            }
          </div>
          <URLInput
            className="post-block--link-input components-text-control__input"
            onChange={
              (newUrl) => setAttributes({
                url: newUrl,
              })
            }
            placeholder={__('Add a URL', 'alleypack')}
            autoFocus={false}
            value={url}
          />
        </div>
      </form>,
      this.getDateEdit(),
    ];
  }

  /**
   * Render components when false === isSelected.
   */
  renderDeselectedContent() {
    return [
      // Render deselected output/functions.
      this.getImageRender(),

      <div className="post-block--inner">
        <div className="post-block--meta">
          {[
            this.getPostTypeRender(),
            this.getDateRender(),
          ]}
        </div>
        {[
          this.getTitleRender(),
          this.getExcerptRender(),
          this.getCtaRender(),
        ]}
      </div>,
    ];
  }

  /**
   * Renders this component.
   * @returns {object} - JSX for this component.
   */
  render() {
    const {
      attributes,
      attributes: {
        postType,
        textAlignment,
        title,
      } = {},
      isSelected,
      setAttributes,
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
        <Controls
          attributes={attributes}
          isSelected={isSelected}
          setAttributes={setAttributes}
        />
        {
          // if we're in the select view.
          isSelected
          || '' === title
            ? this.renderSelectedContent()
            : this.renderDeselectedContent()
        }
      </div>
    );
  }
}

EditablePostBlockEdit.defaultProps = {
  attributes: {
    authors: '',
    ctaMessage: '',
    excerpt: '',
    imageId: 0,
    imageSize: 'thumbnail',
    imageUrl: '',
    postType: '',
    publishDate: '',
    showAuthors: 'true',
    showCta: 'false',
    showPostType: 'true',
    showPublished: 'true',
    textAlignment: 'left',
    url: '',
  },
  isSelected: false,
};

EditablePostBlockEdit.propTypes = {
  attributes: PropTypes.shape({
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
    showContentType: PropTypes.string,
    showCta: PropTypes.string,
    showPostType: PropTypes.string,
    showPublished: PropTypes.string,
    textAlignment: PropTypes.string,
    title: PropTypes.string.isRequired,
    url: PropTypes.string,
  }),
  isSelected: PropTypes.bool,
  setAttributes: PropTypes.func.isRequired,
  media: PropTypes.shape({
    id: PropTypes.number,
  }).isRequired,
};

/**
 * Inject media into props to render images.
 */
export default withSelect((select, ownProps) => {
  const {
    attributes: {
      imageId,
    } = {},
  } = ownProps;

  // Get Media function.
  const {
    getMedia,
  } = select('core');

  // Return media object if we have an image id selected.
  return undefined !== imageId && 0 !== imageId
    ? {
      media: imageId ? getMedia(imageId) || {} : {},
    } : {};
})(EditablePostBlockEdit);
