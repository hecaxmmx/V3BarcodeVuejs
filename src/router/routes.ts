import Main from '~/layouts/Main.layout.vue'
// import { Wallet, Loans, Account, Card, Settings, Login } from '~/pages'
import { Barcode } from '~/pages'

const routes = [
  {
    path: '/',
    component: Main,
    children: [
      {
        name: 'login',
        path: '',
        component: Barcode,
      },
      {
        name: 'barcode',
        path: 'barcode',
        component: Barcode,
        meta: { private: false },
      },
      // {
      //   name: 'wallet',
      //   path: 'wallet',
      //   component: Wallet,
      //   meta: { private: true },
      // },
      // {
      //   name: 'loans',
      //   path: 'loans',
      //   component: Loans,
      //   meta: { private: true },
      // },
      // {
      //   name: 'account',
      //   path: 'account',
      //   component: Account,
      //   meta: { private: true },
      // },
      // {
      //   name: 'card',
      //   path: 'card',
      //   component: Card,
      //   meta: { private: true },
      // },
      // {
      //   name: 'settings',
      //   path: 'settings',
      //   component: Settings,
      //   meta: { private: true },
      // },
    ],
  },
]

export default routes
