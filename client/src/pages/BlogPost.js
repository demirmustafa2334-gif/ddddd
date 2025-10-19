import React from 'react';
import { Helmet } from 'react-helmet-async';
import { Link, useParams } from 'react-router-dom';
import styled from 'styled-components';
import { useQuery } from 'react-query';
import { FaCalendarAlt, FaUser, FaEye, FaArrowLeft, FaArrowRight, FaTag } from 'react-icons/fa';
import { api } from '../utils/api';

const BlogPostContainer = styled.div`
  min-height: 100vh;
  padding: 2rem 0;
`;

const HeaderSection = styled.section`
  background: linear-gradient(135deg, ${props => props.theme.colors.primary} 0%, ${props => props.theme.colors.secondary} 100%);
  color: white;
  padding: 4rem 0;
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

const HeaderContent = styled.div`
  position: relative;
  z-index: 1;
  max-width: 800px;
  margin: 0 auto;
  padding: 0 1rem;
  text-align: center;
`;

const Breadcrumb = styled.div`
  margin-bottom: 1rem;
  font-size: 0.9rem;
  opacity: 0.8;

  a {
    color: white;
    text-decoration: none;

    &:hover {
      text-decoration: underline;
    }
  }
`;

const PostTitle = styled.h1`
  font-size: 2.5rem;
  margin-bottom: 1rem;
  font-weight: 700;
  line-height: 1.2;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    font-size: 2rem;
  }
`;

const PostMeta = styled.div`
  display: flex;
  justify-content: center;
  gap: 2rem;
  flex-wrap: wrap;
  margin-bottom: 1.5rem;
  font-size: 0.9rem;
  opacity: 0.9;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    gap: 1rem;
  }
`;

const MetaItem = styled.div`
  display: flex;
  align-items: center;
  gap: 0.5rem;
`;

const PostExcerpt = styled.p`
  font-size: 1.2rem;
  opacity: 0.9;
  line-height: 1.6;
  max-width: 600px;
  margin: 0 auto;
`;

const ContentSection = styled.section`
  padding: 4rem 0;
`;

const ContentContainer = styled.div`
  max-width: 800px;
  margin: 0 auto;
  padding: 0 1rem;
`;

const PostImage = styled.div`
  width: 100%;
  height: 400px;
  background: linear-gradient(45deg, ${props => props.theme.colors.primary}, ${props => props.theme.colors.secondary});
  border-radius: 12px;
  margin-bottom: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 3rem;
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

const PostContent = styled.div`
  line-height: 1.8;
  font-size: 1.1rem;
  color: ${props => props.theme.colors.text};

  h2 {
    color: ${props => props.theme.colors.primary};
    margin: 2rem 0 1rem;
    font-size: 1.8rem;
  }

  h3 {
    color: ${props => props.theme.colors.primary};
    margin: 1.5rem 0 0.75rem;
    font-size: 1.4rem;
  }

  p {
    margin-bottom: 1.5rem;
  }

  ul, ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
  }

  li {
    margin-bottom: 0.5rem;
  }

  blockquote {
    border-left: 4px solid ${props => props.theme.colors.primary};
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: ${props => props.theme.colors.textLight};
  }

  img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
  }
`;

const TagsSection = styled.div`
  margin: 2rem 0;
  padding: 1.5rem;
  background: ${props => props.theme.colors.backgroundLight};
  border-radius: 8px;
`;

const TagsTitle = styled.h3`
  color: ${props => props.theme.colors.primary};
  margin-bottom: 1rem;
  font-size: 1.2rem;
`;

const TagsList = styled.div`
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
`;

const Tag = styled.span`
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  background: ${props => props.theme.colors.primary};
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
`;

const RelatedDistrictsSection = styled.section`
  padding: 4rem 0;
  background: ${props => props.theme.colors.backgroundLight};
`;

const RelatedDistrictsGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  max-width: 800px;
  margin: 0 auto;
  padding: 0 1rem;
`;

const RelatedDistrictCard = styled(Link)`
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: ${props => props.theme.shadows.small};
  text-decoration: none;
  color: inherit;
  transition: transform 0.3s ease, box-shadow 0.3s ease;

  &:hover {
    transform: translateY(-3px);
    box-shadow: ${props => props.theme.shadows.medium};
  }
`;

const RelatedDistrictName = styled.h4`
  color: ${props => props.theme.colors.primary};
  margin-bottom: 0.5rem;
  font-size: 1.2rem;
