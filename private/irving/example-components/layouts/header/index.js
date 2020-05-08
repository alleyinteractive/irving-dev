import React from 'react';
import Link from 'components/link';

const Header = () => (
  <header>
    <Link to="/">Home</Link>
    <Link to="/about-us/">About Us</Link>
    <Link to="/yeah/">Yeah</Link>
  </header>
);

export default Header;
