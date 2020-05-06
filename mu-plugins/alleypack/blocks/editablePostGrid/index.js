/* global wp, React */

import attributes from './attributes';
import PostGridEdit from './edit';
import PostGrid from './save';

const {
  blocks: {
    registerBlockType,
  },
  i18n: {
    __,
  },
} = wp;

/**
 * Register post grid block.
 */
registerBlockType(
  'alleypack/block-post-grid',
  {
    attributes,
    title: __('Post Grid', 'alleypack'),
    description: __(
      'A post selection grid.',
      'alleypack',
    ),
    icon: 'schedule',
    category: 'widgets',
    keywords: [
      __('grid', 'alleypack'),
      __('curated', 'alleypack'),
      __('posts', 'alleypack'),
    ],
    supports: {
      html: false,
    },
    edit: PostGridEdit,
    // eslint-disable-next-line react/prop-types
    save: ({ attributes: blockAttributes }) => (
      <PostGrid
        backgroundColor={blockAttributes.backgroundColor}
        excerpt={blockAttributes.excerpt}
        heading={blockAttributes.heading}
        theme={blockAttributes.theme}
      />
    ),
  },
);
