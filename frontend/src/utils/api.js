import axios from 'axios';

const api = axios.create();

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('re_token');
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

// ── Properties ────────────────────────────────────────────────────────────────

export async function getProperties(filters = {}) {
  const params = {};
  if (filters.type)     params.type     = filters.type;
  if (filters.category) params.category = filters.category;
  if (filters.city)     params.city     = filters.city;
  const res = await api.get('/controllers/PropertyController.php', { params });
  return res.data;
}

export async function getProperty(id) {
  const res = await api.get('/controllers/PropertyController.php', { params: { id } });
  return res.data;
}

// ── Contact ───────────────────────────────────────────────────────────────────

export async function submitContact(data) {
  const res = await api.post('/controllers/ContactController.php', data);
  return res.data;
}

// ── Auth ──────────────────────────────────────────────────────────────────────

export async function login(email, password) {
  const res = await api.post('/controllers/AuthController.php?action=login', { email, password });
  const { token, user } = res.data;
  localStorage.setItem('re_token', token);
  localStorage.setItem('re_user', JSON.stringify(user));
  return user;
}

export async function register(name, email, password) {
  await api.post('/controllers/AuthController.php?action=register', { name, email, password });
}

export function logout() {
  localStorage.removeItem('re_token');
  localStorage.removeItem('re_user');
}

export function getCurrentUser() {
  const stored = localStorage.getItem('re_user');
  return stored ? JSON.parse(stored) : null;
}
