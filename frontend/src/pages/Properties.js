import React, { useState, useEffect } from 'react';
import { useSearchParams } from 'react-router-dom';
import { getProperties } from '../utils/api';
import PropertyCard from '../components/PropertyCard';
import './Properties.css';

function Properties() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [filters, setFilters] = useState({
    type:     searchParams.get('type')     || '',
    category: searchParams.get('category') || '',
    city:     searchParams.get('city')     || '',
  });
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const params = {};
    if (filters.type)     params.type     = filters.type;
    if (filters.category) params.category = filters.category;
    if (filters.city)     params.city     = filters.city;
    setSearchParams(params);
  }, [filters, setSearchParams]);

  useEffect(() => {
    setLoading(true);
    getProperties(filters)
      .then(setResults)
      .catch(() => setResults([]))
      .finally(() => setLoading(false));
  }, [filters]);

  const handleChange = (e) =>
    setFilters((prev) => ({ ...prev, [e.target.name]: e.target.value }));

  const clearFilters = () =>
    setFilters({ type: '', category: '', city: '' });

  return (
    <div className="properties-page">
      <div className="properties-header">
        <h1>All Properties</h1>
        <p>
          {loading
            ? 'Loading...'
            : `${results.length} listing${results.length !== 1 ? 's' : ''} found`}
        </p>
      </div>

      <div className="properties-layout">
        <aside className="filters">
          <h3>Filters</h3>

          <div className="filter-group">
            <label>Type</label>
            <select name="type" value={filters.type} onChange={handleChange}>
              <option value="">All</option>
              <option value="sale">For Sale</option>
              <option value="rent">For Rent</option>
            </select>
          </div>

          <div className="filter-group">
            <label>Category</label>
            <select name="category" value={filters.category} onChange={handleChange}>
              <option value="">All</option>
              <option value="house">House</option>
              <option value="apartment">Apartment</option>
              <option value="villa">Villa</option>
              <option value="land">Land</option>
              <option value="commercial">Commercial</option>
            </select>
          </div>

          <div className="filter-group">
            <label>City</label>
            <select name="city" value={filters.city} onChange={handleChange}>
              <option value="">All Cities</option>
              <option value="Prishtina">Prishtina</option>
              <option value="Prizren">Prizren</option>
              <option value="Gjilan">Gjilan</option>
              <option value="Peja">Peja</option>
              <option value="Ferizaj">Ferizaj</option>
            </select>
          </div>

          <button className="btn-clear" onClick={clearFilters}>Clear Filters</button>
        </aside>

        <div className="properties-grid">
          {loading ? (
            <p className="no-results">Loading properties...</p>
          ) : results.length > 0 ? (
            results.map((p) => <PropertyCard key={p.id} property={p} />)
          ) : (
            <p className="no-results">No properties match your filters.</p>
          )}
        </div>
      </div>
    </div>
  );
}

export default Properties;
