/* global React */

/**
 * Return post thumbnail url for post.
 *
 * @param {object} post post object.
 * @returns {string} authors string.
 */
export default function getPostAuthors(post = {}) {
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

  const authorsList = _embedded.author;

  // Bail if we don't have featured media.
  if (undefined === authorsList || 0 === authorsList.length) {
    return '';
  }

  const authorsListMapped = authorsList.map((author) => {
    const {
      name,
      link,
    } = author;
    return (
      <a key={name} className="author-name" href={link}>
        {name}
      </a>
    );
  });

  return authorsListMapped;
}
