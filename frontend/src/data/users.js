// Seeded accounts always available for login
const seedUsers = [
  {
    id: 1,
    name: 'Admin',
    email: 'admin@realestate.com',
    password: 'admin123',
    role: 'admin',
  },
];

export function getUsers() {
  const stored = localStorage.getItem('re_users');
  const registered = stored ? JSON.parse(stored) : [];
  return [...seedUsers, ...registered];
}

export function saveUser(user) {
  const stored = localStorage.getItem('re_users');
  const registered = stored ? JSON.parse(stored) : [];
  registered.push(user);
  localStorage.setItem('re_users', JSON.stringify(registered));
}

export function findByEmail(email) {
  return getUsers().find((u) => u.email === email) || null;
}
