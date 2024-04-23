import {
  createMemoryHistory,
  createRouter,
  createWebHashHistory,
  createWebHistory,
} from 'vue-router';
import { useAuthStore } from 'src/stores/auth';
import { Notify } from 'quasar';
import routes from './routes';

const createHistory = process.env.SERVER
  ? createMemoryHistory
  : (process.env.VUE_ROUTER_MODE === 'history' ? createWebHistory : createWebHashHistory);

const router = createRouter({
  scrollBehavior: () => ({ left: 0, top: 0 }),
  routes,

  // quasar.conf.js -> build -> vueRouterMode
  // quasar.conf.js -> build -> publicPath
  history: createHistory(process.env.VUE_ROUTER_BASE),
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  if (to.path !== '/login' && !authStore.isAuthenticated) {
    next('/login');
  }
  if (to.meta.forEditor && !authStore.isEditor) {
    // In case if rout requires "ROLE_EDITOR"
    Notify.create({
      type: 'negative',
      message: 'You don\'t have access to see this page',
    });
    // Send user back to previous page
    next(from);
  }
  next();
});

export default router;
