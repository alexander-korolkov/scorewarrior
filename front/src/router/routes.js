const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '/actions-log', component: () => import('pages/ActionsLog.vue'), name: 'ActionsLog', meta: { forEditor: true } },
      { path: '', component: () => import('pages/IndexPage.vue'), meta: { forEditor: false } },
      { path: '/users', component: () => import('pages/UsersList.vue'), meta: { forEditor: true } },
      { path: '/create-user', component: () => import('pages/CreateUser.vue'), name: 'CreateUser', meta: { forEditor: true } },
      { path: '/send-message', component: () => import('pages/SendMessage.vue'), name: 'SendMessage', meta: { forEditor: false } },
      { path: '/grant-item', component: () => import('pages/GrantItem.vue'), name: 'GrantItem', meta: { forEditor: false } },
      { path: '/manage-reviews', component: () => import('pages/ManageReviews.vue'), name: 'ManageReviews', meta: { forEditor: true } },
      { path: '/manage-item-grant-requests', component: () => import('pages/ManageReviews.vue'), name: 'ManageItemGrantRequests', meta: { forEditor: true } },
    ],
  },
  {
    path: '/login', component: () => import('pages/LoginPage.vue'), meta: { forEditor: false }
  },
  {
    path: '/:catchAll(.*)*', component: () => import('pages/ErrorNotFound.vue'), meta: { forEditor: false }
  },
];

export default routes;
