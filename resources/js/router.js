import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
    routes: [
        {
            path: '/',
            name: 'index',
            component: require('./index').default
        },
        {
            path: '/phone-book',
            name: 'phone',
            component: require('./phonebook').default
        },
        {
            path: '/send-box',
            name: 'phone',
            component: require('./sendbox').default
        },
        {
            path: '/template-message',
            name: 'template',
            component: require('./template').default
        },
       
    ]
})


export default router
