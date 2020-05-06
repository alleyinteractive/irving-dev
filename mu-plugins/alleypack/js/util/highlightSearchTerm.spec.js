/* global describe, expect, it */

import React from 'react';

import highlightSearchTerm from './highlightSearchTerm';

describe('highlightSearchTerm', () => {
  it('Should break apart and highlight search terms in text.', () => {
    expect(highlightSearchTerm(
      '',
      'The quick brown fox jumped over the lazy dog.'
    ))
      .toEqual([
        'The quick brown fox jumped over the lazy dog.',
      ]);
    expect(highlightSearchTerm(
      't',
      'The quick brown fox jumped over the lazy dog.'
    ))
      .toEqual([
        <mark>T</mark>,
        'he quick brown fox jumped over ',
        <mark>t</mark>,
        'he lazy dog.',
      ]);
    expect(highlightSearchTerm(
      'th',
      'The quick brown fox jumped over the lazy dog.'
    ))
      .toEqual([
        <mark>Th</mark>,
        'e quick brown fox jumped over ',
        <mark>th</mark>,
        'e lazy dog.',
      ]);
    expect(highlightSearchTerm(
      'the',
      'The quick brown fox jumped over the lazy dog.'
    ))
      .toEqual([
        <mark>The</mark>,
        ' quick brown fox jumped over ',
        <mark>the</mark>,
        ' lazy dog.',
      ]);
    expect(highlightSearchTerm(
      'the ',
      'The quick brown fox jumped over the lazy dog.'
    ))
      .toEqual([
        <mark>The </mark>,
        'quick brown fox jumped over ',
        <mark>the </mark>,
        'lazy dog.',
      ]);
    expect(highlightSearchTerm(
      'the l',
      'The quick brown fox jumped over the lazy dog.'
    ))
      .toEqual([
        'The quick brown fox jumped over ',
        <mark>the l</mark>,
        'azy dog.',
      ]);
    expect(highlightSearchTerm(
      'the li',
      'The quick brown fox jumped over the lazy dog.'
    ))
      .toEqual([
        'The quick brown fox jumped over the lazy dog.',
      ]);
  });
});
