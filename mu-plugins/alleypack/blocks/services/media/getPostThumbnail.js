/**
 * Return post thumbnail url for post.
 *
 * @param {object} post post object.
 * @param {string} size thumbnail size.
 * @returns {string} thumbnail url.
 */
export default function getPostThumbnail(post = {}, size = 'thumbnail') {
  // Bail if we don't have a post object.
  if (! post) {
    return '';
  }

  const {
    _embedded = {},
  } = post;

  // Bail if we don't have a the _embedded object.
  if (! _embedded) {
    return '';
  }

  const featuredMedia = _embedded['wp:featuredmedia'];

  // Bail if we don't have featured media.
  if (undefined === featuredMedia || 0 === featuredMedia.length) {
    return '';
  }

  const mediaDetails = _embedded['wp:featuredmedia'][0];

  const {
    media_details: details = {},
  } = mediaDetails;

  const {
    sizes = {},
  } = details;

  // Bail if we don't have media details. We should always have this, but fallback none the less.
  if (! sizes) {
    return '';
  }

  return undefined !== sizes[size] ? sizes[size].source_url : mediaDetails.source_url; // eslint-disable-line max-len
}
