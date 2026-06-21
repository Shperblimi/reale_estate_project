import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { getProperty, submitContact } from '../utils/api';
import './PropertyDetail.css';

function PropertyDetail() {
  const { id }   = useParams();
  const navigate = useNavigate();

  const [property, setProperty] = useState(null);
  const [loading, setLoading]   = useState(true);
  const [notFound, setNotFound] = useState(false);

  const [contactForm, setContactForm]       = useState({ name: '', email: '', phone: '', message: '' });
  const [contactSent, setContactSent]       = useState(false);
  const [contactError, setContactError]     = useState('');
  const [contactLoading, setContactLoading] = useState(false);

  useEffect(() => {
    getProperty(id)
      .then(setProperty)
      .catch(() => setNotFound(true))
      .finally(() => setLoading(false));
  }, [id]);

  const handleContactChange = (e) =>
    setContactForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));

  const handleContactSubmit = async (e) => {
    e.preventDefault();
    setContactError('');
    setContactLoading(true);
    try {
      await submitContact({ ...contactForm, property_id: id });
      setContactSent(true);
      setContactForm({ name: '', email: '', phone: '', message: '' });
    } catch (err) {
      setContactError(err.response?.data?.error || 'Failed to send message.');
    } finally {
      setContactLoading(false);
    }
  };

  if (loading) {
    return <div className="detail-not-found"><h2>Loading...</h2></div>;
  }

  if (notFound || !property) {
    return (
      <div className="detail-not-found">
        <h2>Property not found.</h2>
        <button onClick={() => navigate('/properties')}>Back to Listings</button>
      </div>
    );
  }

  const {
    title, description, price, type, category,
    bedrooms, bathrooms, area, address, city, country, image, status,
  } = property;

  return (
    <div className="detail-page">
      <button className="btn-back" onClick={() => navigate(-1)}>← Back</button>

      <div className="detail-layout">
        <div className="detail-left">
          <div className="detail-image">
            <img src={image} alt={title} />
            <span className={`badge ${type}`}>{type === 'sale' ? 'For Sale' : 'For Rent'}</span>
          </div>

          <div className="detail-info">
            <h1>{title}</h1>
            <p className="detail-location">📍 {address}, {city}, {country}</p>
            <p className="detail-price">
              {type === 'rent'
                ? `€${Number(price).toLocaleString()} / month`
                : `€${Number(price).toLocaleString()}`}
            </p>

            <div className="detail-specs">
              {bedrooms > 0  && <div className="spec"><span>🛏</span><p>{bedrooms} Bedrooms</p></div>}
              {bathrooms > 0 && <div className="spec"><span>🚿</span><p>{bathrooms} Bathrooms</p></div>}
              <div className="spec"><span>📐</span><p>{area} m²</p></div>
              <div className="spec"><span>🏠</span><p style={{ textTransform: 'capitalize' }}>{category}</p></div>
              <div className="spec"><span>✅</span><p style={{ textTransform: 'capitalize' }}>{status}</p></div>
            </div>

            <div className="detail-description">
              <h3>Description</h3>
              <p>{description}</p>
            </div>
          </div>
        </div>

        <aside className="detail-contact">
          <h3>Interested in this property?</h3>
          <p>Fill in the form and we'll get back to you shortly.</p>

          {contactSent ? (
            <div className="success-msg">
              <p>✅ Message sent! We'll be in touch soon.</p>
              <button onClick={() => setContactSent(false)}>Send another message</button>
            </div>
          ) : (
            <form onSubmit={handleContactSubmit}>
              {contactError && <p className="auth-error">{contactError}</p>}
              <input
                type="text"
                name="name"
                placeholder="Your name"
                value={contactForm.name}
                onChange={handleContactChange}
                required
              />
              <input
                type="email"
                name="email"
                placeholder="Your email"
                value={contactForm.email}
                onChange={handleContactChange}
                required
              />
              <input
                type="tel"
                name="phone"
                placeholder="Phone number"
                value={contactForm.phone}
                onChange={handleContactChange}
              />
              <textarea
                name="message"
                placeholder="Your message"
                value={contactForm.message}
                onChange={handleContactChange}
                rows={4}
                required
              />
              <button type="submit" disabled={contactLoading}>
                {contactLoading ? 'Sending...' : 'Send Message'}
              </button>
            </form>
          )}
        </aside>
      </div>
    </div>
  );
}

export default PropertyDetail;
