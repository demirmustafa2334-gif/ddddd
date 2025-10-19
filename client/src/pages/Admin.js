import React, { useState, useEffect } from 'react';
import { Helmet } from 'react-helmet-async';
import { Routes, Route, Link, useNavigate } from 'react-router-dom';
import styled from 'styled-components';
import { FaTachometerAlt, FaBlog, FaCity, FaMapMarkerAlt, FaEnvelope, FaCog, FaSignOutAlt, FaPlus, FaEdit, FaTrash, FaEye } from 'react-icons/fa';
import { api } from '../utils/api';

const AdminContainer = styled.div`
  min-height: 100vh;
  background: ${props => props.theme.colors.backgroundLight};
`;

const AdminHeader = styled.header`
  background: white;
  box-shadow: ${props => props.theme.shadows.small};
  padding: 1rem 0;
  margin-bottom: 2rem;
`;

const AdminHeaderContent = styled.div`
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
`;

const AdminLogo = styled(Link)`
  font-family: ${props => props.theme.fonts.heading};
  font-size: 1.5rem;
  font-weight: 600;
  color: ${props => props.theme.colors.primary};
  text-decoration: none;

  &:hover {
    color: ${props => props.theme.colors.secondary};
  }
`;

const AdminNav = styled.nav`
  display: flex;
  gap: 2rem;
  align-items: center;
`;

const NavLink = styled(Link)`
  color: ${props => props.theme.colors.text};
  text-decoration: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;

  &:hover {
    background: ${props => props.theme.colors.backgroundLight};
    color: ${props => props.theme.colors.primary};
  }

  &.active {
    background: ${props => props.theme.colors.primary};
    color: white;
  }
`;

const LogoutButton = styled.button`
  background: ${props => props.theme.colors.danger};
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;

  &:hover {
    background: #c82333;
  }
`;

const AdminContent = styled.div`
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
`;

const DashboardGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-bottom: 3rem;
`;

const StatCard = styled.div`
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: ${props => props.theme.shadows.small};
  text-align: center;
`;

const StatIcon = styled.div`
  width: 60px;
  height: 60px;
  background: ${props => props.theme.colors.primary};
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.5rem;
  margin: 0 auto 1rem;
`;

const StatValue = styled.div`
  font-size: 2rem;
  font-weight: 700;
  color: ${props => props.theme.colors.primary};
  margin-bottom: 0.5rem;
`;

const StatLabel = styled.div`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
`;

const SectionTitle = styled.h2`
  color: ${props => props.theme.colors.primary};
  margin-bottom: 1.5rem;
  font-size: 1.8rem;
`;

const TableContainer = styled.div`
  background: white;
  border-radius: 8px;
  box-shadow: ${props => props.theme.shadows.small};
  overflow: hidden;
  margin-bottom: 2rem;
`;

const Table = styled.table`
  width: 100%;
  border-collapse: collapse;
`;

const TableHeader = styled.thead`
  background: ${props => props.theme.colors.backgroundLight};
`;

const TableHeaderCell = styled.th`
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  color: ${props => props.theme.colors.text};
  border-bottom: 2px solid ${props => props.theme.colors.border};
`;

const TableBody = styled.tbody``;

const TableRow = styled.tr`
  border-bottom: 1px solid ${props => props.theme.colors.border};

  &:hover {
    background: ${props => props.theme.colors.backgroundLight};
  }
`;

const TableCell = styled.td`
  padding: 1rem;
  color: ${props => props.theme.colors.text};
`;

const ActionButton = styled.button`
  background: ${props => props.variant === 'danger' ? props.theme.colors.danger : props.theme.colors.primary};
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 0.5rem;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;

  &:hover {
    background: ${props => props.variant === 'danger' ? '#c82333' : props.theme.colors.secondary};
  }
`;

const CreateButton = styled(Link)`
  background: ${props => props.theme.colors.success};
  color: white;
  text-decoration: none;
  padding: 0.75rem 1.5rem;
  border-radius: 6px;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
  transition: all 0.3s ease;

  &:hover {
    background: #218838;
    transform: translateY(-2px);
  }
`;

const LoadingState = styled.div`
  text-align: center;
  padding: 4rem 2rem;
  color: ${props => props.theme.colors.textLight};
  font-size: 1.1rem;
`;

const ErrorState = styled.div`
  text-align: center;
  padding: 4rem 2rem;
  color: ${props => props.theme.colors.danger};
`;

const LoginForm = styled.div`
  max-width: 400px;
  margin: 4rem auto;
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: ${props => props.theme.shadows.medium};
`;

const FormGroup = styled.div`
  margin-bottom: 1.5rem;
`;

const Label = styled.label`
  display: block;
  margin-bottom: 0.5rem;
  color: ${props => props.theme.colors.text};
  font-weight: 500;
