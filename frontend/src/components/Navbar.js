import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import './Navbar.css';

function Navbar() {
  const { user, logout, isAdmin } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  return (
    <nav className="navbar">
      <div className="navbar-brand">
        <Link to="/">RealEstate</Link>
      </div>
      <ul className="navbar-links">
        <li><Link to="/">Home</Link></li>
        <li><Link to="/properties">Properties</Link></li>
        <li><Link to="/contact">Contact</Link></li>
        {user ? (
          <>
            {isAdmin && <li><Link to="/admin">Admin</Link></li>}
            <li>
              <span className="navbar-username">Hi, {user.name}</span>
            </li>
            <li>
              <button className="btn-logout" onClick={handleLogout}>Logout</button>
            </li>
          </>
        ) : (
          <>
            <li><Link to="/login">Login</Link></li>
            <li><Link to="/register" className="btn-register">Register</Link></li>
          </>
        )}
      </ul>
    </nav>
  );
}

export default Navbar;
