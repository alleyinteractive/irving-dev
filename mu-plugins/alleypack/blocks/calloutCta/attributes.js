export default {
  calloutBackgroundColor: {
    attribute: 'data-callout-background-color',
    selector: '.block__callout',
    source: 'attribute',
    type: 'string',
  },
  calloutButtonText: {
    selector: '.callout__cta-button-link',
    source: 'text',
    type: 'string',
  },
  calloutButtonUrl: {
    selector: '.callout__cta-button-link',
    source: 'attribute',
    attribute: 'href',
    type: 'string',
  },
  calloutImageAltText: {
    selector: '.callout__image-inner',
    attribute: 'alt',
    source: 'attribute',
    type: 'string',
  },
  calloutImageUrl: {
    selector: '.callout__image-inner',
    attribute: 'src',
    source: 'attribute',
    type: 'string',
  },
  contentSource: {
    selector: '.callout__content-text-source',
    source: 'text',
    type: 'string',
  },
  title: {
    selector: '.callout__title',
    source: 'children',
    type: 'array',
  },
};
