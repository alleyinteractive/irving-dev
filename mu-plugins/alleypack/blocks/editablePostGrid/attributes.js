export default {
  backgroundColor: {
    attribute: 'data-background-color',
    default: 'white',
    selector: '.block--type--post-grid',
    source: 'attribute',
    type: 'string',
  },
  heading: {
    default: '',
    selector: '.post-grid--heading',
    source: 'html',
    type: 'string',
  },
  excerpt: {
    default: '',
    selector: '.post-grid--excerpt',
    source: 'html',
    type: 'string',
  },
  theme: {
    attribute: 'data-theme',
    default: '',
    selector: '.block--type--post-grid',
    source: 'attribute',
    type: 'string',
  },
};
