Vue.component('task', {
    template: '<li><slot></slot></li>'
});
Vue.component('task-list', {
    template: '<ul><slot></slot></ul>'
});

new Vue({
    el: '#root',
    data: {
        message: 'Hello, World'
    }
});