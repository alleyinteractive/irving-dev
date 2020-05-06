import React from 'react';

/**
 * Given a search term (needle) and a string (haystack), looks for instances of
 * the needle in the haystack and returns an array of JSX elements where any
 * matching search terms in the string are broken up into separate elements
 * in the array and are surrounded by <mark> tags. To use this function in
 * a React component, simply replace the area where you are including the
 * haystack with a call to this function. For example:
 *
 *     <div>{highlightSearchTerm('example', content)}</div>
 *
 * @param {string} needle - The search string.
 * @param {string} haystack - The string to be searched.
 * @returns {array} - An array of JSX elements.
 */
export default function highlightSearchTerm(needle, haystack) {
  /*
   * If needle is an empty string or otherwise empty value, just array-ify haystack.
   * The reason for this is because splitting a string by an empty string results in
   * each individual character in the string getting split out, which isn't what we
   * want.
   */
  if (! needle) {
    return [haystack];
  }

  return haystack
    /*
     * Split by a regex with a capture group, matching case insensitively (i).
     * The capture group ensures that the delimiter in the split, which is the matched text,
     * is present in the result. For example, if we split the string "a, b, c" by the regex
     * /(,)/i we would get ['a', ',', ' b', ',', ' c'] which is what we want for
     * highlighting matches, since the delimiter *is* the search text and that's what we
     * want to highlight.
     */
    .split(new RegExp(`(${needle})`, 'i'))

    /*
     * Ensure that any empty strings that ended up in the result get removed.
     * This can happen if the haystack either begins or ends with the needle.
     * For example, given a haystack of "apple" and a needle of "a" the resultant
     * split would be ['', 'a', 'pple'] so we would want to filter out the
     * empty element in position 0 of the array before continuing.
     */
    .filter((text) => '' !== text)

    // Fork for matches. Enclose matches (irrespective of case) in <mark> tags.
    .map((text) => (
      needle.toLowerCase() === text.toLowerCase()
        ? <mark>{text}</mark>
        : text
    ));
}
