import type { DocumentNode } from 'graphql'
import { print } from 'graphql'

const GQL_ENDPOINT = import.meta.env.VITE_GRAPHQL_ENDPOINT ?? '/graphql'

export interface GraphQLResponse<T = unknown> {
  data: T
  errors?: { message: string; locations?: unknown; path?: unknown }[]
}

export class GraphQLError extends Error {
  constructor(
    message: string,
    public readonly errors: GraphQLResponse['errors'],
  ) {
    super(message)
    this.name = 'GraphQLError'
  }
}

async function request<T>(
  query: string | DocumentNode,
  variables: Record<string, unknown> = {},
): Promise<T> {
  const queryString = typeof query === 'string' ? query : print(query)

  const response = await fetch(GQL_ENDPOINT, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
    credentials: 'same-origin',
    body: JSON.stringify({ query: queryString, variables }),
  })

  if (!response.ok) {
    throw new GraphQLError(`HTTP error: ${response.status}`, undefined)
  }

  const json: GraphQLResponse<T> = await response.json()

  if (json.errors?.length) {
    throw new GraphQLError(json.errors.map((e) => e.message).join(' | '), json.errors)
  }

  return json.data
}

export const graphqlClient = { request }