`;

const RelatedDistrictDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
  line-height: 1.5;
`;

const NavigationSection = styled.section`
  padding: 2rem 0;
  background: white;
`;

const NavigationContainer = styled.div`
  max-width: 800px;
  margin: 0 auto;
  padding: 0 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    flex-direction: column;
    gap: 1rem;
  }
`;

const NavButton = styled(Link)`
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: ${props => props.theme.colors.primary};
  color: white;
  text-decoration: none;
  border-radius: 25px;
  transition: all 0.3s ease;
  font-weight: 500;

  &:hover {
    background: ${props => props.theme.colors.secondary};
    transform: translateX(${props => props.direction === 'left' ? '-5px' : '5px'});
  }

  &.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
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

const BlogPost = () => {
  const { slug } = useParams();

  const { data: post, isLoading, error } = useQuery(
    ['blog-post', slug],
    () => api.get(`/blog/${slug}`).then(res => res.data),
    {
      enabled: !!slug,
    }
  );

  if (isLoading) {
    return (
      <BlogPostContainer>
        <LoadingState>Blog yazısı yükleniyor...</LoadingState>
      </BlogPostContainer>
    );
  }

  if (error || !post) {
    return (
      <BlogPostContainer>
        <ErrorState>
          Blog yazısı bulunamadı veya bir hata oluştu.
        </ErrorState>
      </BlogPostContainer>
    );
  }

  return (
    <BlogPostContainer>
      <Helmet>
        <title>{post.title} - Yerel Tanıtım Blog</title>
        <meta name="description" content={post.metaDescription || post.excerpt} />
        <meta name="keywords" content={post.seoKeywords?.join(', ') || post.tags?.join(', ') || ''} />
      </Helmet>

      <HeaderSection>
        <HeaderContent>
          <Breadcrumb>
            <Link to="/">Ana Sayfa</Link> / 
            <Link to="/blog">Blog</Link> / 
            {post.title}
          </Breadcrumb>
          <PostTitle>{post.title}</PostTitle>
          <PostMeta>
            <MetaItem>
              <FaUser />
              {post.author?.firstName} {post.author?.lastName}
            </MetaItem>
            <MetaItem>
              <FaCalendarAlt />
              {new Date(post.publishedAt).toLocaleDateString('tr-TR')}
            </MetaItem>
            <MetaItem>
              <FaEye />
              {post.viewCount} görüntülenme
            </MetaItem>
            {post.city && (
              <MetaItem>
                📍 {post.city.name}
                {post.district && ` - ${post.district.name}`}
              </MetaItem>
            )}
          </PostMeta>
          <PostExcerpt>{post.excerpt}</PostExcerpt>
        </HeaderContent>
      </HeaderSection>

      <ContentSection>
        <ContentContainer>
          <PostImage>
            📝
          </PostImage>
          
          <PostContent 
            dangerouslySetInnerHTML={{ __html: post.content }}
          />

          {post.tags && post.tags.length > 0 && (
            <TagsSection>
              <TagsTitle>Etiketler</TagsTitle>
              <TagsList>
                {post.tags.map((tag, index) => (
                  <Tag key={index}>
                    <FaTag />
                    {tag}
                  </Tag>
                ))}
              </TagsList>
            </TagsSection>
          )}
        </ContentContainer>
      </ContentSection>

      {post.relatedDistricts && post.relatedDistricts.length > 0 && (
        <RelatedDistrictsSection>
          <div className="container">
            <h2 className="text-center" style={{ marginBottom: '2rem' }}>
              İlgili İlçeler
            </h2>
            <RelatedDistrictsGrid>
              {post.relatedDistricts.map(district => (
                <RelatedDistrictCard 
                  key={district._id} 
                  to={`/sehir/${post.city?.slug}/${district.slug}`}
                >
                  <RelatedDistrictName>{district.name}</RelatedDistrictName>
                  <RelatedDistrictDescription>{district.description}</RelatedDistrictDescription>
                </RelatedDistrictCard>
              ))}
            </RelatedDistrictsGrid>
          </div>
        </RelatedDistrictsSection>
      )}

      <NavigationSection>
        <NavigationContainer>
          <NavButton to="/blog" direction="left">
            <FaArrowLeft />
            Tüm Yazılar
          </NavButton>
          
          <NavButton to="/blog" direction="right">
            Blog Ana Sayfa
            <FaArrowRight />
          </NavButton>
        </NavigationContainer>
      </NavigationSection>
    </BlogPostContainer>
  );
};

export default BlogPost;