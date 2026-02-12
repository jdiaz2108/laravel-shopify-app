import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AppLayout from '@/layouts/AppLayout.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: AppLayout,
      children: [
        {
          path: '',
          redirect: { name: 'products.index' },
        },
        {
          path: 'products',
          name: 'products.index',
          component: () => import('@/views/ProductsView.vue'),
        },
        {
          path: 'products/:slug',
          name: 'products.show',
          component: () => import('@/views/ProductDetailView.vue'),
        },
      ],
    },
  ],
})

export default router
