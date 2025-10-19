import React, { useState } from 'react';
import { Helmet } from 'react-helmet-async';
import styled from 'styled-components';
import { FaMapMarkerAlt, FaPhone, FaEnvelope, FaPaperPlane, FaCheckCircle } from 'react-icons/fa';
import { api } from '../utils/api';

const ContactContainer = styled.div`
  min-height: 100vh;
  padding: 2rem 0;
`;

const HeaderSection = styled.section`
  background: linear-gradient(135deg, ${props => props.theme.colors.primary} 0%, ${props => props.theme.colors.secondary} 100%);
  color: white;
  padding: 4rem 0;
  text-align: center;
`;

const HeaderTitle = styled.h1`
  font-size: 3rem;
  margin-bottom: 1rem;
  font-weight: 700;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    font-size: 2rem;
  }
`;

const HeaderSubtitle = styled.p`
  font-size: 1.2rem;
  opacity: 0.9;
  max-width: 600px;
  margin: 0 auto;
`;

const ContactSection = styled.section`
  padding: 4rem 0;
`;

const ContactGrid = styled.div`
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4rem;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;

  @media (max-width: ${props => props.theme.breakpoints.tablet}) {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
`;

const ContactInfo = styled.div`
  h2 {
    color: ${props => props.theme.colors.primary};
    margin-bottom: 2rem;
    font-size: 2rem;
  }

  p {
    color: ${props => props.theme.colors.textLight};
    line-height: 1.8;
    margin-bottom: 2rem;
  }
`;

const ContactItem = styled.div`
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
  padding: 1rem;
  background: white;
  border-radius: 8px;
  box-shadow: ${props => props.theme.shadows.small};
  transition: transform 0.3s ease;

  &:hover {
    transform: translateY(-2px);
  }
`;

const ContactIcon = styled.div`
  width: 50px;
  height: 50px;
  background: ${props => props.theme.colors.primary};
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.2rem;
  flex-shrink: 0;
`;

const ContactDetails = styled.div`
  h4 {
    color: ${props => props.theme.colors.primary};
    margin-bottom: 0.25rem;
    font-size: 1.1rem;
  }

  p {
    color: ${props => props.theme.colors.textLight};
    margin: 0;
    font-size: 0.9rem;
  }
`;

const ContactForm = styled.div`
  h2 {
    color: ${props => props.theme.colors.primary};
    margin-bottom: 2rem;
    font-size: 2rem;
  }
`;

const Form = styled.form`
  background: white;
  padding: 2rem;
  border-radius: 12px;
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

  &.error {
    border-color: ${props => props.theme.colors.danger};
  }
`;

const TextArea = styled.textarea`
  width: 100%;
  padding: 0.75rem;
  border: 2px solid ${props => props.theme.colors.border};
  border-radius: 6px;
  font-size: 1rem;
  min-height: 120px;
  resize: vertical;
  font-family: inherit;
  transition: border-color 0.3s ease;

  &:focus {
    outline: none;
    border-color: ${props => props.theme.colors.primary};
  }

  &.error {
    border-color: ${props => props.theme.colors.danger};
  }
`;

const Select = styled.select`
  width: 100%;
  padding: 0.75rem;
  border: 2px solid ${props => props.theme.colors.border};
  border-radius: 6px;
  font-size: 1rem;
  background: white;
  transition: border-color 0.3s ease;

  &:focus {
    outline: none;
    border-color: ${props => props.theme.colors.primary};
  }

  &.error {
    border-color: ${props => props.theme.colors.danger};
  }
`;

const ErrorMessage = styled.div`
  color: ${props => props.theme.colors.danger};
  font-size: 0.8rem;
  margin-top: 0.25rem;
`;

const SubmitButton = styled.button`
  width: 100%;
  padding: 1rem;
  background: ${props => props.theme.colors.primary};
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;

  &:hover {
    background: ${props => props.theme.colors.secondary};
    transform: translateY(-2px);
  }

  &:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
  }
`;

