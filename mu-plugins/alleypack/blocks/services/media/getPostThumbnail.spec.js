/* global describe, expect, it */

import getPostThumbnail from './getPostThumbnail';

describe('getPostThumbnail', () => {
  it('Should properly get an image thumbnail from a media object.', () => {
    expect(getPostThumbnail({
      _embedded: {
        'wp:featuredmedia': [{
          media_details: {
            sizes: {
              thumbnail: {
                source_url: '/source/url/thumbnail.jpg',
              },
            },
          },
          source_url: '/source/url/source.jpg',
        }],
      },
    })).toEqual('/source/url/thumbnail.jpg');
  });

  it('Should fall back to full size image if no thumbnail is present.', () => {
    expect(getPostThumbnail({
      _embedded: {
        'wp:featuredmedia': [{
          source_url: '/source/url/source.jpg',
        }],
      },
    })).toEqual('/source/url/source.jpg');
  });

  it('Should return an empty string if there is no image URL present.', () => {
    expect(getPostThumbnail({})).toEqual('');
  });
});
