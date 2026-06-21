import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { getProperties } from '../utils/api';
import PropertyCard from '../components/PropertyCard';
import './Home.css';

function Home() {
  const [search, setSearch]     = useState('');
  const [type, setType]         = useState('');
  const [featured, setFeatured] = useState([]);
  const navigate                = useNavigate();

  useEffect(() => {
    getProperties().then((data) => setFeatured(data.slice(0, 3))).catch(() => {});
  }, []);

  const handleSearch = (e) => {
    e.preventDefault();
    const params = new URLSearchParams();
    if (search) params.set('city', search);
    if (type)   params.set('type', type);
    navigate(`/properties?${params.toString()}`);
  };

  return (
    <div className="home">
      <section className="hero">
        <div className="hero-content">
          <h1>Find Your Dream Property</h1>
          <p>Browse hundreds of listings for sale and rent across Kosovo.</p>

          <form className="search-bar" onSubmit={handleSearch}>
            <input
              type="text"
              placeholder="Search by city..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />
            <select value={type} onChange={(e) => setType(e.target.value)}>
              <option value="">All Types</option>
              <option value="sale">For Sale</option>
              <option value="rent">For Rent</option>
            </select>
            <button type="submit">Search</button>
          </form>
        </div>
      </section>

      <section className="featured">
        <h2>Featured Properties</h2>
        <div className="property-grid">
          {featured.map((p) => (
            <PropertyCard key={p.id} property={p} />
          ))}
        </div>
        <div className="view-all">
          <button onClick={() => navigate('/properties')}>View All Properties</button>
        </div>
      </section>

      <section className="stats">
        <div className="stat"><span>20+</span><p>Properties Listed</p></div>
        <div className="stat"><span>5</span><p>Cities Covered</p></div>
        <div className="stat"><span>100%</span><p>Trusted Listings</p></div>
      </section>
    </div>
  );
}

export default Home;
