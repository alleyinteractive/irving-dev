/* global wp, React */
import attributes from './attributes';
import CalloutCtaBlock from './save';
import CalloutCtaEdit from './edit';

const {
  blocks: {
    registerBlockType,
  },
  i18n: {
    __,
  },
} = wp;

registerBlockType(
  'alley-pack/callout-cta',
  {
    attributes,
    category: 'widgets',
    description: __(
      'Build a customizable Callout Call-to-Action (CTA)',
      'alleypack'
    ),
    edit: CalloutCtaEdit,
    icon: 'megaphone',
    keywords: [
      __('callout', 'alleypack'),
      __('cta', 'alleypack'),
      __('action', 'alleypack'),
    ],
    // eslint-disable-next-line react/prop-types
    save: ({ attributes: blockAttributes }) => (
      <CalloutCtaBlock
        calloutBackgroundColor={blockAttributes.calloutBackgroundColor}
        calloutButtonText={blockAttributes.calloutButtonText}
        calloutButtonUrl={blockAttributes.calloutButtonUrl}
        calloutImageAltText={blockAttributes.calloutImageAltText}
        calloutImageUrl={blockAttributes.calloutImageUrl}
        contentSource={blockAttributes.contentSource}
        title={blockAttributes.title}
      />
    ),
    supports: {
      html: false,
    },
    title: __('Callout CTA', 'alleypack'),
  }
);