const SuccessMessage = styled.div`
  background: ${props => props.theme.colors.success};
  color: white;
  padding: 1rem;
  border-radius: 6px;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
`;

const MapSection = styled.section`
  padding: 4rem 0;
  background: ${props => props.theme.colors.backgroundLight};
`;

const MapContainer = styled.div`
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
  text-align: center;

  h2 {
    color: ${props => props.theme.colors.primary};
    margin-bottom: 2rem;
    font-size: 2rem;
  }
`;

const MapPlaceholder = styled.div`
  width: 100%;
  height: 400px;
  background: linear-gradient(45deg, ${props => props.theme.colors.primary}, ${props => props.theme.colors.secondary});
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.5rem;
  position: relative;
  overflow: hidden;

  &::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
  }
`;

const Contact = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
    city: '',
    district: ''
  });
  const [errors, setErrors] = useState({});
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isSubmitted, setIsSubmitted] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    
    // Clear error when user starts typing
    if (errors[name]) {
      setErrors(prev => ({
        ...prev,
        [name]: ''
      }));
    }
  };

  const validateForm = () => {
    const newErrors = {};

    if (!formData.name.trim()) {
      newErrors.name = 'Ad Soyad gereklidir';
    }

    if (!formData.email.trim()) {
      newErrors.email = 'E-posta gereklidir';
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = 'Geçerli bir e-posta adresi girin';
    }

    if (!formData.subject.trim()) {
      newErrors.subject = 'Konu gereklidir';
    }

    if (!formData.message.trim()) {
      newErrors.message = 'Mesaj gereklidir';
    } else if (formData.message.trim().length < 10) {
      newErrors.message = 'Mesaj en az 10 karakter olmalıdır';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!validateForm()) {
      return;
    }

    setIsSubmitting(true);
    
    try {
      await api.post('/contact', formData);
      setIsSubmitted(true);
      setFormData({
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: '',
        city: '',
        district: ''
      });
    } catch (error) {
      console.error('Contact form error:', error);
      setErrors({ submit: 'Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyin.' });
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <ContactContainer>
      <Helmet>
        <title>İletişim - Yerel Tanıtım</title>
        <meta name="description" content="Yerel Tanıtım ile iletişime geçin. Sorularınız, önerileriniz ve geri bildirimleriniz için bizimle iletişime geçin." />
        <meta name="keywords" content="iletişim, yerel tanıtım, destek, geri bildirim" />
      </Helmet>

      <HeaderSection>
        <div className="container">
          <HeaderTitle>İletişim</HeaderTitle>
          <HeaderSubtitle>
            Sorularınız, önerileriniz ve geri bildirimleriniz için 
            bizimle iletişime geçin.
          </HeaderSubtitle>
        </div>
      </HeaderSection>

      <ContactSection>
        <div className="container">
          <ContactGrid>
            <ContactInfo>
              <h2>Bizimle İletişime Geçin</h2>
              <p>
                Yerel Tanıtım projesi hakkında sorularınız, önerileriniz veya 
                geri bildirimleriniz varsa, aşağıdaki iletişim bilgilerini 
                kullanarak bizimle iletişime geçebilirsiniz.
              </p>

              <ContactItem>
                <ContactIcon>
                  <FaMapMarkerAlt />
                </ContactIcon>
                <ContactDetails>
                  <h4>Adres</h4>
                  <p>İstanbul, Türkiye</p>
                </ContactDetails>
              </ContactItem>

              <ContactItem>
                <ContactIcon>
                  <FaPhone />
                </ContactIcon>
                <ContactDetails>
                  <h4>Telefon</h4>
                  <p>+90 (212) 555 0123</p>
                </ContactDetails>
              </ContactItem>

              <ContactItem>
                <ContactIcon>
                  <FaEnvelope />
                </ContactIcon>
                <ContactDetails>
                  <h4>E-posta</h4>
                  <p>info@yereltanitim.com</p>
                </ContactDetails>
              </ContactItem>
            </ContactInfo>

            <ContactForm>
              <h2>Mesaj Gönder</h2>
              <Form onSubmit={handleSubmit}>
                {isSubmitted && (
                  <SuccessMessage>
                    <FaCheckCircle />
                    Mesajınız başarıyla gönderildi! En kısa sürede size dönüş yapacağız.
                  </SuccessMessage>
                )}

                <FormGroup>
                  <Label htmlFor="name">Ad Soyad *</Label>
                  <Input
                    type="text"
                    id="name"
                    name="name"
                    value={formData.name}
                    onChange={handleChange}
                    className={errors.name ? 'error' : ''}
                    placeholder="Adınız ve soyadınız"
                  />
                  {errors.name && <ErrorMessage>{errors.name}</ErrorMessage>}
                </FormGroup>

                <FormGroup>
                  <Label htmlFor="email">E-posta *</Label>
                  <Input
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    className={errors.email ? 'error' : ''}
                    placeholder="ornek@email.com"
                  />
                  {errors.email && <ErrorMessage>{errors.email}</ErrorMessage>}
                </FormGroup>

                <FormGroup>
                  <Label htmlFor="phone">Telefon</Label>
                  <Input
                    type="tel"
                    id="phone"
                    name="phone"
                    value={formData.phone}
                    onChange={handleChange}
                    placeholder="+90 (5XX) XXX XX XX"
                  />
                </FormGroup>

                <FormGroup>
                  <Label htmlFor="city">Şehir</Label>
                  <Input
                    type="text"
                    id="city"
                    name="city"
                    value={formData.city}
                    onChange={handleChange}
                    placeholder="Yaşadığınız şehir"
                  />
                </FormGroup>

                <FormGroup>
                  <Label htmlFor="district">İlçe</Label>
                  <Input
                    type="text"
                    id="district"
                    name="district"
                    value={formData.district}
                    onChange={handleChange}
                    placeholder="Yaşadığınız ilçe"
                  />
                </FormGroup>

                <FormGroup>
                  <Label htmlFor="subject">Konu *</Label>
                  <Input
                    type="text"
                    id="subject"
                    name="subject"
                    value={formData.subject}
                    onChange={handleChange}
                    className={errors.subject ? 'error' : ''}
                    placeholder="Mesaj konusu"
                  />
                  {errors.subject && <ErrorMessage>{errors.subject}</ErrorMessage>}
                </FormGroup>

                <FormGroup>
                  <Label htmlFor="message">Mesaj *</Label>
                  <TextArea
                    id="message"
                    name="message"
                    value={formData.message}
                    onChange={handleChange}
                    className={errors.message ? 'error' : ''}
                    placeholder="Mesajınızı buraya yazın..."
                  />
                  {errors.message && <ErrorMessage>{errors.message}</ErrorMessage>}
                </FormGroup>

                {errors.submit && <ErrorMessage>{errors.submit}</ErrorMessage>}

                <SubmitButton type="submit" disabled={isSubmitting}>
                  <FaPaperPlane />
                  {isSubmitting ? 'Gönderiliyor...' : 'Mesaj Gönder'}
                </SubmitButton>
              </Form>
            </ContactForm>
          </ContactGrid>
        </div>
      </ContactSection>

      <MapSection>
        <MapContainer>
          <h2>Konumumuz</h2>
          <MapPlaceholder>
            <div>
              <FaMapMarkerAlt style={{ fontSize: '3rem', marginBottom: '1rem' }} />
              <div>Harita</div>
              <div style={{ fontSize: '1rem', opacity: 0.8, marginTop: '0.5rem' }}>
                İstanbul, Türkiye
              </div>
            </div>
          </MapPlaceholder>
        </MapContainer>
      </MapSection>
    </ContactContainer>
  );
};

export default Contact;