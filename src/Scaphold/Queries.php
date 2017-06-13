<?php

namespace Bonnier\WP\ContentHub\Editor\Scaphold;

/**
 * Class Queries
 *
 * @package \Bonnier\WP\ContentHub\Editor\Scaphold
 */
class Queries
{
    const GET_COMPOSITES = '  
        query GetComposites($cursor: String!, $limit: Int!) {
            viewer {
            allComposites(first: $limit, after: $cursor) {
              aggregations {
                count
              }
              pageInfo {
                hasNextPage
              }
              edges {
                cursor
                node {
                  id
                }
              }
            }
          }
        }
    ';

    const GET_COMPOSITES_BY_BRAND = '  
        query GetComposites($brandId: ID!, $cursor: String!, $limit: Int!) {
          viewer {
            allComposites(where: {brand: {id: {eq: $brandId}}}, first: $limit, after: $cursor) {
              aggregations {
                count
              }
              pageInfo {
                hasNextPage
              }
              edges {
                cursor
                node {
                  id
                }
              }
            }
          }
        }
    ';

    const GET_COMPOSITE = '
        query GetComposite($id: ID!) {
          getComposite(id: $id) {
            id
            title
            description
            modifiedAt
            createdAt
            publishedAt
            kind
            canonicalUrl
            locale
            advertorialLabel
            status
            magazine
            externalId
            translationSet {
              id
              masterLocale
              composites {
                edges {
                  node {
                    id
                    locale
                  }
                }
              }
            }
            recommended {
              edges {
                node {
                  id
                }
              }
            }
            teasers {
              edges {
                node {
                  title
                  description
                  image {
                    id
                    url
                    locale
                    trait
                    caption
                    altText
                  }
                  kind
                }
              }
            }
            source {
              id
              code
              name
            }
            brand {
              id
              name
              code
            }
            accessRules {
              edges {
                node {
                  id
                  kind
                  values
                  domain
                }
              }
            }
            metaInformation {
              id
              canonicalUrl
              pageTitle
              description
              originalUrl
              socialMediaText
            }
            categories {
              edges {
                node {
                  id
                  name
                }
              }
            }
            content(first: 10000, orderBy: {field: position, direction: ASC}) {
              edges {
                node {
                  id
                  position
                  locked
                  image {
                    id
                    url
                    locale
                    trait
                    caption
                    altText
                    copyright
                  }
                  textItem {
                    id
                    body
                    translationSet {
                      id
                    }
                  }
                  insertedCode {
                    id
                    code
                  }
                  associatedComposite {
                    id
                  }
                  infobox {
                    id
                    title
                    body
                    translationSet {
                      id
                    }
                  }
                  tag {
                    id
                    locale
                    name
                    translationSet {
                      id
                    }
                    vocabulary {
                      id
                      name
                    }
                  }
                  video {
                    id
                    thumbnailUrl
                    caption
                    service
                    locale
                    trait
                    videoIdentifier
                    images {
                      edges {
                        node {
                          id
                          url
                          locale
                          trait
                          caption
                          altText
                          copyright
                        }
                      }
                    }
                  }
                  file {
                    id
                    url
                    caption
                    images {
                      edges {
                        node {
                          id
                          url
                          locale
                          trait
                          caption
                          altText
                          copyright
                        }
                      }
                    }
                    accessRules {
                      edges {
                        node {
                          id
                          kind
                          values
                          domain
                        }
                      }
                    }
                  }
                  inventory {
                    id
                    title
                    items {
                      edges {
                        node {
                          id
                          position
                          name
                          values
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
    ';
}
