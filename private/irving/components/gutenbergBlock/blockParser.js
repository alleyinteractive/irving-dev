const React = require('react');
const mapValues = require('lodash/mapValues');

/* eslint-disable */
const blockParser = (block) => {
  let  Block = null;

  if (process.env.BROWSER) {
    const { getBlockAttribute } = require('@wordpress/blocks/build/api/parser');
    const Block = require('@wordpress/block-library/build/paragraph/save').default;
    const blockDefinition = require('@wordpress/block-library/src/paragraph/block.json');

    const blockAttributes = mapValues(
      blockDefinition.attributes,
      ( attributeSchema, attributeKey ) => {
        return getBlockAttribute(
          attributeKey,
          attributeSchema,
          content,
          block.attrs
        );
      }
    );

    return () => <Block attributes={blockAttributes} />
  }

  return Block;
};

module.exports = blockParser;
