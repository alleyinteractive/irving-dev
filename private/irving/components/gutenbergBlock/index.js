/* eslint-disable */
import React from 'react';
import PropTypes from 'prop-types';
import blockParser from './blockParser';
// import withAsync from '@components/hoc/withAsync';


// const blockMap = {
//   'core/paragraph': withAsync(
//     () => import('@wordpress/block-library/build/paragraph/save')
//   ),
// };

const GutenbergBlock = (props) => {
  const {
    blockName,
    content,
  } = props;
  const Block = blockParser(blockName, content);

  if (! Block) {
    return null;
  }

  // const blockTag = blockName.replace('core', 'wp').replace('/', ':');
  // const blockObject = parse(
  //   `<!-- ${blockTag} --> ${content} <!-- /${blockTag} -->`
  // );
  // const Block = getSaveElement(blockName);
  console.log(Block);

  return (
    <div>
      <Block />
    </div>
  );
};

GutenbergBlock.propTypes = {
  blockName: PropTypes.string.isRequired,
  content: PropTypes.string.isRequired,
};

export default GutenbergBlock;
