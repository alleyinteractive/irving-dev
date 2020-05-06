/**
 * Custom media queries
 */
export const bkptVal = {
  xxl: 90,
  xl: 80,
  lg: 64,
  md: 48,
  sm: 32,
};

const breakpointObj = Object.keys(bkptVal)
  .reduce((acc, curr) => {
    acc[`${curr}Min`] = `min-width: ${bkptVal[curr]}rem`;
    acc[`${curr}Max`] = `max-width: ${bkptVal[curr] - 0.0001}rem`;
    acc[`${curr}Val`] = `${bkptVal[curr]}rem`;
    return acc;
  }, {});

const breakpoints = {
  adminBarMedMin: 'min-width: 783px',
  adminBarSmMin: 'min-width: 601px',
  bkptVal,
  ...breakpointObj,
};

export const breakpointNames = Object.keys(breakpoints).concat('all');

export default breakpoints;