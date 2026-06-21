import React, { useState } from 'react';
import { submitContact } from '../utils/api';
import './Contact.css';

function Contact() {
  const [form, setForm]           = useState({ name: '', email: '', phone: '', message: '' });
  const [submitted, setSubmitted] = useState(false);
  const [error, setError]         = useState('');
  const [loading, setLoading]     = useState(false);

  const handleChange = (e) =>
    setForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      await submitContact(form);
      setSubmitted(true);
      setForm({ name: '', email: '', phone: '', message: '' });
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to send message. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="contact-page">
      <div className="contact-layout">
        <div className="contact-info">
          <h2>Get in Touch</h2>
          <p>Have questions about a property or need help with your search? We're here to help.</p>
          <div className="info-item"><span>📍</span> Bill Clinton Blvd, Prishtina, Kosovo</div>
          <div className="info-item"><span>📞</span> +383 44 000 000</div>
          <div className="info-item"><span>✉️</span> info@realestate.com</div>
          <div className="info-item"><span>🕐</span> Mon–Fri: 09:00 – 18:00</div>
        </div>

        <div className="contact-form-wrap">
          {submitted ? (
            <div className="success-msg">
              <h3>✅ Message Sent!</h3>
              <p>Thank you for reaching out. We'll get back to you shortly.</p>
              <button onClick={() => setSubmitted(false)}>Send another message</button>
            </div>
          ) : (
            <form className="contact-form" onSubmit={handleSubmit}>
              <h2>Send a Message</h2>

              {error && <p className="auth-error">{error}</p>}

              <div className="form-group">
                <label>Name</label>
                <input type="text"  name="name"    value={form.name}    onChange={handleChange} required />
              </div>
              <div className="form-group">
                <label>Email</label>
                <input type="email" name="email"   value={form.email}   onChange={handleChange} required />
              </div>
              <div className="form-group">
                <label>Phone</label>
                <input type="tel"   name="phone"   value={form.phone}   onChange={handleChange} />
              </div>
              <div className="form-group">
                <label>Message</label>
                <textarea name="message" rows={5} value={form.message} onChange={handleChange} required />
              </div>
              <button type="submit" className="btn-submit" disabled={loading}>
                {loading ? 'Sending...' : 'Send Message'}
              </button>
            </form>
          )}
        </div>
      </div>
    </div>
  );
}

export default Contact;
