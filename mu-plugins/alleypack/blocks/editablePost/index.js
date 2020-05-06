/* global wp, React */

import attributes from './attributes';
import EditablePostBlockEdit from './edit';
import EditablePostBlock from './save';

const {
  blocks: {
    registerBlockType,
  },
  i18n: {
    __,
  },
} = wp;

/**
 * Register editable post block.
 */
registerBlockType(
  'alleypack/block-post-block',
  {
    attributes,
    title: __('Editable Post Block', 'alleypack'),
    description: __(
      'Add a single editable post block.',
      'alleypack',
    ),
    icon: 'editor-insertmore',
    category: 'widgets',
    keywords: [
      __('1up', 'alleypack'),
      __('post', 'alleypack'),
      __('edit', 'alleypack'),
    ],
    parent: [
      'alleypack/block-post-grid',
    ],
    supports: {
      html: false,
    },
    edit: EditablePostBlockEdit,
    // eslint-disable-next-line react/prop-types
    save: ({ attributes: blockAttributes }) => (
      <EditablePostBlock
        authors={blockAttributes.authors}
        ctaMessage={blockAttributes.ctaMessage}
        excerpt={blockAttributes.excerpt}
        imageId={blockAttributes.imageId}
        imageSize={blockAttributes.imageSize}
        imageUrl={blockAttributes.imageUrl}
        postType={blockAttributes.postType}
        publishDate={blockAttributes.publishDate}
        showAuthors={blockAttributes.showAuthors}
        showCta={blockAttributes.showCta}
        showPostType={blockAttributes.showPostType}
        showPublished={blockAttributes.showPublished}
        textAlignment={blockAttributes.textAlignment}
        title={blockAttributes.title}
        url={blockAttributes.url}
      />
    ),
  },
);
