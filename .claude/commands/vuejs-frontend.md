# Vue.js / Nuxt Frontend for photo.madsnorgaard.net

You are building the headless frontend for photo.madsnorgaard.net — a documentary photo archive. The backend is WordPress REST API at `https://photo.madsnorgaard.net`. The frontend is Nuxt 3 (or Vue 3).

## Project context
- **WP REST base:** `https://photo.madsnorgaard.net/wp-json/wp/v2`
- **Custom namespace:** `https://photo.madsnorgaard.net/wp-json/photo-archive-blocks/v1`
- **CPTs:** `photos`, `stories`, `projects`
- **Taxonomies:** `series` (flat), `subjects` (hierarchical)
- **ACF meta on photos:** `archive_number` (int), `location` (string), `date_taken` (Y-m-d), `camera` (string)
- **Site domain:** `madsnorgaard.net` (Nuxt frontend lives here; WP at photo.madsnorgaard.net)
- **CORS:** photo.madsnorgaard.net allows `https://madsnorgaard.net`, `http://localhost:3000`, `http://localhost:3001`

## Nuxt 3 composable for WP API

```ts
// composables/useWordPress.ts
const WP_BASE = 'https://photo.madsnorgaard.net/wp-json/wp/v2'

export const usePhotos = (params?: Record<string, string | number>) =>
  useFetch<WpPhoto[]>(`${WP_BASE}/photos`, {
    params: { per_page: 20, _embed: true, ...params },
  })

export const usePhoto = (id: number) =>
  useFetch<WpPhoto>(`${WP_BASE}/photos/${id}`, {
    params: { _embed: true },
  })

export const useStories = (params?: Record<string, string | number>) =>
  useFetch<WpStory[]>(`${WP_BASE}/stories`, {
    params: { per_page: 10, _embed: true, ...params },
  })

export const useSeries = () =>
  useFetch<WpTerm[]>(`${WP_BASE}/series`, { params: { per_page: 100 } })

export const useSubjects = () =>
  useFetch<WpTerm[]>(`${WP_BASE}/subjects`, { params: { per_page: 100 } })
```

## TypeScript types

```ts
// types/wordpress.ts
export interface WpPhoto {
  id: number
  slug: string
  title: { rendered: string }
  excerpt: { rendered: string }
  content: { rendered: string }
  featured_media: number
  meta: {
    archive_number: number | null
    location: string | null
    date_taken: string | null   // Y-m-d
    camera: string | null
  }
  series: number[]
  subjects: number[]
  _embedded?: {
    'wp:featuredmedia'?: WpMedia[]
  }
}

export interface WpStory {
  id: number
  slug: string
  title: { rendered: string }
  content: { rendered: string }
  excerpt: { rendered: string }
  featured_media: number
  series: number[]
  _embedded?: {
    'wp:featuredmedia'?: WpMedia[]
  }
}

export interface WpMedia {
  id: number
  source_url: string
  media_details: {
    sizes: {
      [key: string]: { source_url: string; width: number; height: number }
    }
  }
  alt_text: string
}

export interface WpTerm {
  id: number
  slug: string
  name: string
  count: number
  parent: number   // 0 = top-level
}
```

## nuxt.config.ts

```ts
export default defineNuxtConfig({
  runtimeConfig: {
    public: {
      wpBase: 'https://photo.madsnorgaard.net/wp-json/wp/v2',
    },
  },
  nitro: {
    routeRules: {
      // Cache WP API responses at the CDN layer
      '/api/photos/**': { cache: { maxAge: 300 } },
    },
  },
})
```

## Server-side proxy (recommended for production)

Route WP API calls through Nuxt server routes to avoid exposing the WP origin and add caching:

```ts
// server/api/photos.get.ts
export default defineEventHandler(async (event) => {
  const query = getQuery(event)
  const data = await $fetch('https://photo.madsnorgaard.net/wp-json/wp/v2/photos', {
    params: { per_page: 20, _embed: true, ...query },
  })
  return data
})
```

## Image handling

WordPress generates multiple image sizes. Prefer named sizes from the Mauer Stills theme:

| WP size name | Dimensions | Use case |
|---|---|---|
| `mauer_stills_thumb_6` | 1440×1440 | Full-width hero / OG image |
| `mauer_stills_thumb_1` | 780px wide | Half-width tile |
| `mauer_stills_thumb_5` | 420px wide | Grid thumbnail |
| `large` | WP default | Fallback |

```ts
// Get a specific image size from _embedded media
const getImageUrl = (photo: WpPhoto, size = 'mauer_stills_thumb_6'): string => {
  const media = photo._embedded?.['wp:featuredmedia']?.[0]
  return media?.media_details?.sizes?.[size]?.source_url ?? media?.source_url ?? ''
}
```

## Page examples

```vue
<!-- pages/archive/index.vue -->
<script setup lang="ts">
const { data: photos } = await usePhotos({ per_page: 40, _embed: true })
</script>

<template>
  <div class="photo-grid">
    <article v-for="photo in photos" :key="photo.id">
      <NuxtLink :to="`/archive/${photo.slug}`">
        <img
          :src="getImageUrl(photo, 'mauer_stills_thumb_5')"
          :alt="photo.title.rendered"
          loading="lazy"
        />
        <span class="archive-number">
          {{ String(photo.meta.archive_number).padStart(3, '0') }}
        </span>
      </NuxtLink>
    </article>
  </div>
</template>
```

```vue
<!-- pages/archive/[slug].vue -->
<script setup lang="ts">
const route = useRoute()
const { data: photos } = await useFetch<WpPhoto[]>(
  'https://photo.madsnorgaard.net/wp-json/wp/v2/photos',
  { params: { slug: route.params.slug, _embed: true } }
)
const photo = computed(() => photos.value?.[0])
if (!photo.value) throw createError({ statusCode: 404 })
</script>
```

## Security notes for frontend
- **Never** store Application Passwords in frontend code or env vars committed to git
- Use `useRuntimeConfig().public.wpBase` for the API base URL (not hardcoded)
- For authenticated calls (admin UI only), use Application Password with HTTPS only
- Validate photo IDs before sending to API: `Number.isInteger(+id) && +id > 0`

$ARGUMENTS
