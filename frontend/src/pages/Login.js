import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { login as apiLogin } from '../utils/api';
import { useAuth } from '../context/AuthContext';
import './Auth.css';

function Login() {
  const [form, setForm]       = useState({ email: '', password: '' });
  const [error, setError]     = useState('');
  const [loading, setLoading] = useState(false);
  const { login }             = useAuth();
  const navigate              = useNavigate();

  const handleChange = (e) =>
    setForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      const user = await apiLogin(form.email, form.password);
      login(user);
      navigate(user.role === 'admin' ? '/admin' : '/');
    } catch (err) {
      setError(err.response?.data?.error || err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-container">
      <form className="auth-form" onSubmit={handleSubmit}>
        <h2>Login</h2>

        {error && <p className="auth-error">{error}</p>}

        <div className="form-group">
          <label>Email</label>
          <input
            type="email"
            name="email"
            value={form.email}
            onChange={handleChange}
            required
          />
        </div>

        <div className="form-group">
          <label>Password</label>
          <input
            type="password"
            name="password"
            value={form.password}
            onChange={handleChange}
            required
          />
        </div>

        <button type="submit" className="btn-submit" disabled={loading}>
          {loading ? 'Logging in...' : 'Login'}
        </button>

        <p className="auth-switch">
          Don't have an account? <Link to="/register">Register</Link>
        </p>

        <p className="auth-hint">
          Admin demo: admin@realestate.com / admin123
        </p>
      </form>
    </div>
  );
}

export default Login;
