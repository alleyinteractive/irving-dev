import styled from 'styled-components';

/* eslint-disable import/prefer-default-export */
export const PaginationWrapper = styled.div`
  align-items: center;
  display: flex;
  justify-content: center;
  width: 100%;

  span,
  a {
    border: 1px solid gray;
    margin: 0 1rem;
    padding: 1rem;
  }

  .current {
    background: #CCC;
  }
`;
/* eslint-enable */