`;

const Input = styled.input`
  width: 100%;
  padding: 0.75rem;
  border: 2px solid ${props => props.theme.colors.border};
  border-radius: 6px;
  font-size: 1rem;
  transition: border-color 0.3s ease;

  &:focus {
    outline: none;
    border-color: ${props => props.theme.colors.primary};
  }
`;

const SubmitButton = styled.button`
  width: 100%;
  padding: 0.75rem;
  background: ${props => props.theme.colors.primary};
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;

  &:hover {
    background: ${props => props.theme.colors.secondary};
  }

  &:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
`;

const ErrorMessage = styled.div`
  color: ${props => props.theme.colors.danger};
  font-size: 0.9rem;
  margin-top: 0.5rem;
`;

// Dashboard Component
const Dashboard = () => {
  const [stats, setStats] = useState({
    cities: 0,
    districts: 0,
    blogPosts: 0,
    contactMessages: 0
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchStats = async () => {
      try {
        const [citiesRes, districtsRes, blogRes, contactRes] = await Promise.all([
          api.get('/cities'),
          api.get('/districts'),
          api.get('/blog'),
          api.get('/contact')
        ]);

        setStats({
          cities: citiesRes.data.length,
          districts: districtsRes.data.length,
          blogPosts: blogRes.data.posts?.length || 0,
          contactMessages: contactRes.data.messages?.length || 0
        });
      } catch (error) {
        console.error('Error fetching stats:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchStats();
  }, []);

  if (loading) {
    return <LoadingState>İstatistikler yükleniyor...</LoadingState>;
  }

  return (
    <div>
      <SectionTitle>Dashboard</SectionTitle>
      <DashboardGrid>
        <StatCard>
          <StatIcon>
            <FaCity />
          </StatIcon>
          <StatValue>{stats.cities}</StatValue>
          <StatLabel>Şehir</StatLabel>
        </StatCard>
        <StatCard>
          <StatIcon>
            <FaMapMarkerAlt />
          </StatIcon>
          <StatValue>{stats.districts}</StatValue>
          <StatLabel>İlçe</StatLabel>
        </StatCard>
        <StatCard>
          <StatIcon>
            <FaBlog />
          </StatIcon>
          <StatValue>{stats.blogPosts}</StatValue>
          <StatLabel>Blog Yazısı</StatLabel>
        </StatCard>
        <StatCard>
          <StatIcon>
            <FaEnvelope />
          </StatIcon>
          <StatValue>{stats.contactMessages}</StatValue>
          <StatLabel>İletişim Mesajı</StatLabel>
        </StatCard>
      </DashboardGrid>
    </div>
  );
};

// Blog Management Component
const BlogManagement = () => {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchPosts = async () => {
      try {
        const response = await api.get('/blog/admin/all');
        setPosts(response.data);
      } catch (error) {
        console.error('Error fetching posts:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchPosts();
  }, []);

  if (loading) {
    return <LoadingState>Blog yazıları yükleniyor...</LoadingState>;
  }

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
        <SectionTitle>Blog Yönetimi</SectionTitle>
        <CreateButton to="/admin/blog/new">
          <FaPlus />
          Yeni Yazı
        </CreateButton>
      </div>
      
      <TableContainer>
        <Table>
          <TableHeader>
            <tr>
              <TableHeaderCell>Başlık</TableHeaderCell>
              <TableHeaderCell>Yazar</TableHeaderCell>
              <TableHeaderCell>Kategori</TableHeaderCell>
              <TableHeaderCell>Durum</TableHeaderCell>
              <TableHeaderCell>Görüntülenme</TableHeaderCell>
              <TableHeaderCell>İşlemler</TableHeaderCell>
            </tr>
          </TableHeader>
          <TableBody>
            {posts.map(post => (
              <TableRow key={post._id}>
                <TableCell>{post.title}</TableCell>
                <TableCell>{post.author?.firstName} {post.author?.lastName}</TableCell>
                <TableCell>{post.category}</TableCell>
                <TableCell>
                  <span style={{ 
                    color: post.isPublished ? '#28a745' : '#ffc107',
                    fontWeight: '500'
                  }}>
                    {post.isPublished ? 'Yayında' : 'Taslak'}
                  </span>
                </TableCell>
                <TableCell>{post.viewCount}</TableCell>
                <TableCell>
                  <ActionButton>
                    <FaEye />
                    Görüntüle
                  </ActionButton>
                  <ActionButton>
                    <FaEdit />
                    Düzenle
                  </ActionButton>
                  <ActionButton variant="danger">
                    <FaTrash />
                    Sil
                  </ActionButton>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>
    </div>
  );
};

// Contact Messages Component
const ContactMessages = () => {
  const [messages, setMessages] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchMessages = async () => {
      try {
        const response = await api.get('/contact');
        setMessages(response.data.messages);
      } catch (error) {
        console.error('Error fetching messages:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchMessages();
  }, []);

  if (loading) {
    return <LoadingState>Mesajlar yükleniyor...</LoadingState>;
  }

  return (
    <div>
      <SectionTitle>İletişim Mesajları</SectionTitle>
      
      <TableContainer>
        <Table>
          <TableHeader>
            <tr>
              <TableHeaderCell>Ad Soyad</TableHeaderCell>
              <TableHeaderCell>E-posta</TableHeaderCell>
              <TableHeaderCell>Konu</TableHeaderCell>
              <TableHeaderCell>Tarih</TableHeaderCell>
              <TableHeaderCell>Durum</TableHeaderCell>
              <TableHeaderCell>İşlemler</TableHeaderCell>
            </tr>
          </TableHeader>
          <TableBody>
            {messages.map(message => (
              <TableRow key={message._id}>
                <TableCell>{message.name}</TableCell>
                <TableCell>{message.email}</TableCell>
                <TableCell>{message.subject}</TableCell>
                <TableCell>{new Date(message.createdAt).toLocaleDateString('tr-TR')}</TableCell>
                <TableCell>
                  <span style={{ 
                    color: message.isRead ? '#28a745' : '#ffc107',
                    fontWeight: '500'
                  }}>
                    {message.isRead ? 'Okundu' : 'Okunmadı'}
                  </span>
                </TableCell>
                <TableCell>
                  <ActionButton>
                    <FaEye />
                    Görüntüle
                  </ActionButton>
                  <ActionButton variant="danger">
                    <FaTrash />
                    Sil
                  </ActionButton>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>
    </div>
  );
};

// Login Component
const Login = ({ onLogin }) => {
  const [formData, setFormData] = useState({
    email: '',
    password: ''
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const response = await api.post('/auth/login', formData);
      localStorage.setItem('token', response.data.token);
      onLogin(response.data.user);
    } catch (error) {
      setError('Giriş başarısız. Lütfen bilgilerinizi kontrol edin.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <LoginForm>
      <h2 style={{ textAlign: 'center', marginBottom: '2rem', color: '#2c5530' }}>
        Admin Girişi
      </h2>
      <form onSubmit={handleSubmit}>
        <FormGroup>
          <Label htmlFor="email">E-posta</Label>
          <Input
            type="email"
            id="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            required
          />
        </FormGroup>
        <FormGroup>
          <Label htmlFor="password">Şifre</Label>
          <Input
            type="password"
            id="password"
            name="password"
            value={formData.password}
            onChange={handleChange}
            required
          />
        </FormGroup>
        {error && <ErrorMessage>{error}</ErrorMessage>}
        <SubmitButton type="submit" disabled={loading}>
          {loading ? 'Giriş yapılıyor...' : 'Giriş Yap'}
        </SubmitButton>
      </form>
    </LoginForm>
  );
};

// Main Admin Component
const Admin = () => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    const checkAuth = async () => {
      const token = localStorage.getItem('token');
      if (!token) {
        setLoading(false);
        return;
      }

      try {
        const response = await api.get('/auth/me');
        setUser(response.data.user);
      } catch (error) {
        localStorage.removeItem('token');
      } finally {
        setLoading(false);
      }
    };

    checkAuth();
  }, []);

  const handleLogin = (userData) => {
    setUser(userData);
  };

  const handleLogout = () => {
    localStorage.removeItem('token');
    setUser(null);
  };

  if (loading) {
    return <LoadingState>Yükleniyor...</LoadingState>;
  }

  if (!user) {
    return <Login onLogin={handleLogin} />;
  }

  return (
    <AdminContainer>
      <Helmet>
        <title>Admin Panel - Yerel Tanıtım</title>
      </Helmet>

      <AdminHeader>
        <AdminHeaderContent>
          <AdminLogo to="/">Yerel Tanıtım Admin</AdminLogo>
          <AdminNav>
            <NavLink to="/admin" end>Dashboard</NavLink>
            <NavLink to="/admin/blog">Blog</NavLink>
            <NavLink to="/admin/contact">Mesajlar</NavLink>
            <NavLink to="/admin/settings">Ayarlar</NavLink>
            <LogoutButton onClick={handleLogout}>
              <FaSignOutAlt />
              Çıkış
            </LogoutButton>
          </AdminNav>
        </AdminHeaderContent>
      </AdminHeader>

      <AdminContent>
        <Routes>
          <Route path="/" element={<Dashboard />} />
          <Route path="/blog" element={<BlogManagement />} />
          <Route path="/contact" element={<ContactMessages />} />
          <Route path="/settings" element={<div>Ayarlar sayfası yakında...</div>} />
        </Routes>
      </AdminContent>
    </AdminContainer>
  );
};

export default Admin;