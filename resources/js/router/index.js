import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import About from '../pages/About';
import Register from '../pages/Register';
import Login from '../pages/Login';
import Dashboard from '../pages/Dashboard';

import Books from '../components/Books';
import AddBook from '../components/AddBook';
import EditBook from '../components/EditBook';

import UrlShortener from '../components/UrlShorternerUI';
import UrlManager from '../components/UrlsManager';
import UrlRedirect from '../components/UrlRedirect';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'about',
        path: '/about',
        component: About
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    },
    {
        name: 'books',
        path: '/books',
        component: Books
    },
    {
        name: 'addbook',
        path: '/books/add',
        component: AddBook
    },
    {
        name: 'UrlShortener',
        path: '/url/add',
        component: UrlShortener
    },
    {
        name: 'UrlManager',
        path: '/url/list',
        component: UrlManager
    },
    {
        name: 'UrlRedirect',
        path: '/SL/:id',
        component: UrlRedirect
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
