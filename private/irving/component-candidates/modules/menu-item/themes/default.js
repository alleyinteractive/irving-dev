import styled from 'styled-components';

/* eslint-disable import/prefer-default-export */
export const Wrapper = styled.span`
  flex: 1 0 auto;
  padding: 0 1.625rem;

  li {
    a {
      color: #12121c;
      font-size: 1.2rem;
      font-weight: 600;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  }
`;

export const Dropdown = styled.ul`
  display: none;
`;
/* eslint-enable */
