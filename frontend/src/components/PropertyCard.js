import React from 'react';
import { useNavigate } from 'react-router-dom';
import './PropertyCard.css';

function PropertyCard({ property }) {
  const navigate = useNavigate();
  const { id, title, price, type, category, bedrooms, bathrooms, area, city, image } = property;

  return (
    <div className="property-card" onClick={() => navigate(`/properties/${id}`)}>
      <div className="card-image">
        <img src={image} alt={title} />
        <span className={`badge ${type}`}>{type === 'sale' ? 'For Sale' : 'For Rent'}</span>
      </div>
      <div className="card-body">
        <h3>{title}</h3>
        <p className="card-location">📍 {city}</p>
        <p className="card-price">
          {type === 'rent'
            ? `€${Number(price).toLocaleString()} / month`
            : `€${Number(price).toLocaleString()}`}
        </p>
        <div className="card-meta">
          {bedrooms > 0 && <span>🛏 {bedrooms} bed</span>}
          {bathrooms > 0 && <span>🚿 {bathrooms} bath</span>}
          <span>📐 {area} m²</span>
          <span className="card-category">{category}</span>
        </div>
      </div>
    </div>
  );
}

export default PropertyCard;
